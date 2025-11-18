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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showSummary()
    {
        // General statistics
        $totalActivities = Activity::count();
        $activeActivities = Activity::where('is_active', true)->count();
        $totalParticipants = Participant::count();
        $totalDownloads = DownloadLog::count();
        
        // Recent activities - เรียงตาม activity_id แทน created_at
        $recentActivities = Activity::with(['user'])
            ->orderBy('activity_id', 'desc')
            ->take(5)
            ->get();
        
        // Download statistics
        $todayDownloads = DownloadLog::whereDate('downloaded_at', today())->count();
        $weekDownloads = DownloadLog::whereBetween('downloaded_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        
        // Top activities by participants count
        $topActivities = Activity::withCount('participants')
            ->orderBy('participants_count', 'desc')
            ->take(5)
            ->get();
        
        // Recent download logs - ต้อง eager load ทั้ง participant และ activity
        $recentDownloads = DownloadLog::with(['participant.activity'])
            ->whereHas('participant') // เอาเฉพาะที่มี participant
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
        // ดึงข้อมูล activity พร้อม relationships ที่จำเป็น
        $activity = Activity::with([
            'user',
            'participants'
        ])->findOrFail($activityId);
        
        // คำนวณสถิติการดาวน์โหลด
        $downloadStats = [
            'total' => DownloadLog::whereHas('participant', function($query) use ($activityId) {
                $query->where('activity_id', $activityId);
            })->count(),
            
            'unique_users' => DownloadLog::whereHas('participant', function($query) use ($activityId) {
                $query->where('activity_id', $activityId);
            })->distinct('participant_id')->count('participant_id'),
            
            'today' => DownloadLog::whereHas('participant', function($query) use ($activityId) {
                $query->where('activity_id', $activityId);
            })->whereDate('downloaded_at', today())->count(),
        ];
        
        // ข้อมูลการดาวน์โหลดรายวัน (30 วันล่าสุด)
        $dailyDownloads = DownloadLog::whereHas('participant', function($query) use ($activityId) {
                $query->where('activity_id', $activityId);
            })
            ->selectRaw('DATE(downloaded_at) as date, COUNT(*) as downloads')
            ->where('downloaded_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('d/m'),
                    'downloads' => $item->downloads
                ];
            });
        
        return view('dashboard.activity-details', compact('activity', 'downloadStats', 'dailyDownloads'));
    }

    public function exportDownloadLog(Request $request)
    {
        $query = DownloadLog::with(['participant']);
        
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
        
        // เพิ่ม BOM สำหรับ UTF-8
        $csvData = "\xEF\xBB\xBF";
        $csvData .= "วันที่ดาวน์โหลด,ชื่อผู้เข้าร่วม,อีเมล,รหัสนักศึกษา,IP Address,User Agent\n";
        
        foreach ($logs as $log) {
            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s","%s"' . "\n",
                $log->downloaded_at->format('Y-m-d H:i:s'),
                $log->participant->name ?? 'N/A',
                $log->participant->email ?? 'N/A',
                $log->participant->student_id ?? 'N/A',
                $log->ip_address ?? 'N/A',
                str_replace('"', '""', $log->user_agent ?? 'N/A')
            );
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="download_log_' . date('Y-m-d_His') . '.csv"');
    }

    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
        $totalActivities = Activity::count();
        $activeActivities = Activity::where('is_active', true)->count();
        $totalParticipants = Participant::count();
        $totalCertificates = Participant::where('certificate_generated', true)->count();
        
        $activities = Activity::with(['agency', 'branch', 'participants', 'user'])
                             ->orderBy('activity_id', 'desc')
                             ->limit(6)
                             ->get();
        } else {
        $totalActivities = Activity::where('user_id', $user->user_id)->count();
        $activeActivities = Activity::where('user_id', $user->user_id)
                                    ->where('is_active', true)
                                    ->count();

        $totalParticipants = Participant::whereHas('activity', function($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->count();
        
        $totalCertificates = Participant::whereHas('activity', function($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->where('certificate_generated', true)->count();
        
        $activities = Activity::where('user_id', $user->user_id)
                             ->with(['agency', 'branch', 'participants'])
                             ->orderBy('activity_id', 'desc')
                             ->limit(6)
                             ->get();
        }

        return view('dashboard.admin-dashboard', compact(
            'totalActivities',
            'activeActivities',
            'totalParticipants',
            'totalCertificates',
            'activities'
        ));
    }
    
    /**
     * User Dashboard
     */
    public function userDashboard()
    {
         $activities = Activity::with(['agency', 'branch', 'user'])
            ->get();
        
        return view('dashboard.users-dashboard', compact('activities'));
    }

    public function dashboard(Request $request)
{
    $query = Activity::with(['agency', 'participants'])
                     ->where('is_active', true)
                     ->orderBy('created_at', 'desc');
    
    // ถ้ามีการค้นหา
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('activity_name', 'LIKE', "%{$search}%")
              ->orWhere('access_code', 'LIKE', "%{$search}%");
        });
    }
    
    $activities = $query->paginate(12);
    
    return view('users-dashboard', compact('activities'));
}
}