@extends('partials.dashboard')

@section('title', 'ดูตัวอย่างใบประกาศ')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">ดูตัวอย่างใบประกาศ</h1>
            <p class="text-gray-600 mt-1">{{ $activity->activity_name }}</p>
        </div>
        <a href="{{ route('activity.certificates', $activity->activity_id) }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition duration-200 ease-in-out flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            กลับ
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-100 border border-green-400 text-green-800 shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-red-100 border border-red-400 text-red-800 shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Certificate Info Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Side - Info -->
            <div class="space-y-4">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ข้อมูลใบประกาศ
                </h2>

                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="text-sm font-semibold text-gray-600 w-32">กิจกรรม:</span>
                        <span class="text-sm text-gray-800">{{ $activity->activity_name }}</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-sm font-semibold text-gray-600 w-32">หน่วยงาน:</span>
                        <span class="text-sm text-gray-800">{{ $activity->agency->agency_name }}</span>
                    </div>

                    @if($activity->branch)
                    <div class="flex items-start gap-3">
                        <span class="text-sm font-semibold text-gray-600 w-32">สาขา:</span>
                        <span class="text-sm text-gray-800">{{ $activity->branch->branch_name }}</span>
                    </div>
                    @endif

                    <div class="flex items-start gap-3">
                        <span class="text-sm font-semibold text-gray-600 w-32">วันที่:</span>
                        <span class="text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($activity->end_date)->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-sm font-semibold text-gray-600 w-32">ตำแหน่งชื่อ:</span>
                        <span class="text-sm text-gray-800">X: {{ $activity->position_x }}, Y: {{ $activity->position_y }}</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-sm font-semibold text-gray-600 w-32">รหัสเข้าถึง:</span>
                        <span class="text-sm font-mono bg-gray-100 px-3 py-1 rounded border border-gray-300 font-bold text-gray-800">
                            {{ $activity->access_code }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-gray-200 space-y-3">
                    <a href="{{ route('certificate.preview', $activity->activity_id) }}" 
                       target="_blank"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-3 rounded-xl shadow-md transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        ดู PDF ตัวอย่าง
                    </a>

                    <a href="{{ route('edit-activity', $activity->activity_id) }}" 
                       class="w-full flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-3 rounded-xl shadow-md transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        แก้ไขตำแหน่งชื่อ
                    </a>
                </div>
            </div>

            <!-- Right Side - Preview Image -->
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    ตัวอย่างใบประกาศ
                </h2>

                @if($activity->certificate_img)
                <div class="relative inline-block w-full">
                    <div style="position: relative; width: 100%; padding-bottom: 70.707070707%; background: #f3f4f6; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                        <img src="{{ asset('storage/' . $activity->certificate_img) }}" 
                             alt="Certificate Preview" 
                             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: fill;">
                        
                        <!-- Overlay Text -->
                        <div style="position: absolute; top: {{ ($activity->position_y / 1000) * 100 }}%; left: {{ ($activity->position_x / 1000) * 100 }}%; transform: translate(-50%, -50%);">
                            <div style="color: red; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3); white-space: nowrap;">
                                นายตัวอย่าง ทดสอบภาษาไทย
                            </div>
                        </div>

                        <!-- Position Marker -->
                        <div style="position: absolute; top: {{ ($activity->position_y / 1000) * 100 }}%; left: {{ ($activity->position_x / 1000) * 100 }}%; transform: translate(-50%, -50%); width: 16px; height: 16px; background: #ef4444; border: 3px solid white; border-radius: 50%; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);"></div>
                    </div>

                    <div class="mt-3 text-xs text-gray-600 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-yellow-800 mb-1">หมายเหตุ:</p>
                                <ul class="space-y-1 text-yellow-700">
                                    <li>• จุดสีแดงคือตำแหน่งที่จะแสดงชื่อ</li>
                                    <li>• ข้อความสีแดงเป็นตัวอย่างการแสดงผล</li>
                                    <li>• ใน PDF จริงจะแสดงเป็นสีดำ</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-gray-100 rounded-xl p-12 text-center">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-600 font-semibold mb-2">ยังไม่มีใบประกาศ</p>
                    <p class="text-sm text-gray-500">กรุณาอัพโหลดใบประกาศก่อน</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Instructions Card -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-6 border border-blue-200">
        <h2 class="text-xl font-bold text-blue-900 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            คำแนะนำ
        </h2>
        <div class="space-y-3 text-sm text-blue-800">
            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">1</span>
                <div>
                    <p class="font-semibold mb-1">ตรวจสอบตำแหน่งชื่อ</p>
                    <p class="text-blue-700">คลิก "ดู PDF ตัวอย่าง" เพื่อดูว่าชื่อแสดงในตำแหน่งที่ถูกต้องหรือไม่</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">2</span>
                <div>
                    <p class="font-semibold mb-1">แก้ไขตำแหน่ง (ถ้าจำเป็น)</p>
                    <p class="text-blue-700">หากตำแหน่งไม่ตรง คลิก "แก้ไขตำแหน่งชื่อ" เพื่อปรับให้เหมาะสม</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">3</span>
                <div>
                    <p class="font-semibold mb-1">เพิ่มผู้เข้าร่วม</p>
                    <p class="text-blue-700">เมื่อตำแหน่งถูกต้องแล้ว กลับไปหน้ารายชื่อเพื่อเพิ่มผู้เข้าร่วมกิจกรรม</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('[class*="bg-green-100"], [class*="bg-red-100"]');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>

@endsection