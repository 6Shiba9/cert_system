<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาใบประกาศ - Certificate System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Navbar with Search -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <!-- Logo & Title -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white rounded-lg p-2 shadow-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">Certificate System</h1>
                            <p class="text-xs text-blue-100">ระบบจัดการใบประกาศ</p>
                        </div>
                    </div>
                </div>

                <!-- Search Box in Navbar -->
                <div class="flex-1 max-w-2xl">
                    <div class="relative">
                        <input type="text" 
                               id="navbarSearchInput"
                               placeholder="🔍 ค้นหากิจกรรมหรือหน่วยงาน..." 
                               class="w-full px-5 py-3 pr-12 rounded-xl focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 transition bg-white bg-opacity-20 backdrop-blur-sm text-white placeholder-blue-100 border border-white border-opacity-20">
                        <button id="clearSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-white hover:text-blue-200 transition hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Access Code Button -->
                <a href="{{ route('certificate.form') }}" 
                   class="bg-white bg-opacity-10 backdrop-blur-sm hover:bg-white hover:text-blue-600 text-white font-semibold px-4 py-3 rounded-xl border border-white border-opacity-20 transition-all duration-300 shadow-lg whitespace-nowrap flex items-center gap-2 justify-center lg:justify-start">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    ค้นหาด้วยรหัส
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Results Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">กิจกรรม/หลักสูตรอบรม</h2>
                        <p id="resultCount" class="text-gray-600 mt-1">
                            มีกิจกรรมทั้งหมด <span class="font-semibold">{{ $activities->count() }} กิจกรรม</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Cards Grid -->
        <div id="activitiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activities as $activity)
            <div class="activity-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-200 transform hover:-translate-y-2"
                 data-activity-name="{{ strtolower($activity->activity_name) }}"
                 data-agency-name="{{ strtolower($activity->agency->agency_name ?? '') }}"
                 data-access-code="{{ strtolower($activity->access_code) }}">
                
                <!-- Certificate Image -->
                <div class="relative h-64 bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center overflow-hidden">
                    @if($activity->certificate_img)
                        <img src="{{ asset('storage/' . $activity->certificate_img) }}" 
                             alt="{{ $activity->activity_name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="text-center">
                            <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-xl">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Watermark -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                        <p class="text-6xl font-bold text-blue-600 transform -rotate-45">ใบประกาศ</p>
                    </div>

                    <!-- Status Badge -->
                    @if($activity->is_active)
                    <div class="absolute top-3 right-3">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            เปิดใช้งาน
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 line-clamp-2 min-h-[3.5rem]">
                        {{ $activity->activity_name }}
                    </h3>

                    <div class="space-y-3 mb-5">
                        <!-- Agency -->
                        <div class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-gray-700">หน่วยงาน: <strong>{{ $activity->agency->agency_name ?? '-' }}</strong></span>
                        </div>

                        <!-- Date Range -->
                        <div class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-700">
                                {{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }}
                                @if($activity->end_date && $activity->start_date != $activity->end_date)
                                    - {{ \Carbon\Carbon::parse($activity->end_date)->format('d/m/Y') }}
                                @endif
                            </span>
                        </div>

                        <!-- Participants -->
                        <div class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-gray-700">ผู้เข้าร่วม: <strong>{{ $activity->participants->count() }} คน</strong></span>
                        </div>

                        <!-- Access Code -->
                        <div class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            <span class="text-gray-700">
                                รหัส: 
                                <span class="font-mono bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold">{{ $activity->access_code }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('certificate.select', $activity->access_code) }}" 
                       class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        ดาวน์โหลดใบประกาศ
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">ยังไม่มีกิจกรรม</h3>
                <p class="text-gray-500">ยังไม่มีกิจกรรมที่เปิดให้ดาวน์โหลดใบประกาศในขณะนี้</p>
            </div>
            @endforelse
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="hidden col-span-full text-center py-16">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-gray-600 mb-2">ไม่พบผลการค้นหา</h3>
            <p class="text-gray-500">ลองค้นหาด้วยคำค้นอื่นหรือติดต่อผู้จัดกิจกรรม</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="container mx-auto px-8 py-6">
            <div class="text-center text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} <strong>Certificate System</strong> - ระบบจัดการใบประกาศออนไลน์</p>
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
        const resultCount = document.getElementById('resultCount');
        const totalActivities = {{ $activities->count() }};

        // Real-time search
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            // Show/hide clear button
            if (searchTerm) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            // Filter cards
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

            // Update result count
            if (searchTerm) {
                resultCount.innerHTML = `พบ <span class="font-semibold">${visibleCount} กิจกรรม</span> จากคำค้นหา "<span class="font-semibold">${searchTerm}</span>"`;
            } else {
                resultCount.innerHTML = `มีกิจกรรมทั้งหมด <span class="font-semibold">${totalActivities} กิจกรรม</span>`;
            }

            // Show/hide no results message
            if (visibleCount === 0 && searchTerm !== '') {
                activitiesGrid.style.display = 'none';
                noResults.classList.remove('hidden');
            } else {
                activitiesGrid.style.display = 'grid';
                noResults.classList.add('hidden');
            }
        });

        // Clear search
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        });

        // Focus search on "/" key
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && document.activeElement !== searchInput) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    </script>
</body>
</html>