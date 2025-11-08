@extends('partials.dashboard')

@section('title', 'สรุปข้อมูลระบบ')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">สรุปข้อมูลระบบ</h1>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">กิจกรรมทั้งหมด</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalActivities }}</p>
                    <p class="text-sm text-gray-500">เปิดใช้งาน {{ $activeActivities }} กิจกรรม</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">ผู้เข้าร่วมทั้งหมด</h3>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalParticipants) }}</p>
                    <p class="text-sm text-gray-500">คน</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">ดาวน์โหลดทั้งหมด</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($totalDownloads) }}</p>
                    <p class="text-sm text-gray-500">วันนี้ {{ $todayDownloads }} ครั้ง</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">สัปดาห์นี้</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($weekDownloads) }}</p>
                    <p class="text-sm text-gray-500">ดาวน์โหลด</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Monthly Downloads Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">สถิติดาวน์โหลดรายเดือน</h3>
            <canvas id="monthlyChart" width="400" height="200"></canvas>
        </div>

        <!-- Activity Status Pie Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">สถานะกิจกรรม</h3>
            <canvas id="statusChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Top Activities -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">กิจกรรมยอดนิยม (ตามจำนวนผู้เข้าร่วม)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">กิจกรรม</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">รหัส</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">สถานะ</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ผู้เข้าร่วม</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topActivities as $activity)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-5 py-5 text-sm">
                            <p class="text-gray-900 font-semibold">{{ $activity->activity_name }}</p>
                        </td>
                        <td class="px-5 py-5 text-sm">
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $activity->access_code }}</code>
                        </td>
                        <td class="px-5 py-5 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full {{ $activity->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $activity->is_active ? 'เปิดใช้งาน' : 'ปิด' }}
                            </span>
                        </td>
                        <td class="px-5 py-5 text-sm">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                {{ $activity->participants_count }} คน
                            </span>
                        </td>
                        <td class="px-5 py-5 text-sm">
                            <a href="{{ route('activity-details', $activity->activity_id) }}" 
                               class="text-blue-600 hover:text-blue-900 hover:underline">ดูรายละเอียด</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-5 text-center text-gray-500">
                            ไม่มีข้อมูลกิจกรรม
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Downloads -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">ดาวน์โหลดล่าสุด</h3>
            <a href="{{ route('export-download-log') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                ส่งออก CSV
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">วันเวลา</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">กิจกรรม</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ชื่อผู้เข้าร่วม</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentDownloads as $log)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-5 py-5 text-sm text-gray-700">
                            {{ $log->downloaded_at ? $log->downloaded_at->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-5 py-5 text-sm">
                            @if($log->participant && $log->participant->activity)
                                <p class="text-gray-900">{{ $log->participant->activity->activity_name }}</p>
                            @else
                                <span class="text-gray-400 italic">ไม่มีข้อมูล</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 text-sm text-gray-700">
                            {{ $log->participant ? $log->participant->name : 'N/A' }}
                        </td>
                        <td class="px-5 py-5 text-sm text-gray-700 font-mono">
                            {{ $log->ip_address ?? 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-5 text-center text-gray-500">
                            ยังไม่มีการดาวน์โหลด
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Downloads Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: @json(array_column($monthlyDownloads, 'month')),
        datasets: [{
            label: 'ดาวน์โหลด',
            data: @json(array_column($monthlyDownloads, 'count')),
            borderColor: '#3B82F6',
            backgroundColor: '#3B82F640',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Activity Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['เปิดใช้งาน', 'ปิดใช้งาน'],
        datasets: [{
            data: [{{ $activityStats['active'] }}, {{ $activityStats['inactive'] }}],
            backgroundColor: ['#10B981', '#EF4444']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

@endsection