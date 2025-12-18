@extends('partials.dashboard')

@section('title', 'จัดการกิจกรรม')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">จัดการกิจกรรม</h1>
            <p class="text-gray-600 mt-1">จัดการและติดตามกิจกรรมทั้งหมดของคุณ</p>
        </div>
        <a href="{{ route('add-activity') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition duration-200 ease-in-out flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            เพิ่มกิจกรรมใหม่
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div id="alert-success" class="mb-6 p-4 rounded-xl bg-green-100 border border-green-400 text-green-800 shadow-sm animate-fadeIn">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-semibold">สำเร็จ!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="alert-error" class="mb-6 p-4 rounded-xl bg-red-100 border border-red-400 text-red-800 shadow-sm animate-fadeIn">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-semibold">ข้อผิดพลาด!</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-100 border border-red-400 text-red-800 shadow-sm">
        <p class="font-semibold mb-2">พบข้อผิดพลาด:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">กิจกรรมทั้งหมด</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $activities->count() }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">กิจกรรมที่เปิด</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $activities->where('is_active', true)->count() }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">ผู้เข้าร่วมทั้งหมด</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $activities->sum(function($a) { return $a->participants->count(); }) }}</h3>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">กิจกรรมที่ปิด</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $activities->where('is_active', false)->count() }}</h3>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 uppercase text-xs font-bold">
                        <th class="px-6 py-4 border-b text-left">กิจกรรม</th>
                        <th class="px-6 py-4 border-b text-left">หน่วยงาน</th>
                        <th class="px-6 py-4 border-b text-center">วันที่</th>
                        <th class="px-6 py-4 border-b text-center">รหัสเข้าถึง</th>
                        <th class="px-6 py-4 border-b text-center">ผู้เข้าร่วม</th>
                        <th class="px-6 py-4 border-b text-center">สถานะ</th>
                        <th class="px-6 py-4 border-b text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($activities as $activity)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <!-- Activity Name with Image -->
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($activity->certificate_img)
                                <img class="w-12 h-12 rounded-lg object-cover shadow-md" 
                                     src="{{ asset('storage/' . $activity->certificate_img) }}" 
                                     alt="Certificate">
                                @else
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-gray-900 line-clamp-1">{{ $activity->activity_name }}</p>
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $activity->user->name }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Agency & Branch -->
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-semibold text-gray-900">{{ $activity->agency->agency_name }}</p>
                                @if($activity->branch)
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">
                                        {{ $activity->branch->branch_name }}
                                    </span>
                                </p>
                                @endif
                            </div>
                        </td>

                        <!-- Date Range -->
                        <td class="px-6 py-4 text-center">
                            <div class="text-xs">
                                <p class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }}</p>
                                <p class="text-gray-500">ถึง</p>
                                <p class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($activity->end_date)->format('d/m/Y') }}</p>
                            </div>
                        </td>

                        <!-- Access Code -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 border border-gray-300 font-mono text-sm font-bold text-gray-800 shadow-sm">
                                {{ $activity->access_code }}
                            </span>
                        </td>

                        <!-- Participants -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-blue-100 text-blue-800 text-sm font-bold">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    {{ $activity->participants->count() }} คน
                                </span>
                                <button onclick="openParticipantsModal({{ $activity->activity_id }})" 
                                        class="text-xs text-green-600 hover:text-green-800 font-semibold flex items-center gap-1 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    อัพโหลด Excel
                                </button>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold shadow-sm
                                {{ $activity->is_active ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                                @if($activity->is_active)
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    เปิดใช้งาน
                                @else
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    ปิดใช้งาน
                                @endif
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center gap-2">
                                <a href="{{ route('activity.certificates', $activity->activity_id) }}" 
                                   class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    รายชื่อ
                                </a>
                                <a href="{{ route('edit-activity', $activity->activity_id) }}" 
                                   class="w-full text-center bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    แก้ไข
                                </a>
                                @if(!empty($activity->certificate_img))
                                    {{-- ถ้ามีใบประกาศแล้ว แสดงปุ่มแบบ disabled --}}
                                    <div class="relative group">
                                        <button 
                                            disabled
                                            class="w-full text-center bg-gray-400 text-white text-xs font-semibold px-3 py-2 rounded-lg cursor-not-allowed flex items-center justify-center gap-1 opacity-60">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            มีใบประกาศแล้ว
                                        </button>
                                        {{-- Tooltip เมื่อ hover --}}
                                        <div class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                                            มีใบประกาศในระบบแล้ว
                                        </div>
                                    </div>
                                @else
                                    {{-- ถ้ายังไม่มีใบประกาศ แสดงปุ่มปกติ --}}
                                    <a href="{{ route('add-certificate', $activity->activity_id) }}" 
                                        class="w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        ใบประกาศ
                                    </a>
                                @endif
                                <form action="{{ route('delete-activity', $activity->activity_id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center justify-center gap-1" 
                                            onclick="return confirm('⚠️ คุณแน่ใจหรือไม่ว่าต้องการลบกิจกรรมนี้?\n\nข้อมูลทั้งหมดรวมถึงผู้เข้าร่วมจะถูกลบอย่างถาวร!')">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        ลบ
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">ยังไม่มีกิจกรรม</h3>
                            <p class="text-gray-500 mb-4">เริ่มสร้างกิจกรรมแรกของคุณเลย</p>
                            <a href="{{ route('add-activity') }}" 
                               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                สร้างกิจกรรมใหม่
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Upload Participants Modal -->
<div id="participants-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 relative animate-fadeIn">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                อัพโหลดรายชื่อผู้เข้าร่วม
            </h3>
            <button onclick="closeParticipantsModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- ปุ่มดาวน์โหลดไฟล์ต้นแบบ -->
        <div class="mb-5">
            <a href="{{ route('download-template-general') }}" 
                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                ดาวน์โหลดไฟล์ต้นแบบ Excel
            </a>
        </div>

        <form id="participants-form" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">เลือกไฟล์ Excel</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-green-400 transition">
                    <input type="file" name="participants_file" id="excel-file-upload" 
                        accept=".xlsx,.xls,.csv"
                        class="hidden"
                        required>
                    <label for="excel-file-upload" class="cursor-pointer">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm font-semibold text-gray-700">คลิกเพื่อเลือกไฟล์</p>
                        <p class="text-xs text-gray-500 mt-1">รองรับ .xlsx, .xls, .csv</p>
                    </label>
                </div>
                <p id="file-name-upload" class="text-sm text-gray-600 mt-2 text-center"></p>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                <p class="text-xs font-bold text-blue-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    📋 รูปแบบไฟล์ Excel
                </p>
                <ul class="text-xs text-blue-800 space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="font-bold">•</span>
                        <span><strong>คอลัมน์ A:</strong> name (ชื่อ-นามสกุล) - บังคับ</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold">•</span>
                        <span><strong>คอลัมน์ B:</strong> email (อีเมล) - บังคับ</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold">•</span>
                        <span><strong>คอลัมน์ C:</strong> student_id (รหัสนักศึกษา) - บังคับ</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold">•</span>
                        <span>แถวแรกเป็นหัวตาราง (Header)</span>
                    </li>
                </ul>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition flex items-center justify-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    อัพโหลด
                </button>
                <button type="button" onclick="closeParticipantsModal()"
                    class="flex-1 px-4 py-3 bg-gray-400 text-white font-semibold rounded-xl hover:bg-gray-500 transition shadow-lg">
                    ยกเลิก
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
function openParticipantsModal(activityId) {
    const uploadUrl = "{{ route('participants.upload', ':id') }}".replace(':id', activityId);
    document.getElementById('participants-form').action = uploadUrl;
    document.getElementById('participants-modal').classList.remove('hidden');
}

function closeParticipantsModal() {
    document.getElementById('participants-modal').classList.add('hidden');
    document.getElementById('excel-file-upload').value = '';
    document.getElementById('file-name-upload').textContent = '';
}

// Show filename when file is selected
document.getElementById('excel-file-upload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('file-name-upload').textContent = `📄 ${file.name}`;
    }
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('participants-modal');
    if (event.target === modal) {
        closeParticipantsModal();
    }
});

// Close alerts after 3 seconds
setTimeout(() => {
    let alertSuccess = document.getElementById('alert-success');
    let alertError = document.getElementById('alert-error');
    if (alertSuccess) {
        alertSuccess.style.transition = 'opacity 0.5s';
        alertSuccess.style.opacity = '0';
        setTimeout(() => alertSuccess.remove(), 500);
    }
    if (alertError) {
        alertError.style.transition = 'opacity 0.5s';
        alertError.style.opacity = '0';
        setTimeout(() => alertError.remove(), 500);
    }
}, 3000);
</script>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}
</style>

@endsection