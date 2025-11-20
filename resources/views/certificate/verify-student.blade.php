<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันตัวตน - Certificate System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">ยืนยันตัวตน</h1>
            <p class="text-gray-600">กรุณายืนยันตัวตนก่อนดาวน์โหลดใบประกาศ</p>
        </div>

        <!-- Participant Info -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-6 border border-blue-200">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 rounded-full p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">ผู้เข้าร่วม</p>
                    <p class="text-lg font-bold text-gray-800">{{ $participant->name }}</p>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-blue-200">
                <p class="text-sm text-gray-600">กิจกรรม</p>
                <p class="text-sm font-semibold text-gray-800">{{ $activity->activity_name }}</p>
            </div>
        </div>

        <!-- Error Message -->
        @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-100 border border-red-400 text-red-800">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Verification Form -->
        <form method="POST" action="{{ route('certificate.verify', $participant->certificate_token) }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="student_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    รหัสนักศึกษา <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="student_id" 
                       name="student_id" 
                       value="{{ old('student_id') }}"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition font-mono text-lg" 
                       placeholder="กรอกรหัสนักศึกษาของคุณ"
                       required
                       autofocus>
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    กรุณากรอกรหัสนักศึกษาให้ตรงกับที่ลงทะเบียนไว้
                </p>
            </div>

            <!-- Attempts Warning -->
            @if(session('attempts'))
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <div class="flex items-center gap-2 text-yellow-800">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">พยายามแล้ว {{ session('attempts') }} ครั้ง</span>
                </div>
            </div>
            @endif

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                ยืนยันตัวตนและดาวน์โหลด
            </button>

            <!-- Back Button -->
            <a href="{{ route('certificate.select', $activity->access_code) }}" 
               class="block text-center text-gray-600 hover:text-gray-800 text-sm font-medium transition">
                ← กลับไปเลือกชื่ออื่น
            </a>
        </form>

        <!-- Help Text -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-600 mb-2 font-semibold">💡 ไม่มีรหัสนักศึกษา?</p>
                <p class="text-xs text-gray-600">กรุณาติดต่อผู้จัดกิจกรรมหรือใช้ช่องทางอื่นในการรับใบประกาศ</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="fixed bottom-4 left-0 right-0 text-center">
        <p class="text-xs text-gray-500">
            Certificate System - ระบบยืนยันตัวตนเพื่อความปลอดภัย
        </p>
    </div>
</body>
</html>