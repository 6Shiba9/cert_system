<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลการค้นหา - {{ $searchName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">

<div class="max-w-5xl mx-auto px-4 py-12">

    <!-- Header -->
    <div class="bg-white rounded-3xl shadow-xl p-8 mb-10 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-green-500/10 to-blue-500/10"></div>

        <div class="relative">
            <div class="mx-auto w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                พบข้อมูลของคุณเรียบร้อย 🎉
            </h1>

            <p class="text-gray-600">
                ชื่อผู้เข้าร่วม: <span class="font-semibold text-gray-800">{{ $searchName }}</span>
            </p>

            <p class="mt-2 text-lg">
                พบทั้งหมด
                <span class="font-bold text-green-600">
                    {{ $participants->count() }}
                </span>
                กิจกรรม
            </p>
        </div>
    </div>

    <!-- Activities -->
    <div class="space-y-6">

        @foreach($participants as $participant)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 p-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <!-- Info -->
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        {{ $participant->activity->activity_name }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-600">

                        <div class="flex items-center gap-2">
                            🏢
                            <span>{{ $participant->activity->agency->agency_name ?? '-' }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            📅
                            <span>
                                {{ \Carbon\Carbon::parse($participant->activity->start_date)->format('d/m/Y') }}
                            </span>
                        </div>

                        @if($participant->student_id)
                        <div class="flex items-center gap-2 sm:col-span-2">
                            🆔
                            <span class="font-mono font-semibold text-gray-800">
                                {{ $participant->student_id }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action -->
                <div class="shrink-0">
                    @if($participant->student_id)
                        <a href="{{ route('certificate.verify.form', $participant->certificate_token) }}"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition">
                            ✔ ยืนยันรหัส
                        </a>
                    @else
                        <a href="{{ route('certificate.pdf', $participant->certificate_token) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition">
                            📄 ดูใบประกาศ
                        </a>
                    @endif
                </div>

            </div>
        </div>
        @endforeach

    </div>

    <!-- Back -->
    <div class="text-center mt-12">
        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold">
            ← กลับหน้าหลัก
        </a>
    </div>

</div>

</body>
</html>
