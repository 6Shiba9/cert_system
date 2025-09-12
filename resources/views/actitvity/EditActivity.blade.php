@extends('partials.dashboard')

@section('title', 'แก้ไขกิจกรรม')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">แก้ไขกิจกรรม</h2>

    <form action="{{ route('update-activity', $activity->activity_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- ชื่อกิจกรรม -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อกิจกรรม</label>
            <input type="text" name="activity_name" value="{{ old('activity_name', $activity->activity_name) }}"
                   class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-700"
                   required>
            @error('activity_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- หน่วยงาน + สาขา -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">หน่วยงาน</label>
                <select name="agency_id" id="agency_id"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="">-- เลือกหน่วยงาน --</option>
                    @foreach($agencies as $agency)
                        <option value="{{ $agency->agency_id }}" 
                                {{ old('agency_id', $activity->agency_id) == $agency->agency_id ? 'selected' : '' }}>
                            {{ $agency->agency_name }}
                        </option>
                    @endforeach
                </select>
                @error('agency_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">เลือกสาขา</label>
                <select name="branch_id" id="branch_id"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="">-- เลือกสาขา --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->branch_id }}" 
                                {{ old('branch_id', $activity->branch_id) == $branch->branch_id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- วันที่ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เริ่มต้น</label>
                <input type="date" name="start_date" value="{{ old('start_date', $activity->start_date) }}"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">วันที่สิ้นสุด</label>
                <input type="date" name="end_date" value="{{ old('end_date', $activity->end_date) }}"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required>
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- ใบประกาศปัจจุบัน -->
        @if($activity->certificate_img)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ใบประกาศปัจจุบัน</label>
            <div class="bg-gray-50 p-4 rounded-lg border">
                <div class="relative inline-block">
                    <img id="current_certificate_preview" 
                         src="{{ asset('storage/' . $activity->certificate_img) }}" 
                         alt="Current Certificate" 
                         class="max-w-full max-h-96 rounded-lg border shadow-lg cursor-crosshair">
                    <div id="current_name_position_marker" 
                         class="absolute w-4 h-4 bg-red-500 border-2 border-white rounded-full shadow-lg transform -translate-x-2 -translate-y-2 cursor-move"
                         style="left: {{ ($activity->position_x / 1000) * 100 }}%; top: {{ ($activity->position_y / 1000) * 100 }}%;">
                    </div>
                    <div id="current_preview_text" 
                         class="absolute text-red-600 font-bold text-lg pointer-events-none"
                         style="left: {{ ($activity->position_x / 1000) * 100 }}%; top: {{ ($activity->position_y / 1000) * 100 }}%; transform: translateX(-50%) translateY(-20px); text-shadow: 1px 1px 2px white;">
                        ตัวอย่างชื่อผู้เข้าร่วม
                    </div>
                </div>
                <div class="mt-3 text-sm text-gray-600">
                    <p>• คลิกบนรูปภาพเพื่อกำหนดตำแหน่งชื่อใหม่</p>
                    <p>• ลากจุดแดงเพื่อปรับตำแหน่ง</p>
                </div>
            </div>
        </div>
        @endif

        <!-- อัพโหลดใบประกาศใหม่ -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $activity->certificate_img ? 'เปลี่ยนรูปใบประกาศ' : 'รูปใบประกาศ' }} (PNG)
            </label>
            <input type="file" name="certificate_img" id="certificate_img" accept="image/png,image/jpg,image/jpeg"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
            <p class="text-sm text-gray-500 mt-1">รองรับไฟล์ PNG, JPG, JPEG ขนาดไม่เกิน 2MB</p>
            @error('certificate_img')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Certificate Preview (shown when new file is selected) -->
        <div id="new_certificate_preview_section" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-3">กำหนดตำแหน่งชื่อบนใบประกาศใหม่</label>
            <div class="bg-gray-50 p-4 rounded-lg border-2 border-dashed border-gray-300">
                <div class="relative inline-block">
                    <img id="new_certificate_preview" src="" alt="New Certificate Preview" 
                         class="max-w-full max-h-96 border rounded-lg shadow-lg cursor-crosshair">
                    <div id="new_name_position_marker" 
                         class="absolute w-4 h-4 bg-red-500 border-2 border-white rounded-full shadow-lg transform -translate-x-2 -translate-y-2 cursor-move hidden">
                    </div>
                    <div id="new_preview_text" 
                         class="absolute text-red-600 font-bold text-lg pointer-events-none hidden"
                         style="text-shadow: 1px 1px 2px white;">
                        ตัวอย่างชื่อผู้เข้าร่วม
                    </div>
                </div>
            </div>
        </div>

        <!-- ตำแหน่งชื่อบนใบประกาศ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง X (แนวนอน)</label>
                <input type="number" name="position_x" value="{{ old('position_x', $activity->position_x) }}" min="0" max="1000"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('position_x')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง Y (แนวตั้ง)</label>
                <input type="number" name="position_y" value="{{ old('position_y', $activity->position_y) }}" min="0" max="1000"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('position_y')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- สถานะการใช้งาน -->
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" 
                       {{ old('is_active', $activity->is_active) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">เปิดใช้งานกิจกรรม</span>
            </label>
        </div>

        <!-- รหัสเข้าถึง -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">รหัสเข้าถึง</label>
            <input type="text" value="{{ $activity->access_code }}" readonly
                   class="w-full h-10 px-4 rounded-lg border-gray-300 bg-gray-50 text-gray-700 font-mono">
            <p class="text-sm text-gray-500 mt-1">รหัสนี้ใช้สำหรับให้ผู้เข้าร่วมเข้าถึงใบประกาศ</p>
        </div>

        <!-- ปุ่ม -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('manage-activities') }}"
               class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                ยกเลิก
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                บันทึกการแก้ไข
            </button>
        </div>
    </form>
