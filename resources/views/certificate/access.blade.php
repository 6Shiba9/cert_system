<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าถึงใบประกาศ - Certificate System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">

<div class="h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">เข้าถึงใบประกาศ</h1>
            <p class="text-gray-600">กรอกรหัสเข้าถึงและชื่อของคุณเพื่อดาวน์โหลดใบประกาศ</p>
        </div>
        
        @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('certificate.access') }}">
            @csrf

            <!-- Access Code Field -->
            <div class="mb-6">
                <label for="access_code" class="block text-sm font-semibold text-gray-700 mb-2">รหัสเข้าถึงกิจกรรม</label>
                <input type="text" 
                       id="access_code" 
                       name="access_code" 
                       value="{{ old('access_code') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out uppercase font-mono" 
                       placeholder="เช่น ABC1234567"
                       required>
                @error('access_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name Field -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">ชื่อ-นามสกุล</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out" 
                       placeholder="กรอกชื่อ-นามสกุลของคุณ"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out">
                เข้าถึงใบประกาศ
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">
                หากคุณไม่มีรหัสเข้าถึง กรุณาติดต่อผู้จัดกิจกรรม
            </p>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ url('/') }}" class="text-sm text-blue-600 hover:text-blue-800 transition duration-150">
                กลับหน้าแรก
            </a>
        </div>
    </div>
</div>

<script>
// Auto uppercase access code input
document.getElementById('access_code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>

</body>
</html>
