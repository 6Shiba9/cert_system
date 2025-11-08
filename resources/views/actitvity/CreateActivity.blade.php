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
                    <select name="agency_id" id="agency_id"
                        class="w-full h-10 px-4 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- เลือกหน่วยงาน --</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->agency_id }}" {{ old('agency_id') == $agency->agency_id ? 'selected' : '' }}>
                                {{ $agency->agency_name }}
                            </option>
                        @endforeach
                    </select>
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
                <input type="date" name="start_date" value="{{ old('start_date') }}"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่สิ้นสุด</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required>
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
const agencies = @json($agencies);
document.addEventListener("DOMContentLoaded", function () {
    const agencySelect = document.getElementById("agency_id");
    const branchSelect = document.getElementById("branch_id");

    agencySelect.addEventListener("change", function () {
        const selectedAgencyId = this.value;

        // เคลียร์ options เดิม
        branchSelect.innerHTML = '<option value="">-- เลือกสาขา --</option>';

        if (!selectedAgencyId) {
            branchSelect.disabled = true;
            branchSelect.removeAttribute("required");
            return;
        }

        const selectedAgency = agencies.find(a => a.agency_id == selectedAgencyId);

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
            // ถ้า agency ไม่มีสาขา
            branchSelect.disabled = true;
            branchSelect.removeAttribute("required");
        }
    });

    // เริ่มต้น disable ไว้ก่อน
    branchSelect.disabled = true;
});

</script>

@endsection