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

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-200 text-left text-gray-600 uppercase text-sm font-semibold">
                    <th class="px-5 py-3 border-b-2 border-gray-200">ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">ชื่อผู้ใช้งาน</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">อีเมล</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">บทบาท</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-100 transition-colors duration-150">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $user->user_id }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $user->name }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $user->email }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $user->role }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <a href="{{ route('edituser', ['id' => $user->user_id]) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">แก้ไข</a>
                            <span class="text-gray-400">|</span>
                            <form action="{{ route('deleteuser', ['id' => $user->user_id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้งานนี้?')">ลบ</button>
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
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">ชื่อ</label>
                        <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">อีเมล</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">รหัสผ่าน</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">บทบาท</label>
                        <select name="role" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                        </select>
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
    </div>
</div>

<script>
    const addUserBtn = document.getElementById('add-user-btn');
    const modal = document.getElementById('add-user-modal', 'edit-user-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');

    addUserBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Close the modal when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
</script>
@endsection
