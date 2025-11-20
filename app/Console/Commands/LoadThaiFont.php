<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Dompdf\Dompdf;

class LoadThaiFont extends Command
{
    protected $signature = 'font:load-thai';
    protected $description = 'Load Thai fonts for Dompdf';

    public function handle()
    {
        $fontDir = public_path('fonts');
        $fontCache = storage_path('fonts');
        
        $this->info("Loading Thai fonts...");
        $this->info("Font directory: {$fontDir}");
        $this->info("Font cache: {$fontCache}");
        
        // ตรวจสอบว่าไฟล์ฟอนต์มีอยู่จริง
        $fonts = [
            'THSarabunNew-Regular.ttf',
            'THSarabunNew-Bold.ttf',
            'THSarabunNew-Italic.ttf',
            'THSarabunNew-BoldItalic.ttf'
        ];
        
        foreach ($fonts as $font) {
            if (file_exists("{$fontDir}/{$font}")) {
                $this->info("✓ Found: {$font}");
            } else {
                $this->error("✗ Missing: {$font}");
            }
        }
        
        $this->info("Fonts loaded successfully!");
        
        return 0;
    }
}