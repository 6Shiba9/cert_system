<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Participant;
use App\Models\DownloadLog;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function showSummary()
    {
        // General statistics
        $totalActivities = Activity::count();
        $activeActivities = Activity::where('is_active', true)->count();
        $totalParticipants = Participant::count();
        $totalDownloads = DownloadLog::count();
        
        // Recent activities
        $recentActivities = Activity::with(['agency', 'branch', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Download statistics
        $todayDownloads = DownloadLog::whereDate('downloaded_at', today())->count();
        $weekDownloads = DownloadLog::whereBetween('downloaded_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        
        // Top activities by downloads
        $topActivities = Activity::withCount('participants')
            ->with(['agency', 'branch'])
            ->orderBy('participants_count', 'desc')
            ->take(5)
            ->get();
        
        // Recent download logs
        $recentDownloads = DownloadLog::with(['participant.activity'])
            ->orderBy('downloaded_at', 'desc')
            ->take(10)
            ->get();
        
        // Monthly download chart data
        $monthlyDownloads = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = DownloadLog::whereYear('downloaded_at', $date->year)
                ->whereMonth('downloaded_at', $date->month)
                ->count();
            $monthlyDownloads[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        // Activity status distribution
        $activityStats = [
            'active' => Activity::where('is_active', true)->count(),
            'inactive' => Activity::where('is_active', false)->count(),
        ];
        
        return view('dashboard.summary', compact(
            'totalActivities',
            'activeActivities', 
            'totalParticipants',
            'totalDownloads',
            'recentActivities',
            'todayDownloads',
            'weekDownloads',
            'topActivities',
            'recentDownloads',
            'monthlyDownloads',
            'activityStats'
        ));
    }

    public function showActivityDetails($activityId)
    {
        $activity = Activity::with([
            'agency', 
            'branch', 
            'user', 
            'participants.downloadLogs'
        ])->findOrFail($activityId);
        
        $downloadStats = [
            'total_participants' => $activity->participants->count(),
            'downloaded' => $activity->participants->whereNotNull('downloaded_at')->count(),
            'not_downloaded' => $activity->participants->whereNull('downloaded_at')->count(),
        ];
        
        $dailyDownloads = DownloadLog::whereHas('participant', function($query) use ($activityId) {
                $query->where('activity_id', $activityId);
            })
            ->selectRaw('DATE(downloaded_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();
        
        return view('dashboard.activity_details', compact('activity', 'downloadStats', 'dailyDownloads'));
    }

    public function exportDownloadLog(Request $request)
    {
        $query = DownloadLog::with(['participant.activity']);
        
        if ($request->activity_id) {
            $query->whereHas('participant', function($q) use ($request) {
                $q->where('activity_id', $request->activity_id);
            });
        }
        
        if ($request->start_date) {
            $query->whereDate('downloaded_at', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->whereDate('downloaded_at', '<=', $request->end_date);
        }
        
        $logs = $query->orderBy('downloaded_at', 'desc')->get();
        
        $csvData = "วันที่ดาวน์โหลด,กิจกรรม,ชื่อผู้เข้าร่วม,IP Address,User Agent\n";
        
        foreach ($logs as $log) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $log->downloaded_at->format('Y-m-d H:i:s'),
                $log->participant->activity->activity_name,
                $log->participant->name,
                $log->ip_address,
                $log->user_agent
            );
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="download_log_' . date('Y-m-d') . '.csv"');
    }
}
