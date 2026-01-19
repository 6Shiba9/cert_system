@extends('partials.dashboard')

@section('title', 'เพิ่มกิจกรรม')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">เพิ่มกิจกรรมใหม่</h2>

    <form action="{{ route('save-activity') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- ชื่อกิจกรรม -->
        <div>
            <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">    
            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อกิจกรรม</label>
            <input type="text" name="activity_name" value="{{ old('activity_name') }}"
                   class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-700"
                   required>
            @error('activity_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- หน่วยงาน + สาขา -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">หน่วยงาน & สาขา</label>
            <div class="flex gap-4">
                <!-- หน่วยงาน -->
                <div class="w-1/2">
                    @if($user->role === 'admin')
                        {{-- Admin เลือกหน่วยงานได้ --}}
                        <select name="agency_id" id="agency_id"
                            class="w-full h-10 px-4 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 required">
                            <option value="">-- เลือกหน่วยงาน --</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->agency_id }}" {{ old('agency_id') == $agency->agency_id ? 'selected' : '' }}>
                                    {{ $agency->agency_name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        {{-- Manager/User ล็อกหน่วยงานของตัวเอง --}}
                        <input type="text" 
                               value="{{ $user->agency->agency_name }}" 
                               class="w-full h-10 px-4 rounded-lg border border-gray-300 bg-gray-100 text-gray-700" 
                               readonly>
                        <input type="hidden" name="agency_id" value="{{ $user->agency_id }}">
                        
                        {{-- แสดง badge "ล็อกแล้ว" --}}
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            ล็อกตามหน่วยงานของคุณ
                        </p>
                    @endif
                    @error('agency_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- สาขา -->
                <div class="w-1/2">
                    <select name="branch_id" id="branch_id"
                        class="w-full h-10 px-4 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- เลือกสาขา --</option>
                    </select>
                    @error('branch_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- วันที่ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เริ่มต้น</label>
                <input type="date" 
                    name="start_date" 
                    id="start_date"
                    min="{{ date('Y-m-d') }}"
                    value="{{ old('start_date') }}"
                    class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                <p class="text-xs text-gray-500 mt-1">ไม่สามารถเลือกวันที่ย้อนหลังได้</p>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่สิ้นสุด</label>
                <input type="date" 
                    name="end_date" 
                    id="end_date"
                    min="{{ date('Y-m-d') }}"
                    value="{{ old('end_date') }}"
                    class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                <p class="text-xs text-gray-500 mt-1">ต้องเท่ากับหรือหลังวันที่เริ่มต้น</p>
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- ผู้สร้างกิจกรรม -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ผู้สร้างกิจกรรม</label>
            <input type="text" name="user_name" readonly
                   class="w-full h-10 px-4 rounded-lg border-gray-300 bg-gray-50 text-gray-700"
                   value="{{ Auth::user()->name }}">
        </div>

        <!-- ปุ่ม -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('manage-activities') }}"
               class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                ยกเลิก
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                บันทึกกิจกรรม
            </button>
        </div>
    </form>
</div>

<script>
// เมื่อเลือกวันที่เริ่มต้น ให้ปรับ min ของวันที่สิ้นสุด
document.getElementById('start_date').addEventListener('change', function() {
    const endDateInput = document.getElementById('end_date');
    endDateInput.min = this.value;
    
    if (endDateInput.value && endDateInput.value < this.value) {
        endDateInput.value = this.value;
    }
});    

const agencies = @json($agencies);
const userRole = "{{ $user->role }}";
const userAgencyId = "{{ $user->agency_id }}";

document.addEventListener("DOMContentLoaded", function () {
    const agencySelect = document.getElementById("agency_id");
    const branchSelect = document.getElementById("branch_id");

    // ✅ ถ้าไม่ใช่ admin ให้โหลดสาขาของหน่วยงานนั้นทันที
    if (userRole !== 'admin' && userAgencyId) {
        loadBranches(userAgencyId);
    }

    // Event listener สำหรับ admin
    if (agencySelect) {
        agencySelect.addEventListener("change", function () {
            const selectedAgencyId = this.value;
            loadBranches(selectedAgencyId);
        });
    }

    function loadBranches(agencyId) {
        branchSelect.innerHTML = '<option value="">-- เลือกสาขา --</option>';

        if (!agencyId) {
            branchSelect.disabled = true;
            branchSelect.removeAttribute("required");
            return;
        }

        const selectedAgency = agencies.find(a => a.agency_id == agencyId);

        if (selectedAgency && selectedAgency.branches.length > 0) {
            branchSelect.disabled = false;
            branchSelect.setAttribute("required", "required");

            selectedAgency.branches.forEach(branch => {
                let opt = document.createElement("option");
                opt.value = branch.branch_id;
                opt.textContent = branch.branch_name;
                branchSelect.appendChild(opt);
            });
        } else {
            branchSelect.disabled = true;
            branchSelect.removeAttribute("required");
        }
    }

    // เริ่มต้น disable ไว้ก่อน (เฉพาะ admin)
    if (userRole === 'admin') {
        branchSelect.disabled = true;
    }
});
</script>

@endsection