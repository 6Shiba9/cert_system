<nav class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo & Brand -->
            <a href="{{ url('/dashboard') }}" class="flex items-center space-x-3 group">
                <div class="bg-white rounded-lg p-2 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">Certificate System</h1>
                    <p class="text-xs text-blue-100">ระบบจัดการใบประกาศ</p>
                </div>
            </a>

            <!-- User Info & Logout -->
            <div class="flex items-center space-x-4">
                <!-- User Profile -->
                <div class="flex items-center space-x-3 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl px-4 py-2 border border-white border-opacity-20">
                    <div class="flex items-center space-x-2">
                        <div class="bg-white rounded-full p-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-blue-100">
                                @if(Auth::user()->role == 'admin')
                                    👑 ผู้ดูแลระบบ
                                @else
                                    👤 ผู้ใช้งาน
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <a href="{{ route('logout') }}" 
                   class="flex items-center space-x-2 bg-white bg-opacity-10 backdrop-blur-sm hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-xl border border-white border-opacity-20 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>ออกจากระบบ</span>
                </a>
            </div>
        </div>
    </div>
</nav>