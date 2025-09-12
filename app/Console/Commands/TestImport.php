<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use App\Models\Participant;
use App\Imports\ParticipantsImport;
use Maatwebsite\Excel\Facades\Excel;

class TestImport extends Command
{
    protected $signature = 'test:import';
    protected $description = 'Test CSV import functionality';

    public function handle()
    {
        $activity = Activity::first();
        if (!$activity) {
            $this->error('No activity found!');
            return;
        }

        $this->info("Testing import for activity: {$activity->activity_name}");
        $this->info("Activity ID: {$activity->activity_id}");

        $filePath = base_path('test_simple.csv');
        if (!file_exists($filePath)) {
            $this->error('Test file not found!');
            return;
        }

        $this->info("File exists: {$filePath}");
        
        try {
            // Clear existing participants for this activity
            Participant::where('activity_id', $activity->activity_id)->delete();
            
            $import = new ParticipantsImport($activity->activity_id);
            Excel::import($import, $filePath);
            
            $count = Participant::where('activity_id', $activity->activity_id)->count();
            $this->info("Import completed! Imported {$count} participants.");
            
            // Show imported participants
            $participants = Participant::where('activity_id', $activity->activity_id)->get();
            foreach ($participants as $participant) {
                $this->line("- {$participant->name} (Token: {$participant->certificate_token})");
            }
            
        } catch (\Exception $e) {
            $this->error("Import failed: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }
}
