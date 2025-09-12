@extends('partials.dashboard')

@section('title', 'เพิ่มกิจกรรม')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">เพิ่มกิจกรรมใหม่</h2>

    <form action="" method="POST" class="space-y-6">
        @csrf

        <!-- ชื่อกิจกรรม -->
        <div>
            <input type="hidden" name="user_id"  value="{{ Auth::user()->user_id }}">    
            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อกิจกรรม</label>
            <input type="text" name="activity_name"
                   class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-700"
                   required>
        </div>

        <!-- หน่วยงาน + สาขา -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">หน่วยงาน</label>
                <select name="agency_id"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="">-- เลือกหน่วยงาน --</option>
                    <option value="">คณะวิทยาศาสตร์</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">เลือกสาขา</label>
                <select name="branch_id"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="">-- เลือกสาขา --</option>
                    <option value="">วิทยาการคอมพิวเตอร์</option>
                </select>
            </div>
        </div>

        <!-- วันที่ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เริ่มต้น</label>
                <input type="date" name="start_date"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่สิ้นสุด</label>
                <input type="date" name="end_date"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required>
            </div>
        </div>

        <!-- ผู้สร้างกิจกรรม -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ผู้สร้างกิจกรรม</label>
            <input type="text" name="user_name"
                   class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-700"
                   value="{{ Auth::user()->name }}">
        </div>

        <!-- ปุ่ม -->
        <div class="flex justify-end space-x-4">
            <a href=""
               class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                ยกเลิก
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                บันทึกกิจกรรม
            </button>
        </div>
    </form>
</div>
<!-- test -->
@endsection