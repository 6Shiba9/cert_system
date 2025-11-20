<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าถึงใบประกาศ - Certificate System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">

    <div class="h-screen flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">เข้าถึงใบประกาศ</h1>
                <p class="text-gray-600">กรอกรหัสเข้าถึงและชื่อของคุณเพื่อดาวน์โหลดใบประกาศ</p>
            </div>
            
            @if(session('error'))
                <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-800 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-800 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            <form method="POST" action="{{ route('certificate.access') }}">
                @csrf

                <!-- Access Code Field -->
                <div class="mb-6">
                    <label for="access_code" class="block text-sm font-semibold text-gray-700 mb-2">
                        รหัสเข้าถึงกิจกรรม <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                        id="access_code" 
                        name="access_code" 
                        value="{{ old('access_code') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out uppercase font-mono" 
                        placeholder="เช่น ABC1234567"
                        required
                        autofocus>
                    @error('access_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name Field -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        ชื่อ-นามสกุล <span class="text-red-500">*</span>
                    </label>
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
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    เข้าถึงใบประกาศ
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    หากคุณไม่มีรหัสเข้าถึง กรุณาติดต่อผู้จัดกิจกรรม
                </p>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ url('/') }}" class="text-sm text-blue-600 hover:text-blue-800 transition duration-150 flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    กลับหน้าแรก
                </a>
            </div>
        </div>
    </div>

    <!-- Auto uppercase script only -->
    <script>
        // เปลี่ยน Access Code เป็นตัวพิมพ์ใหญ่อัตโนมัติ
        document.getElementById('access_code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    </script>
</body>
</html>