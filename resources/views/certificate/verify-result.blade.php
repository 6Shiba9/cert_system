<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบใบประกาศ - Certificate System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-2xl w-full">
            @if($valid)
            <!-- ✅ ใบประกาศถูกต้อง -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border-4 border-green-500">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-green-600 mb-2">✅ ใบประกาศถูกต้อง</h1>
                    <p class="text-gray-600">ใบประกาศนี้ออกโดยระบบอย่างถูกต้อง</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">ข้อมูลใบประกาศ</h2>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Certificate ID:</span>
                            <span class="font-mono font-bold text-blue-600">{{ $certificateId }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">ชื่อผู้รับ:</span>
                            <span class="font-semibold">{{ $participant->name }}</span>
                        </div>

                        @if($participant->student_id)
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">รหัสนักศึกษา:</span>
                            <span class="font-mono">{{ $participant->student_id }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">กิจกรรม:</span>
                            <span class="font-semibold">{{ $activity->activity_name }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">หน่วยงาน:</span>
                            <span>{{ $activity->agency->agency_name ?? '-' }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">วันที่จัดกิจกรรม:</span>
                            <span>{{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Digital Signature:</span>
                            <span class="font-mono text-xs">{{ $signature }}</span>
                        </div>

                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">จำนวนครั้งที่ดาวน์โหลด:</span>
                            <span class="font-semibold">{{ $downloadCount }} ครั้ง</span>
                        </div>

                        @if($firstDownload)
                        <div class="flex justify-between py-2 border-t">
                            <span class="text-gray-600">ดาวน์โหลดครั้งแรก:</span>
                            <span class="text-sm">{{ $firstDownload->downloaded_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif

                        @if($lastDownload && $downloadCount > 1)
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">ดาวน์โหลดล่าสุด:</span>
                            <span class="text-sm">{{ $lastDownload->downloaded_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('certificate.pdf', $participant->certificate_token) }}" 
                       target="_blank"
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition text-center">
                        ดูใบประกาศ
                    </a>
                    <a href="{{ route('user.dashboard') }}" 
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition text-center">
                        กลับหน้าหลัก
                    </a>
                </div>
            </div>

            @else
            <!-- ❌ ใบประกาศไม่ถูกต้อง -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border-4 border-red-500">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-red-600 mb-2">❌ ใบประกาศไม่ถูกต้อง</h1>
                    <p class="text-gray-600 mb-4">{{ $message }}</p>
                    <p class="text-sm text-gray-500">โปรดติดต่อผู้จัดกิจกรรมหากคุณเชื่อว่านี่คือข้อผิดพลาด</p>
                </div>

                <a href="{{ route('user.dashboard') }}" 
                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition text-center">
                    กลับหน้าหลัก
                </a>
            </div>
            @endif
        </div>
    </div>
</body>
</html>