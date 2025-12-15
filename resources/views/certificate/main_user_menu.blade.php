<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาใบประกาศ - Certificate System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Sarabun', sans-serif;
        }
        /* Fade-in ทุกอย่าง */
        .fade-in {
            animation: fadeIn 0.9s ease forwards;
            opacity: 0;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Slide up ใช้กับการ์ด */
        .slide-up {
            opacity: 0;
            animation: slideUp 0.8s ease forwards;
        }
        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(25px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Glow soft hover */
        .card-hover:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        }
        .card-hover {
            transition: 0.25s ease;
        }

        /* Image hover zoom */
        .img-zoom:hover {
            transform: scale(1.05);
        }
        .img-zoom {
            transition: 0.4s ease;
        }

    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-16 py-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6">
                <!-- Logo & Title -->
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-50 rounded-xl p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Certificate System</h1>
                        <p class="text-sm text-white font-light">ระบบจัดการใบประกาศออนไลน์</p>
                    </div>
                </div>

                <!-- Search Box -->
                <div class="flex-1 max-w-2xl">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" 
                               id="navbarSearchInput"
                               placeholder="ค้นหากิจกรรมหรือหน่วยงาน..." 
                               class="w-full pl-12 pr-12 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <button id="clearSearch" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Access Code Button -->
                <a href="{{ route('certificate.form') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors duration-200 whitespace-nowrap flex items-center gap-2 justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    ค้นหาด้วยรหัส
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-16 py-12 fade-in">
        <!-- Featured Latest Activity -->
        @if($activities->isNotEmpty())
        <div class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">กิจกรรมล่าสุด</h2>
                <p class="text-gray-500 font-light">กิจกรรมที่เพิ่งอัพโหลดล่าสุด</p>
            </div>
            
            @php
                $latestActivity = $activities->first();
            @endphp
            
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden max-w-5xl mx-auto hover:shadow-xl transition-shadow duration-300 slide-up card-hover">
                <div class="grid md:grid-cols-2 gap-0">
                    <!-- Left: Certificate Image -->
                    <div class="relative h-80 md:h-auto bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center">
                        @if($latestActivity->certificate_img)
                            <img src="{{ asset('storage/' . $latestActivity->certificate_img) }}" 
                                 alt="{{ $latestActivity->activity_name }}" 
                                 class="w-full h-full object-cover img-zoom">
                        @else
                            <div class="text-center">
                                <div class="bg-white rounded-3xl w-24 h-24 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif
                        
                        @if($latestActivity->is_active)
                        <div class="absolute top-4 right-4">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-500 text-white shadow-lg flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                เปิดใช้งาน
                            </span>
                        </div>
                        @endif
                        
                        <!-- New Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg animate-pulse">
                                🔥 ใหม่ล่าสุด
                            </span>
                        </div>
                    </div>
                    
                    <!-- Right: Activity Details -->
                    <div class="p-8 flex flex-col justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                                {{ $latestActivity->activity_name }}
                            </h3>

                            <div class="space-y-4 mb-8">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500">หน่วยงาน</p>
                                        <p class="text-base font-semibold text-gray-900">{{ $latestActivity->agency->agency_name ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500">วันที่จัดกิจกรรม</p>
                                        <p class="text-base font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($latestActivity->start_date)->format('d/m/Y') }}
                                            @if($latestActivity->end_date && $latestActivity->start_date != $latestActivity->end_date)
                                                - {{ \Carbon\Carbon::parse($latestActivity->end_date)->format('d/m/Y') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500">จำนวนผู้เข้าร่วม</p>
                                        <p class="text-base font-semibold text-gray-900">{{ $latestActivity->participants->count() }} คน</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500">รหัสเข้าถึง</p>
                                        <p class="font-mono text-lg font-bold bg-blue-50 text-blue-700 px-3 py-1 rounded-lg inline-block">{{ strtoupper($latestActivity->access_code) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('certificate.select', $latestActivity->access_code) }}" 
                           class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            ดาวน์โหลดใบประกาศ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- All Activities Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">กิจกรรมทั้งหมด</h3>
                <p id="resultCount" class="text-gray-500 font-light">
                    <span id="countNumber" class="font-semibold text-gray-900">{{ $activities->count() }}</span> กิจกรรม
                </p>
            </div>

            <!-- Certificate Cards Grid -->
            <div id="activitiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 fade-in">
                @forelse($activities as $activity)
                <div class="bg-white rounded-xl shadow hover:shadow-lg p-5 transition card-hover slide-up activity-card border border-gray-300"
                     data-activity-name="{{ strtolower($activity->activity_name) }}"
                     data-agency-name="{{ strtolower($activity->agency->agency_name ?? '') }}"
                     data-access-code="{{ strtolower($activity->access_code) }}">
                    
                    <div class="relative h-48 bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center">
                        @if($activity->certificate_img)
                            <img src="{{ asset('storage/' . $activity->certificate_img) }}" 
                                 alt="{{ $activity->activity_name }}" 
                                 class="w-full h-full object-cover img-zoom">
                        @else
                            <div class="text-center">
                                <div class="bg-white rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif
                        
                        @if($activity->is_active)
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">เปิดใช้งาน</span>
                        </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 line-clamp-2 min-h-[3.5rem]">
                            {{ $activity->activity_name }}
                        </h4>

                        <div class="space-y-3 mb-5 text-sm">
                            <div class="flex items-start text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 mr-2.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>{{ $activity->agency->agency_name ?? '-' }}</span>
                            </div>

                            <div class="flex items-start text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 mr-2.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>
                                    {{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }}
                                    @if($activity->end_date && $activity->start_date != $activity->end_date)
                                        - {{ \Carbon\Carbon::parse($activity->end_date)->format('d/m/Y') }}
                                    @endif
                                </span>
                            </div>

                            <div class="flex items-start text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 mr-2.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>{{ $activity->participants->count() }} คน</span>
                            </div>

                            <div class="flex items-start text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 mr-2.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                <span>รหัส: <span class="font-mono bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-semibold">{{ strtoupper($activity->access_code) }}</span></span>
                            </div>
                        </div>

                        <a href="{{ route('certificate.select', $activity->access_code) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            ดาวน์โหลดใบประกาศ
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">ยังไม่มีกิจกรรม</h3>
                    <p class="text-gray-500 font-light">ยังไม่มีกิจกรรมที่เปิดให้ดาวน์โหลดใบประกาศในขณะนี้</p>
                </div>
                @endforelse
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="hidden text-center py-16">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-600 mb-2">ไม่พบผลการค้นหา</h3>
                <p class="text-gray-500 font-light">ลองค้นหาด้วยคำค้นอื่นหรือติดต่อผู้จัดกิจกรรม</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-24">
        <div class="max-w-7xl mx-auto px-8 sm:px-12 lg:px-16 py-8">
            <div class="text-center text-sm text-gray-500 font-light">
                <p>&copy; {{ date('Y') }} <span class="font-semibold text-gray-900">Certificate System</span>. สงวนลิขสิทธิ์.</p>
            </div>
        </div>
    </footer>

    <!-- Real-time Search Script -->
    <script>
        const searchInput = document.getElementById('navbarSearchInput');
        const clearBtn = document.getElementById('clearSearch');
        const activityCards = document.querySelectorAll('.activity-card');
        const activitiesGrid = document.getElementById('activitiesGrid');
        const noResults = document.getElementById('noResults');
        const countNumber = document.getElementById('countNumber');
        const totalActivities = {{ $activities->count() }};

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            if (searchTerm) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            activityCards.forEach(card => {
                const activityName = card.getAttribute('data-activity-name');
                const agencyName = card.getAttribute('data-agency-name');
                const accessCode = card.getAttribute('data-access-code');

                if (activityName.includes(searchTerm) || 
                    agencyName.includes(searchTerm) || 
                    accessCode.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            countNumber.textContent = visibleCount;

            if (visibleCount === 0 && searchTerm !== '') {
                activitiesGrid.classList.add('hidden');
                noResults.classList.remove('hidden');
            } else {
                activitiesGrid.classList.remove('hidden');
                noResults.classList.add('hidden');
            }
        });

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && document.activeElement !== searchInput) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    </script>
</body>
</html>