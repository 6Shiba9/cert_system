@extends('partials.dashboard')

@section('title', 'ดูใบประกาศนียบัตร - ' . $activity->activity_name)

@section('content')
<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">ใบประกาศนียบัตร</h1>
            <p class="text-gray-600">{{ $activity->activity_name }}</p>
            <p class="text-sm text-gray-500">{{ $activity->agency->agency_name }} - {{ $activity->branch->branch_name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('manage-activities') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                ← กลับ
            </a>
            @if(count($certificates) > 0)
            <a href="{{ route('download-all-certificates', $activity->activity_id) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                📥 ดาวน์โหลดทั้งหมด (ZIP)
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $activity->participants->count() }}</div>
            <div class="text-gray-600">ผู้เข้าร่วมทั้งหมด</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-green-600">{{ count($certificates) }}</div>
            <div class="text-gray-600">ใบประกาศที่สร้างแล้ว</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-orange-600">{{ $activity->participants->count() - count($certificates) }}</div>
            <div class="text-gray-600">ยังไม่ได้สร้างใบประกาศ</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-purple-600">{{ number_format((count($certificates) / max($activity->participants->count(), 1)) * 100, 1) }}%</div>
            <div class="text-gray-600">เสร็จสิ้น</div>
        </div>
    </div>

    @if(count($certificates) > 0)
    <!-- Certificates Grid -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold">ใบประกาศนียบัตรที่สร้างแล้ว</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
            @foreach($certificates as $cert)
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-200">
                <!-- Certificate Preview -->
                <div class="aspect-w-4 aspect-h-3 bg-gray-50">
                    <img src="{{ $cert['file_url'] }}" 
                         alt="Certificate for {{ $cert['participant']->name }}"
                         class="w-full h-48 object-contain cursor-pointer"
                         onclick="openPreview('{{ $cert['file_url'] }}', '{{ $cert['participant']->name }}')">
                </div>
                
                <!-- Certificate Info -->
                <div class="p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">{{ $cert['participant']->name }}</h4>
                    <div class="text-sm text-gray-600 space-y-1">
                        <div>📧 {{ $cert['participant']->email ?? 'ไม่ระบุ' }}</div>
                        <div>🆔 {{ $cert['participant']->student_id ?? 'ไม่ระบุ' }}</div>
                        <div>📊 {{ $cert['file_size'] }}</div>
                        <div>📅 {{ $cert['created_at'] }}</div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('download-certificate', $cert['participant']->certificate_token) }}" 
                           target="_blank"
                           class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded text-sm hover:bg-blue-700 transition duration-200">
                            📥 ดาวน์โหลด
                        </a>
                        <button onclick="openPreview('{{ $cert['file_url'] }}', '{{ $cert['participant']->name }}')"
                                class="flex-1 bg-gray-600 text-white py-2 px-3 rounded text-sm hover:bg-gray-700 transition duration-200">
                            👁️ ดูเต็ม
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- No Certificates Found -->
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <div class="text-6xl mb-4">📜</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">ยังไม่มีใบประกาศ</h3>
        <p class="text-gray-600 mb-6">กรุณาสร้างใบประกาศสำหรับกิจกรรมนี้ก่อน</p>
        <a href="{{ route('generate-certificates', $activity->activity_id) }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 inline-block">
            🏆 สร้างใบประกาศ
        </a>
    </div>
    @endif
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-auto">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 id="previewTitle" class="text-lg font-semibold">ตัวอย่างใบประกาศ</h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 text-center">
                <img id="previewImage" src="" alt="Certificate Preview" class="max-w-full h-auto mx-auto">
            </div>
        </div>
    </div>
</div>

<script>
function openPreview(imageUrl, participantName) {
    document.getElementById('previewImage').src = imageUrl;
    document.getElementById('previewTitle').textContent = `ใบประกาศ - ${participantName}`;
    document.getElementById('previewModal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreview();
    }
});

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});
</script>
@endsection
