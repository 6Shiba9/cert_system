@extends('partials.dashboard')

@section('title', 'รายละเอียดกิจกรรม - ' . $activity->activity_name)

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $activity->activity_name }}</h1>
                    <p class="text-gray-600">
                        รหัส: {{ $activity->access_code }} | 
                        สร้างโดย: {{ $activity->user ? $activity->user->name : 'ไม่ระบุ' }}
                    </p>
                </div>
                <a href="{{ route('summary') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    ← กลับไปแดชบอร์ด
                </a>
            </div>
        </div>

        <!-- Activity Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Activity Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">ข้อมูลกิจกรรม</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">วันที่เริ่มต้น:</span>
                        <p class="font-medium">
                            {{ $activity->start_date ? $activity->start_date->format('d/m/Y') : 'ไม่ระบุ' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">วันที่สิ้นสุด:</span>
                        <p class="font-medium">
                            {{ $activity->end_date ? $activity->end_date->format('d/m/Y') : 'ไม่ระบุ' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">จำนวนผู้เข้าร่วม:</span>
                        <p class="font-medium">{{ $activity->participants->count() }} คน</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">รหัสเข้าถึง:</span>
                        <p class="font-medium font-mono">{{ $activity->access_code }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">สถานะ:</span>
                        <p class="font-medium">
                            <span class="px-2 py-1 rounded-full text-xs {{ $activity->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $activity->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Download Statistics -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">สถิติการดาวน์โหลด</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">ดาวน์โหลดทั้งหมด:</span>
                        <p class="font-medium text-2xl text-blue-600">{{ $downloadStats['total'] }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">ผู้ใช้ที่ดาวน์โหลด:</span>
                        <p class="font-medium">{{ $downloadStats['unique_users'] }} คน</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">ดาวน์โหลดวันนี้:</span>
                        <p class="font-medium">{{ $downloadStats['today'] }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">อัตราการดาวน์โหลด:</span>
                        <p class="font-medium">{{ number_format(($downloadStats['unique_users'] / max($activity->participants->count(), 1)) * 100, 1) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Certificate Preview -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">ตัวอย่างใบประกาศ</h3>
                <div class="text-center">
                    @if($activity->certificate_img)
                        <img src="{{ asset('storage/' . $activity->certificate_img) }}" 
                             alt="Certificate Template" 
                             class="max-w-full h-auto rounded-lg border">
                        <p class="text-sm text-gray-600 mt-2">
                            ตำแหน่งชื่อ: X={{ $activity->position_x }}, Y={{ $activity->position_y }}
                        </p>
                    @else
                        <div class="bg-gray-100 rounded-lg p-8 text-gray-400">
                            ไม่มีเทมเพลตใบประกาศ
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Daily Downloads Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">กราฟการดาวน์โหลดรายวัน</h3>
            <div class="h-64">
                <canvas id="dailyDownloadsChart"></canvas>
            </div>
        </div>

        <!-- Recent Downloads Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">ล็อกการดาวน์โหลดล่าสุด</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ผู้ดาวน์โหลด
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                อีเมล
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                วันที่ดาวน์โหลด
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($activity->downloadLogs()->with('participant')->latest('downloaded_at')->limit(20)->get() as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $log->participant ? $log->participant->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->participant ? $log->participant->email : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->downloaded_at ? $log->downloaded_at->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                ยังไม่มีการดาวน์โหลด
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Downloads Chart
const dailyData = @json($dailyDownloads);
const ctx = document.getElementById('dailyDownloadsChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: dailyData.map(item => item.date),
        datasets: [{
            label: 'จำนวนการดาวน์โหลด',
            data: dailyData.map(item => item.downloads),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endsection