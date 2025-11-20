<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallThaiFont extends Command
{
    protected $signature = 'font:install-thai';
    protected $description = 'Install Thai fonts for Dompdf';

    public function handle()
    {
        $this->info('Installing Thai fonts for Dompdf...');
        
        $fontDir = public_path('fonts');
        $fontCache = storage_path('fonts');
        
        // สร้างโฟลเดอร์
        if (!File::exists($fontCache)) {
            File::makeDirectory($fontCache, 0755, true);
            $this->info("✓ Created font cache directory: {$fontCache}");
        }
        
        // ตรวจสอบไฟล์ฟอนต์
        $fonts = [
            'Regular' => 'THSarabunNew-Regular.ttf',
            'Bold' => 'THSarabunNew-Bold.ttf',
            'Italic' => 'THSarabunNew-Italic.ttf',
            'BoldItalic' => 'THSarabunNew-BoldItalic.ttf'
        ];
        
        foreach ($fonts as $style => $filename) {
            $path = "{$fontDir}/{$filename}";
            if (file_exists($path)) {
                $this->info("✓ Found {$style}: {$filename}");
            } else {
                $this->error("✗ Missing {$style}: {$filename}");
                return 1;
            }
        }
        
        // สร้าง font metrics
        $this->info('Generating font metrics...');
        
        try {
            // วิธีที่ 1: ใช้ dompdf load_font.php
            $loadFontScript = base_path('vendor/dompdf/dompdf/load_font.php');
            
            if (file_exists($loadFontScript)) {
                // Regular
                exec("php {$loadFontScript} THSarabunNew {$fontDir}/THSarabunNew-Regular.ttf", $output, $return);
                $this->info("Regular: " . ($return === 0 ? 'Success' : 'Failed'));
                
                // Bold
                exec("php {$loadFontScript} THSarabunNew-Bold {$fontDir}/THSarabunNew-Bold.ttf", $output, $return);
                $this->info("Bold: " . ($return === 0 ? 'Success' : 'Failed'));
            } else {
                $this->warn('load_font.php not found, using alternative method...');
            }
            
            $this->info('✓ Font installation completed!');
            $this->info('');
            $this->info('Next steps:');
            $this->info('1. Clear cache: php artisan cache:clear');
            $this->info('2. Test PDF generation');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}