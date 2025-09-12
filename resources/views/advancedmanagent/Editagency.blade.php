@extends('partials.dashboard')

@section('title', 'เเก้ไขหน่วยงาน')

@section('content')

<h1 class="text-xl leading-6 font-medium text-center text-gray-900">เเก้ไขหน่วยงาน</h1>
        <div class="mt-3">
            <div class="mt-2 px-7 py-3">
                <form action="{{ route('createagency') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="faculty" class="block text-sm font-semibold text-gray-700 mb-2">หน่วยงาน</label>
                        <input type="text" name="faculty" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="branch" class="block text-sm font-semibold text-gray-700 mb-2">สาขา</label>
                        <input type="text" name="branch" class="w-full px-4 py-2 border rounded-lg" >
                    </div>
                    <!-- ปุ่มบันทึกและปิด -->
                    <div class="flex justify-center items-center gap-4 mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-1/2 shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            บันทึก
                        </button>
                        <button type="button" id="close-modal-btn" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-1/2 shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            ปิด
                        </button>
                    </div>
                </form>
            </div>
        </div>

@endsection