@extends('partials.dashboard')

@section('title', 'จัดการหน่วยงาน')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">จัดการหน่วยงาน</h1>
        <div class="flex gap-3">
            <button id="add-agency-btn"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition duration-200 ease-in-out">
                + เพิ่มหน่วยงาน
            </button>
            <button id="add-branch-btn"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition duration-200 ease-in-out">
                + เพิ่มสาขา
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div id="alert-success" class="mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-800 shadow-sm">
        <p class="font-semibold">สำเร็จ!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 uppercase text-sm font-semibold text-center">
                    <th class="px-6 py-3 border-b">ID</th>
                    <th class="px-6 py-3 border-b">หน่วยงาน (คณะ)</th>
                    <th class="px-6 py-3 border-b">สาขา</th>
                    <th class="px-6 py-3 border-b">การจัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($agency as $agencies)
                <tr class="hover:bg-gray-100 transition">
                    <td class="px-6 py-4 text-sm text-gray-600 text-center">{{ $agencies->agency_id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $agencies->agency_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        @forelse ($agencies->branches as $branch)
                        <span onclick="openBranchEditModal(this)"
                            data-branch-id="{{ $branch->branch_id }}"
                            data-branch-name="{{ $branch->branch_name }}"
                            data-agency-id="{{ $agencies->agency_id }}"
                            class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded-lg text-xs font-medium mr-1 mb-1 cursor-pointer hover:bg-blue-200">
                            {{ $branch->branch_name }}
                        </span>
                        @empty
                        <span class="text-gray-400 italic">- ไม่มีสาขา -</span>
                        @endforelse
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <button onclick="openEditModal(this)"
                            data-id="{{ $agencies->agency_id }}"
                            data-agency="{{ $agencies->agency_name }}"
                            class="text-blue-600 hover:text-blue-900 font-medium mr-2">
                            แก้ไขหน่วยงาน
                        </button>
                        <form action="{{ route('deleteagency', ['id' => $agencies->agency_id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-red-600 hover:text-red-900 font-medium"
                                onclick="return confirm('ข้อมูลทั้งหมดจะถูกลบ คุณแน่ใจหรือไม่?')">
                                ลบ
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Agency Modal -->
<div id="add-edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 relative">
        <h3 id="modal-title" class="text-xl font-bold text-center text-gray-800 mb-6">เพิ่มหน่วยงาน</h3>
        <form id="agency-form" action="{{ route('createagency') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="agency_id" id="agency-id">

            <div>
                <label for="agency_name" class="block text-sm font-semibold text-gray-700 mb-1">หน่วยงาน</label>
                <input type="text" name="agency_name" id="agency_name" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
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

<!-- Add/Edit Branch Modal -->
<div id="add-editbranch-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 relative">
        <h3 id="modalbranch-title" class="text-xl font-bold text-center text-gray-800 mb-6">เพิ่มสาขา</h3>
        <form id="branch-form" action="" method="POST" class="space-y-4"> 
            @csrf 
            <input type="hidden" name="_method" id="formbranch-method" value="POST"> 
            <input type="hidden" name="branch_id" id="branch-id"> 
            <!-- Dropdown เลือกคณะ/หน่วยงาน -->
            <div> 
                <label for="agency_id" class="block text-sm font-semibold text-gray-700 mb-1">เลือกคณะ/หน่วยงาน</label> 
            <select name="agency_id" id="agency_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    <option value="">-- กรุณาเลือก --</option> 
                    @foreach($agency as $agencies) 
                    <option value="{{ $agencies->agency_id }}">{{ $agencies->agency_name }}</option> 
                    @endforeach
                </select>
                <div> 
                    <label for="branch" class="block text-sm font-semibold text-gray-700 mb-1">สาขา</label> 
                    <input type="text" name="branch_name" id="branch" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required> 
                </div>
            </div>
            <div class="flex gap-3 mt-6"> 
                <!-- ปุ่ม -->
                <button type="submit" class="w-1/2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"> บันทึก </button>
                <button type="button" id="deletebranch-btn" href="#" class="w-1/2 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition hidden">ลบหน่วยงาน</ื>
                <button type="button" id="close-modalbranch-btn" class="w-1/2 px-4 py-2 bg-gray-400 text-white font-semibold rounded-lg hover:bg-gray-500 transition"> ปิด </button> 
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
    const addAgencyBtn = document.getElementById('add-agency-btn');
    const modal = document.getElementById('add-edit-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const form = document.getElementById('agency-form');
    const modalTitle = document.getElementById('modal-title');
    const formMethod = document.getElementById('form-method');
    const agencyIdInput = document.getElementById('agency-id');
    const agencyNameInput = document.getElementById('agency_name');

    addAgencyBtn.addEventListener('click', () => {
        modalTitle.innerText = 'เพิ่มหน่วยงาน';
        form.action = "{{ route('createagency') }}";
        formMethod.value = 'POST';
        agencyIdInput.value = '';
        agencyNameInput.value = '';
        modal.classList.remove('hidden');
    });

    function openEditModal(button) {
        modalTitle.innerText = 'แก้ไขหน่วยงาน';
        const id = button.getAttribute('data-id');
        const agency_name = button.getAttribute('data-agency');


        form.action = "{{ route('updateagency', ['id' => ':id']) }}".replace(':id', id);
        formMethod.value = 'PUT';
        agencyIdInput.value =  id;
        agencyNameInput.value = agency_name;

        modal.classList.remove('hidden');
    }

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // close the alert after 3 seconds
    setTimeout(() => {
        let alertBox = document.getElementById('alert-success');
        if (alertBox) {
            setTimeout(() => alertBox.remove(), 500); // ลบออกจาก DOM
        }
    }, 3000);

    // branch

    document.addEventListener("DOMContentLoaded", function() {
        const branchModal = document.getElementById('add-editbranch-modal');
        const branchForm = document.getElementById('branch-form');
        const branchModalTitle = document.getElementById('modalbranch-title');
        const branchFormMethod = document.getElementById('formbranch-method');
        const branchIdInput = document.getElementById('branch-id');
        const agencySelect = document.getElementById('agency_id');
        const branchNameInput = document.getElementById('branch');
        const deletebranchBtn = document.getElementById('deletebranch-btn');

        // เปิด modal สำหรับ "เพิ่มสาขา"
        document.getElementById('add-branch-btn').addEventListener('click', () => {
            branchModalTitle.innerText = 'เพิ่มสาขา';
            branchForm.action = "{{ route('createbranch') }}"; // route สำหรับเพิ่ม
            branchFormMethod.value = 'POST';
            branchIdInput.value = '';
            agencySelect.value = '';
            branchNameInput.value = '';
            branchModal.classList.remove('hidden');
            deletebranchBtn.classList.add('hidden');
        });

        // เปิด modal สำหรับ "แก้ไขสาขา" (กดชื่อสาขา)
        window.openBranchEditModal = function(el) {
            const branchId = el.getAttribute('data-branch-id');
            const branchName = el.getAttribute('data-branch-name');
            const agencyId = el.getAttribute('data-agency-id');

            branchModalTitle.innerText = 'แก้ไขสาขา';
            branchForm.action = "{{ route('updatebranch', ['id' => ':id']) }}".replace(':id', branchId); // route สำหรับแก้ไข
            branchFormMethod.value = 'PUT';
            branchIdInput.value = branchId;
            agencySelect.value = agencyId;
            branchNameInput.value = branchName;

            branchModal.classList.remove('hidden');
            deletebranchBtn.classList.remove('hidden');
        };

        //กดปุ่มลบสาขา
        deletebranchBtn.onclick = function() {
            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสาขานี้?')) {
                branchForm.action = "{{ route('deletebranch', ['id' => ':id']) }}".replace(':id', branchIdInput.value);
                branchFormMethod.value = 'DELETE';
                branchForm.submit();
            }
        }

        // ปิด modal
        document.getElementById('close-modalbranch-btn').addEventListener('click', () => {
            branchModal.classList.add('hidden');
        });

        window.addEventListener('click', (event) => {
            if (event.target === branchModal) {
                branchModal.classList.add('hidden');
            }
        });
    });
</script>

@endsection