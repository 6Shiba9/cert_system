@extends('partials.dashboard')

@section('title', 'รายชื่อผู้เข้าร่วมกิจกรรม')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">รายชื่อผู้เข้าร่วมกิจกรรม</h1>
            <p class="text-gray-600 mt-1">{{ $activity->activity_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('certificate.preview.page', $activity->activity_id) }}" 
            class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition duration-200 ease-in-out flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                ดูตัวอย่างใบประกาศ
            </a>
            <button id="upload-excel-btn"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition duration-200 ease-in-out flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                อัพโหลด Excel
            </button>
            <button id="add-participant-btn"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition duration-200 ease-in-out flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                เพิ่มรายชื่อเดียว
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div id="alert-success" class="mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-800 shadow-sm">
        <p class="font-semibold">สำเร็จ!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div id="alert-error" class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-800 shadow-sm">
        <p class="font-semibold">ข้อผิดพลาด!</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">ผู้เข้าร่วมทั้งหมด</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $activity->participants->count() }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">ดาวน์โหลดทั้งหมด</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $activity->participants->sum(function($p) { return $p->downloadLogs->count(); }) }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">รหัสเข้าถึง</p>
                    <h3 class="text-2xl font-bold mt-2 font-mono">{{ $activity->access_code }}</h3>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-xs font-semibold text-center">
                        <th class="px-4 py-3 border-b">#</th>
                        <th class="px-4 py-3 border-b">ชื่อ-นามสกุล</th>
                        <th class="px-4 py-3 border-b">รหัสนักศึกษา</th>
                        <th class="px-4 py-3 border-b">อีเมล</th>
                        <th class="px-4 py-3 border-b">ดาวน์โหลด</th>
                        <th class="px-4 py-3 border-b">ลิงก์ใบประกาศ</th>
                        <th class="px-4 py-3 border-b">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($activity->participants as $index => $participant)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $participant->name }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            @if($participant->student_id)
                                <span class="font-mono bg-gray-100 px-2 py-1 rounded text-gray-800">{{ $participant->student_id }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $participant->email ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $participant->downloadLogs->count() }} ครั้ง
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-1 justify-center flex-wrap">
                                <a href="{{ route('certificate.pdf', $participant->certificate_token) }}" 
                                   target="_blank"
                                   class="bg-blue-600 text-white px-2 py-1 rounded-lg text-xs hover:bg-blue-700 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    ดู
                                </a>
                                <a href="{{ route('certificate.download', $participant->certificate_token) }}" 
                                   class="bg-green-600 text-white px-2 py-1 rounded-lg text-xs hover:bg-green-700 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    DL
                                </a>
                                <button onclick="copyLink('{{ route('certificate.pdf', $participant->certificate_token) }}')"
                                        class="bg-gray-600 text-white px-2 py-1 rounded-lg text-xs hover:bg-gray-700 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    Copy
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <button onclick="openEditModal(this)"
                                data-id="{{ $participant->participant_id }}"
                                data-name="{{ $participant->name }}"
                                data-student-id="{{ $participant->student_id }}"
                                data-email="{{ $participant->email }}"
                                class="text-blue-600 hover:text-blue-900 font-medium mr-2">
                                แก้ไข
                            </button>
                            <form action="{{ route('participant.delete', $participant->participant_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-900 font-medium"
                                    onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้เข้าร่วมคนนี้?')">
                                    ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-lg font-semibold mb-2">ยังไม่มีผู้เข้าร่วม</p>
                            <p class="text-sm">เริ่มต้นโดยการเพิ่มรายชื่อเดียวหรืออัพโหลดไฟล์ Excel</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Participant Modal -->
