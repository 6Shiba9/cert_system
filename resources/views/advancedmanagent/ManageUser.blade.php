@extends('partials.dashboard')

@section('title', 'จัดการผู้ใช้งาน')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">จัดการผู้ใช้งาน</h1>
        <button id="add-user-btn" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out">
            + เพิ่มผู้ใช้งาน
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-200 text-left text-gray-600 uppercase text-sm font-semibold">
                    <th class="px-5 py-3 border-b-2 border-gray-200">ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">ชื่อผู้ใช้งาน</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">อีเมล</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">หน่วยงาน</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">บทบาท</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">กิจกรรม</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-100 transition-colors duration-150">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $user->user_id }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $user->name }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $user->email }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            @if($user->agency)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $user->agency->agency_name }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            @if($user->activities_count > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    {{ $user->activities_count }} กิจกรรม
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <a href="{{ route('edituser', ['id' => $user->user_id]) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200">แก้ไข</a>
                            <span class="text-gray-400">|</span>
                            <form action="{{ route('deleteuser', ['id' => $user->user_id]) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirmDelete('{{ $user->name }}', {{ $user->activities_count }})">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div id="add-user-modal" class="fixed rounded-xl inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg leading-6 font-medium text-center text-gray-900">เพิ่มผู้ใช้งาน</h3>
        <div class="mt-3">
            <div class="mt-2 px-7 py-3">
                <form action="{{ route('createuser') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">ชื่อ <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">อีเมล <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">รหัสผ่าน <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">ขั้นต่ำ 6 ตัวอักษร</p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="agency_id" class="block text-sm font-semibold text-gray-700 mb-2">หน่วยงาน <span class="text-red-500">*</span></label>
                        <select name="agency_id" class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- เลือกหน่วยงาน --</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->agency_id }}" {{ old('agency_id') == $agency->agency_id ? 'selected' : '' }}>
                                    {{ $agency->agency_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('agency_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">บทบาท <span class="text-red-500">*</span></label>
                        <select name="role" class="w-full px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- เลือกบทบาท --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
    </div>
</div>

<script>
    const addUserBtn = document.getElementById('add-user-btn');
    const modal = document.getElementById('add-user-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');

    addUserBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    /**
     * ✅ ฟังก์ชันยืนยันการลบพร้อมแสดงจำนวนกิจกรรม
     */
    function confirmDelete(userName, activitiesCount) {
        let message = `คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้งาน "${userName}"?\n\n`;
        
        if (activitiesCount > 0) {
            message += `⚠️ ผู้ใช้งานนี้มี ${activitiesCount} กิจกรรม\n`;
            message += `กิจกรรมทั้งหมดและผู้เข้าร่วมจะถูกลบด้วย!\n\n`;
            message += `การกระทำนี้ไม่สามารถย้อนกลับได้`;
        } else {
            message += `การกระทำนี้ไม่สามารถย้อนกลับได้`;
        }
        
        return confirm(message);
    }
</script>
@endsection