</div>

<script>
// Agency-Branch dropdown functionality
document.getElementById('agency_id').addEventListener('change', function() {
    const agencyId = this.value;
    const branchSelect = document.getElementById('branch_id');
    
    // Clear existing options except first one
    branchSelect.innerHTML = '<option value="">-- เลือกสาขา --</option>';
    
    if (agencyId) {
        fetch(`/api/branches/${agencyId}`)
            .then(response => response.json())
            .then(branches => {
                branches.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.branch_id;
                    option.textContent = branch.branch_name;
                    if (branch.branch_id == {{ $activity->branch_id }}) {
                        option.selected = true;
                    }
                    branchSelect.appendChild(option);
                });
            });
    }
});

// Load branches on page load if agency is selected
if (document.getElementById('agency_id').value) {
    document.getElementById('agency_id').dispatchEvent(new Event('change'));
}

// Current certificate position functionality
const currentPreview = document.getElementById('current_certificate_preview');
const currentMarker = document.getElementById('current_name_position_marker');
const currentPreviewText = document.getElementById('current_preview_text');

if (currentPreview) {
    // Position functionality for current certificate
    currentPreview.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const relativeX = Math.round((x / rect.width) * 1000);
        const relativeY = Math.round((y / rect.height) * 1000);
        
        updateCurrentPosition(x, y, relativeX, relativeY);
    });

    // Drag functionality for current certificate marker
    let isDraggingCurrent = false;
    let dragOffsetCurrent = { x: 0, y: 0 };

    currentMarker.addEventListener('mousedown', function(e) {
        isDraggingCurrent = true;
        const rect = this.getBoundingClientRect();
        dragOffsetCurrent.x = e.clientX - rect.left;
        dragOffsetCurrent.y = e.clientY - rect.top;
        
        document.addEventListener('mousemove', onDragCurrent);
        document.addEventListener('mouseup', stopDragCurrent);
        e.preventDefault();
    });

    function onDragCurrent(e) {
        if (!isDraggingCurrent) return;
        
        const previewRect = currentPreview.getBoundingClientRect();
        const x = e.clientX - previewRect.left - dragOffsetCurrent.x;
        const y = e.clientY - previewRect.top - dragOffsetCurrent.y;
        
        const constrainedX = Math.max(0, Math.min(x, previewRect.width - 16));
        const constrainedY = Math.max(0, Math.min(y, previewRect.height - 16));
        
        const relativeX = Math.round((constrainedX / previewRect.width) * 1000);
        const relativeY = Math.round((constrainedY / previewRect.height) * 1000);
        
        updateCurrentPosition(constrainedX, constrainedY, relativeX, relativeY);
    }

    function stopDragCurrent() {
        isDraggingCurrent = false;
        document.removeEventListener('mousemove', onDragCurrent);
        document.removeEventListener('mouseup', stopDragCurrent);
    }

    function updateCurrentPosition(x, y, relativeX, relativeY) {
        currentMarker.style.left = x + 'px';
        currentMarker.style.top = y + 'px';
        
        currentPreviewText.style.left = x + 'px';
        currentPreviewText.style.top = (y - 10) + 'px';
        currentPreviewText.style.transform = 'translateX(-50%)';
        
        document.getElementById('position_x').value = relativeX;
        document.getElementById('position_y').value = relativeY;
    }
}

