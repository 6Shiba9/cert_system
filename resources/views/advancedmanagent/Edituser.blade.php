@extends('partials.dashboard')

@section('title', 'แก้ไขผู้ใช้งาน')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">แก้ไขผู้ใช้งาน</h1>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('updateuser', ['id' => $user->user_id]) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- ชื่อ -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    ชื่อ <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- อีเมล -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    อีเมล <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- รหัสผ่าน (ไม่บังคับ) -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    รหัสผ่านใหม่ <span class="text-gray-500 text-xs font-normal">(เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</span>
                </label>
                <input type="password" 
                       name="password" 
                       placeholder="••••••••" 
                       class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">
                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    กรอกเฉพาะเมื่อต้องการเปลี่ยนรหัสผ่านเท่านั้น (ขั้นต่ำ 6 ตัวอักษร)
                </p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- หน่วยงาน -->
            <div class="mb-4">
                <label for="agency_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    หน่วยงาน <span class="text-red-500">*</span>
                </label>
                <select name="agency_id" 
                        class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                        required>
                    <option value="">-- เลือกหน่วยงาน --</option>
                    @foreach($agencies as $agency)
                        <option value="{{ $agency->agency_id }}" 
                                {{ old('agency_id', $user->agency_id) == $agency->agency_id ? 'selected' : '' }}>
                            {{ $agency->agency_name }}
                        </option>
                    @endforeach
                </select>
                @error('agency_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- บทบาท -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                    บทบาท <span class="text-red-500">*</span>
                </label>
                <select name="role" 
                        class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                        required>
                    <option value="">-- เลือกบทบาท --</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                    <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>
                        Manager
                    </option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ปุ่มบันทึกและกลับ -->
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    💾 บันทึก
                </button>
                <a href="{{ route('ManageUser') }}" 
                   class="flex-1 text-center px-6 py-3 bg-gray-500 text-white font-medium rounded-lg shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 transition">
                    ← กลับ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection