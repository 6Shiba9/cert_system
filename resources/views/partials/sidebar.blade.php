<!-- Sidebar Navigation -->
<aside class="bg-white shadow-lg w-64 p-4 space-y-4">
    <nav class="space-y-2">
        <a href="{{ url('/manager') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 hover:text-blue-600 transition-colors duration-200">
            หน้าหลัก
        </a>
        <a href="{{ route('manage-activities') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 hover:text-blue-600 transition-colors duration-200">
            จัดการกิจกรรม
        </a>
        <a href="{{ route('add-activity') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 hover:text-blue-600 transition-colors duration-200">
            เพิ่มกิจกรรม
        </a>
        <a href="{{ route('summary') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 hover:text-blue-600 transition-colors duration-200">
            สรุปข้อมูลระบบ
        </a>
        @if (Auth::user()->role == 'admin')
        <a href="{{ route('ManageUser') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 hover:text-blue-600 transition-colors duration-200">
            จัดการผู้ใช้งาน
        </a>
        @endif
        @if (Auth::user()->role == 'admin')
        <a href="{{ route('agency') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 hover:text-blue-600 transition-colors duration-200">
            จัดหน่วยงาน
        </a>
        @endif
    </nav>
</aside>