// New certificate upload functionality
document.getElementById('certificate_img').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const newPreview = document.getElementById('new_certificate_preview');
            const newPreviewSection = document.getElementById('new_certificate_preview_section');
            
            newPreview.src = e.target.result;
            newPreviewSection.classList.remove('hidden');
            
            // Hide current certificate preview when new one is selected
            if (currentPreview) {
                currentPreview.parentElement.parentElement.style.opacity = '0.5';
            }
            
            resetNewPosition();
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('new_certificate_preview_section').classList.add('hidden');
        if (currentPreview) {
            currentPreview.parentElement.parentElement.style.opacity = '1';
        }
    }
});

// New certificate position functionality
document.getElementById('new_certificate_preview').addEventListener('click', function(e) {
    const rect = this.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    const relativeX = Math.round((x / rect.width) * 1000);
    const relativeY = Math.round((y / rect.height) * 1000);
    
    updateNewPosition(x, y, relativeX, relativeY);
});

// Drag functionality for new certificate
let isDraggingNew = false;
let dragOffsetNew = { x: 0, y: 0 };

document.getElementById('new_name_position_marker').addEventListener('mousedown', function(e) {
    isDraggingNew = true;
    const rect = this.getBoundingClientRect();
    dragOffsetNew.x = e.clientX - rect.left;
    dragOffsetNew.y = e.clientY - rect.top;
    
    document.addEventListener('mousemove', onDragNew);
    document.addEventListener('mouseup', stopDragNew);
    e.preventDefault();
});

function onDragNew(e) {
    if (!isDraggingNew) return;
    
    const preview = document.getElementById('new_certificate_preview');
    const previewRect = preview.getBoundingClientRect();
    
    const x = e.clientX - previewRect.left - dragOffsetNew.x;
    const y = e.clientY - previewRect.top - dragOffsetNew.y;
    
    const constrainedX = Math.max(0, Math.min(x, previewRect.width - 16));
    const constrainedY = Math.max(0, Math.min(y, previewRect.height - 16));
    
    const relativeX = Math.round((constrainedX / previewRect.width) * 1000);
    const relativeY = Math.round((constrainedY / previewRect.height) * 1000);
    
    updateNewPosition(constrainedX, constrainedY, relativeX, relativeY);
}

function stopDragNew() {
    isDraggingNew = false;
    document.removeEventListener('mousemove', onDragNew);
    document.removeEventListener('mouseup', stopDragNew);
}

function updateNewPosition(x, y, relativeX, relativeY) {
    const marker = document.getElementById('new_name_position_marker');
    const previewText = document.getElementById('new_preview_text');
    
    marker.style.left = x + 'px';
    marker.style.top = y + 'px';
    marker.classList.remove('hidden');
    
    previewText.style.left = x + 'px';
    previewText.style.top = (y - 10) + 'px';
    previewText.style.transform = 'translateX(-50%)';
    previewText.classList.remove('hidden');
    
    document.getElementById('position_x').value = relativeX;
    document.getElementById('position_y').value = relativeY;
}

function resetNewPosition() {
    const marker = document.getElementById('new_name_position_marker');
    const previewText = document.getElementById('new_preview_text');
    
    marker.classList.add('hidden');
    previewText.classList.add('hidden');
    
    // Reset to current activity position
    document.getElementById('position_x').value = {{ $activity->position_x }};
    document.getElementById('position_y').value = {{ $activity->position_y }};
}

// Add CSS styles
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1) translate(-50%, -50%); }
        50% { transform: scale(1.2) translate(-50%, -50%); }
        100% { transform: scale(1) translate(-50%, -50%); }
    }
    
    .position-marker {
        transform: translate(-50%, -50%);
    }
    
    .position-marker:hover {
        background-color: #dc2626;
        transform: translate(-50%, -50%) scale(1.1);
    }
    
    .certificate-preview:hover {
        filter: brightness(1.05);
    }
`;
document.head.appendChild(style);

// Apply classes
document.getElementById('current_name_position_marker').classList.add('position-marker');
document.getElementById('new_name_position_marker').classList.add('position-marker');
document.getElementById('current_certificate_preview').classList.add('certificate-preview');
document.getElementById('new_certificate_preview').classList.add('certificate-preview');
</script>

@endsection