<div id="add-edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 relative">
        <h3 id="modal-title" class="text-xl font-bold text-center text-gray-800 mb-6">เพิ่มผู้เข้าร่วม</h3>
            <form id="participant-form" action="{{ route('participant.add', $activity->activity_id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="participant_id" id="participant-id">

                <div>
                    <label for="participant_name" class="block text-sm font-semibold text-gray-700 mb-1">
                        ชื่อ-นามสกุล <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="participant_name" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                        placeholder="กรอกชื่อ-นามสกุล"
                        value="{{ old('name') }}"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="participant_student_id" class="block text-sm font-semibold text-gray-700 mb-1">
                        รหัสนักศึกษา <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="student_id" id="participant_student_id" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="เช่น 6512345"
                        value="{{ old('student_id') }}"
                        required>
                    @error('student_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="participant_email" class="block text-sm font-semibold text-gray-700 mb-1">
                        อีเมล
                    </label>
                    <input type="email" name="email" id="participant_email" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="example@email.com"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="w-1/2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        บันทึก
                    </button>
                    <button type="button" id="close-modal-btn"
                        class="w-1/2 px-4 py-2 bg-gray-400 text-white font-semibold rounded-lg hover:bg-gray-500 transition">
                        ปิด
                    </button>
                </div>
            </form>
    </div>
</div>

<!-- Upload Excel Modal -->
<div id="upload-excel-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 relative">
        <h3 class="text-xl font-bold text-center text-gray-800 mb-6">อัพโหลดรายชื่อจาก Excel</h3>
        <form action="{{ route('participants.upload', $activity->activity_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">เลือกไฟล์ Excel</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition">
                    <input type="file" name="participants_file" id="excel-file" 
                        accept=".xlsx,.xls,.csv"
                        class="hidden"
                        required>
                    <label for="excel-file" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm text-gray-600">คลิกเพื่อเลือกไฟล์</p>
                        <p class="text-xs text-gray-500 mt-1">รองรับ .xlsx, .xls, .csv</p>
                    </label>
                </div>
                <p id="file-name" class="text-sm text-gray-600 mt-2 text-center"></p>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-lg p-4">
                <p class="text-xs font-bold text-blue-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    📋 รูปแบบไฟล์ Excel
                </p>
                <ul class="text-xs text-blue-800 space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="font-bold">•</span>
                        <span><strong>คอลัมน์ A:</strong> name (ชื่อ-นามสกุล) - บังคับ</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold">•</span>
                        <span><strong>คอลัมน์ B:</strong> email (อีเมล) - บังคับ</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold">•</span>
                        <span><strong>คอลัมน์ C:</strong> student_id (รหัสนักศึกษา) - บังคับ</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold">•</span>
                        <span>แถวแรกเป็นหัวตาราง (Header)</span>
                    </li>
                </ul>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="w-1/2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    อัพโหลด
                </button>
                <button type="button" id="close-upload-modal-btn"
                    class="w-1/2 px-4 py-2 bg-gray-400 text-white font-semibold rounded-lg hover:bg-gray-500 transition">
                    ปิด
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    function copyLink(url) {
        navigator.clipboard.writeText(url).then(function() {
            alert('✅ คัดลอกลิงก์เรียบร้อยแล้ว!\n' + url);
        }).catch(function(err) {
            alert('❌ เกิดข้อผิดพลาดในการคัดลอก');
        });
    }

    // Modal Elements
    const addParticipantBtn = document.getElementById('add-participant-btn');
    const modal = document.getElementById('add-edit-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const form = document.getElementById('participant-form');
    const modalTitle = document.getElementById('modal-title');
    const formMethod = document.getElementById('form-method');
    const participantIdInput = document.getElementById('participant-id');
    const participantNameInput = document.getElementById('participant_name');
    const participantStudentIdInput = document.getElementById('participant_student_id');
    const participantEmailInput = document.getElementById('participant_email');

    // Open Add Modal
    addParticipantBtn.addEventListener('click', () => {
        modalTitle.innerText = 'เพิ่มผู้เข้าร่วม';
        form.action = "{{ route('participant.add', $activity->activity_id) }}";
        formMethod.value = 'POST';
        participantIdInput.value = '';
        participantNameInput.value = '';
        participantStudentIdInput.value = '';
        participantEmailInput.value = '';
        modal.classList.remove('hidden');
    });

    // Open Edit Modal
    function openEditModal(button) {
        modalTitle.innerText = 'แก้ไขผู้เข้าร่วม';
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const studentId = button.getAttribute('data-student-id');
        const email = button.getAttribute('data-email');

        form.action = "{{ route('participant.update', ['id' => ':id']) }}".replace(':id', id);
        formMethod.value = 'PUT';
        participantIdInput.value = id;
        participantNameInput.value = name;
        participantStudentIdInput.value = studentId || '';
        participantEmailInput.value = email || '';

        modal.classList.remove('hidden');
    }

    // Close Modal
    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Upload Excel Modal
    const uploadExcelBtn = document.getElementById('upload-excel-btn');
    const uploadModal = document.getElementById('upload-excel-modal');
    const closeUploadModalBtn = document.getElementById('close-upload-modal-btn');
    const excelFileInput = document.getElementById('excel-file');
    const fileNameDisplay = document.getElementById('file-name');

    uploadExcelBtn.addEventListener('click', () => {
        uploadModal.classList.remove('hidden');
    });

    closeUploadModalBtn.addEventListener('click', () => {
        uploadModal.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === uploadModal) {
            uploadModal.classList.add('hidden');
        }
    });

    excelFileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            fileNameDisplay.textContent = `📄 ${file.name}`;
        }
    });

    // Auto-hide alerts
    setTimeout(() => {
        ['alert-success', 'alert-error'].forEach(id => {
            let alert = document.getElementById(id);
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 3000);
        // เปิด modal อัตโนมัติถ้ามี validation error
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('add-edit-modal').classList.remove('hidden');
        });
    @endif
</script>

@endsection