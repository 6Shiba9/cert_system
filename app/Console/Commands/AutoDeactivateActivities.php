<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use Carbon\Carbon;

class AutoDeactivateActivities extends Command
{
    protected $signature = 'activities:deactivate-expired';
    protected $description = 'Deactivate activities that passed end_date';

    public function handle()
    {
        $now = Carbon::now();

        $count = Activity::where('end_date', '<', $now)
                        ->where('is_active', 1)
                        ->update(['is_active' => 0]);

        $this->info("Deactivated {$count} expired activities.");
    }
}
