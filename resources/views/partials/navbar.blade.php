<nav class="bg-gray-200 shadow-md p-4">
    <div class="container mx-auto flex justify-between items-center ">
        <a href="{{ url('/manager') }}" class="text-xl font-bold text-gray-800">
            Certificate System
        </a>
        <div class="space-x-4">เข้าสู่ระบบโดย {{ Auth::user()->name }}</div>
            
        <div class="space-x-4">
            <a href="{{ route('logout') }}" class="text-gray-800 font-bold hover:underline hover:text-gray-600">logout</a>
        </div>
    </>
</nav>
