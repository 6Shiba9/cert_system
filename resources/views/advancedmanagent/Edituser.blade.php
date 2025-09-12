@extends('partials.dashboard')

@section('title', 'เเก้ไขผู้ใช้งาน')

@section('content')
        <h1 class="text-3xl font-bold text-gray-900">แก้ไขผู้ใช้งาน</h1>
        <div class="mt-3">
            <div class="mt-2 px-7 py-3">
                <form action="{{ route('updateuser', ['id' => $user->user_id]) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">ชื่อ</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">อีเมล</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">รหัสผ่าน</label>
                        <input type="text" value="{{ $user->password }}" name="password" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">บทบาท</label>
                        <select name="role"class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="admin"{{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager"{{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>
                    <!-- ปุ่มบันทึกและปิด -->
                    <div class="flex justify-center items-center gap-4 mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-1/2 shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            บันทึก
                        </button>
                        <a href="{{ route('ManageUser') }}" class="text-center px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-1/2 shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            กลับ
                        </a>
                    </div>
                </form> 
            </div>
        </div>

@endsection
