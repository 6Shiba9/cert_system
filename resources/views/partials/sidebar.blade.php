<!-- Modern Sidebar Navigation -->
<aside class="bg-gradient-to-b from-gray-50 to-white shadow-2xl w-72 p-6 space-y-6 border-r border-gray-200">
    <!-- Sidebar Header -->
    <div class="pb-4 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            เมนูหลัก
        </h2>
    </div>

    <!-- Navigation Links -->
    <nav class="space-y-2">
        <!-- หน้าหลัก -->
        <a href="{{ route('admin.dashboard') }}" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : '' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-semibold">หน้าหลัก</span>
        </a>

        <!-- จัดการกิจกรรม -->
        <a href="{{ route('manage-activities') }}" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-purple-500 hover:to-purple-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg {{ request()->routeIs('manage-activities') ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg' : '' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <span class="font-semibold">จัดการกิจกรรม</span>
        </a>

        <!-- เพิ่มกิจกรรม -->
        <a href="{{ route('add-activity') }}" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-green-500 hover:to-green-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg {{ request()->routeIs('add-activity') ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : '' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="font-semibold">เพิ่มกิจกรรม</span>
        </a>


        @if (Auth::user()->role == 'admin')

        <!-- สรุปข้อมูลระบบ -->
        <a href="{{ route('summary') }}" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-orange-500 hover:to-orange-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg {{ request()->routeIs('summary') ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg' : '' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="font-semibold">สรุปข้อมูลระบบ</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <div class="border-t border-gray-200 mb-2"></div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider px-4">ผู้ดูแลระบบ</p>
        </div>

        <!-- จัดการผู้ใช้งาน -->
        <a href="{{ route('ManageUser') }}" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-indigo-500 hover:to-indigo-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg {{ request()->routeIs('ManageUser') ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg' : '' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="font-semibold">จัดการผู้ใช้งาน</span>
        </a>

        <!-- จัดการหน่วยงาน -->
        <a href="{{ route('agency') }}" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gradient-to-r hover:from-pink-500 hover:to-pink-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg {{ request()->routeIs('agency') ? 'bg-gradient-to-r from-pink-500 to-pink-600 text-white shadow-lg' : '' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span class="font-semibold">จัดการหน่วยงาน</span>
        </a>
        @endif
    </nav>

    <!-- Sidebar Footer -->
    <div class="pt-6 mt-auto border-t border-gray-200">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 rounded-full p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800">ต้องการความช่วยเหลือ?</p>
                    <a href="#" class="text-xs text-blue-600 hover:underline">ติดต่อฝ่ายสนับสนุน</a>
                </div>
            </div>
        </div>
    </div>
</aside>