@extends('partials.dashboard')

@section('title', 'จัดการกิจกรรม')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">จัดการกิจกรรม</h1>
        <a href="{{ route('add-activity') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out">
            + เพิ่มกิจกรรม
        </a>
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

    @if($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-800">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-200 text-left text-gray-600 uppercase text-sm font-semibold">
                    <th class="px-5 py-3 border-b-2 border-gray-200">ชื่อกิจกรรม</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">หน่วยงาน</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">สาขา</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">วันที่</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">รหัสเข้าถึง</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">จำนวนผู้เข้าร่วม</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">รายงาน</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">ผู้เข้าร่วม</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">สถานะ</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr class="hover:bg-gray-100 transition-colors duration-150">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <div class="flex items-center">
                                @if($activity->certificate_img)
                                    <img class="w-10 h-10 rounded-full mr-3" src="{{ asset('storage/' . $activity->certificate_img) }}" alt="Certificate">
                                @endif
                                <div>
                                    <p class="text-gray-900 whitespace-no-wrap font-semibold">{{ $activity->activity_name }}</p>
                                    <p class="text-gray-600 whitespace-no-wrap text-xs">{{ $activity->user->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $activity->agency->agency_name }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $activity->branch->branch_name ?? '-' }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ \Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($activity->end_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $activity->access_code }}</span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $activity->participants->count() }} คน
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <a href="{{ route('activity.certificates', $activity->activity_id) }}" 
                               class="text-blue-600 hover:text-blue-900 text-xs">
                                รายชื่อผู้ได้รับใบประกาศ
                            </a>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <div class="flex items-center space-x-2">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                  
                                </span>
                                <button onclick="openParticipantsModal({{ $activity->activity_id }})" 
                                        class="text-blue-600 hover:text-blue-900 text-xs">
                                    อัพโหลด Excel
                                </button>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $activity->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $activity->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('edit-activity', $activity->activity_id) }}" 
                                   class="text-blue-600 hover:text-blue-900 text-xs">แก้ไข</a>
                                <form action="{{ route('delete-activity', $activity->activity_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs" 
                                            onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบกิจกรรมนี้?')">ลบ</button>
                                </form>
                                <a href="{{ route('add-certificate', $activity->activity_id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-xs">เพิ่มใบประกาศ
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Upload Participants Modal -->
<div id="participants-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">อัพโหลดรายชื่อผู้เข้าร่วม</h3>
            <form id="participants-form" 
                method="POST" 
                enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ไฟล์ Excel (.xlsx, .xls, .csv)</label>
                    <input type="file" name="participants_file" accept=".xlsx,.xls,.csv" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <p class="text-xs text-gray-500 mt-1">
                        คอลัมน์ที่ต้องมี: name (ชื่อ), email (อีเมล), student_id (รหัสนักศึกษา)
                    </p>
                </div>
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="closeParticipantsModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">ยกเลิก</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">อัพโหลด</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openParticipantsModal(activityId) {
    // ✅ ใช้ Laravel route helper
    const uploadUrl = "{{ route('upload-participants', ':id') }}".replace(':id', activityId);
    document.getElementById('participants-form').action = uploadUrl;
    document.getElementById('participants-modal').classList.remove('hidden');
}

function closeParticipantsModal() {
    document.getElementById('participants-modal').classList.add('hidden');
}
</script>

@endsection
