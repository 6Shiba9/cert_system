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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">หน่วยงาน</label>
                <select name="agency_id" id="agency_id"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">เลือกสาขา</label>
                <select name="branch_id" id="branch_id"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="">-- เลือกสาขา --</option>
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

        <!-- ใบประกาศ -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">รูปใบประกาศ (PNG)</label>
            <input type="file" name="certificate_img" id="certificate_img" accept="image/png,image/jpg,image/jpeg"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
            <p class="text-sm text-gray-500 mt-1">รองรับไฟล์ PNG, JPG, JPEG ขนาดไม่เกิน 2MB</p>
            @error('certificate_img')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Certificate Preview and Position Selector -->
        <div id="certificate_preview_section" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-3">กำหนดตำแหน่งชื่อบนใบประกาศ</label>
            <div class="bg-gray-50 p-4 rounded-lg border-2 border-dashed border-gray-300">
                <div class="relative inline-block">
                    <img id="certificate_preview" src="" alt="Certificate Preview" 
                         class="max-w-full max-h-96 border rounded-lg shadow-lg cursor-crosshair">
                    <div id="name_position_marker" 
                         class="absolute w-4 h-4 bg-red-500 border-2 border-white rounded-full shadow-lg transform -translate-x-2 -translate-y-2 cursor-move hidden">
                    </div>
                    <div id="preview_text" 
                         class="absolute text-red-600 font-bold text-lg pointer-events-none hidden"
                         style="text-shadow: 1px 1px 2px white;">
                        ตัวอย่างชื่อผู้เข้าร่วม
                    </div>
                </div>
                <div class="mt-3 text-sm text-gray-600">
                    <p>• คลิกบนรูปภาพเพื่อกำหนดตำแหน่งชื่อ</p>
                    <p>• ลากจุดแดงเพื่อปรับตำแหน่ง</p>
                    <p>• ข้อความสีแดงแสดงตัวอย่างการแสดงผล</p>
                </div>
            </div>
        </div>

        <!-- ตำแหน่งชื่อบนใบประกาศ (Hidden inputs) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง X (แนวนอน)</label>
                <input type="number" name="position_x" id="position_x" value="{{ old('position_x', 0) }}" min="0" max="1000"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       readonly>
                @error('position_x')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง Y (แนวตั้ง)</label>
                <input type="number" name="position_y" id="position_y" value="{{ old('position_y', 0) }}" min="0" max="1000"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       readonly>
                @error('position_y')
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
// Agency-Branch dropdown functionality
document.getElementById('agency_id').addEventListener('change', function() {
    const agencyId = this.value;
    const branchSelect = document.getElementById('branch_id');
    
    // Clear existing options
    branchSelect.innerHTML = '<option value="">-- เลือกสาขา --</option>';
    
    if (agencyId) {
        fetch(`/api/branches/${agencyId}`)
            .then(response => response.json())
            .then(branches => {
                branches.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.branch_id;
                    option.textContent = branch.branch_name;
                    branchSelect.appendChild(option);
                });
            });
    }
});

// Certificate image preview and position selection
document.getElementById('certificate_img').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('certificate_preview');
            const previewSection = document.getElementById('certificate_preview_section');
            
            preview.src = e.target.result;
            previewSection.classList.remove('hidden');
            
            // Reset position
            resetPosition();
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('certificate_preview_section').classList.add('hidden');
    }
});

// Certificate click handler for position setting
document.getElementById('certificate_preview').addEventListener('click', function(e) {
    const rect = this.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    // Convert to relative coordinates (0-1000)
    const relativeX = Math.round((x / rect.width) * 1000);
    const relativeY = Math.round((y / rect.height) * 1000);
    
    updatePosition(x, y, relativeX, relativeY);
});

// Drag functionality for position marker
let isDragging = false;
let dragOffset = { x: 0, y: 0 };

document.getElementById('name_position_marker').addEventListener('mousedown', function(e) {
    isDragging = true;
    const rect = this.getBoundingClientRect();
    dragOffset.x = e.clientX - rect.left;
    dragOffset.y = e.clientY - rect.top;
    
    document.addEventListener('mousemove', onDrag);
    document.addEventListener('mouseup', stopDrag);
    e.preventDefault();
});

function onDrag(e) {
    if (!isDragging) return;
    
    const preview = document.getElementById('certificate_preview');
    const previewRect = preview.getBoundingClientRect();
    
    const x = e.clientX - previewRect.left - dragOffset.x;
    const y = e.clientY - previewRect.top - dragOffset.y;
    
    // Constrain to image bounds
    const constrainedX = Math.max(0, Math.min(x, previewRect.width - 16));
    const constrainedY = Math.max(0, Math.min(y, previewRect.height - 16));
    
    // Convert to relative coordinates
    const relativeX = Math.round((constrainedX / previewRect.width) * 1000);
    const relativeY = Math.round((constrainedY / previewRect.height) * 1000);
    
    updatePosition(constrainedX, constrainedY, relativeX, relativeY);
}

function stopDrag() {
    isDragging = false;
    document.removeEventListener('mousemove', onDrag);
    document.removeEventListener('mouseup', stopDrag);
}

function updatePosition(x, y, relativeX, relativeY) {
    const marker = document.getElementById('name_position_marker');
    const previewText = document.getElementById('preview_text');
    const positionXInput = document.getElementById('position_x');
    const positionYInput = document.getElementById('position_y');
    
    // Update marker position
    marker.style.left = x + 'px';
    marker.style.top = y + 'px';
    marker.classList.remove('hidden');
    
    // Update preview text position (centered on marker)
    previewText.style.left = x + 'px';
    previewText.style.top = (y - 10) + 'px';
    previewText.style.transform = 'translateX(-50%)';
    previewText.classList.remove('hidden');
    
    // Update input values
    positionXInput.value = relativeX;
    positionYInput.value = relativeY;
    
    // Add visual feedback
    marker.style.animation = 'pulse 0.3s ease-in-out';
    setTimeout(() => {
        marker.style.animation = '';
    }, 300);
}

function resetPosition() {
    const marker = document.getElementById('name_position_marker');
    const previewText = document.getElementById('preview_text');
    
    marker.classList.add('hidden');
    previewText.classList.add('hidden');
    
    document.getElementById('position_x').value = 0;
    document.getElementById('position_y').value = 0;
}

// Add CSS animation for marker pulse
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1) translate(-50%, -50%); }
        50% { transform: scale(1.2) translate(-50%, -50%); }
        100% { transform: scale(1) translate(-50%, -50%); }
    }
    
    #name_position_marker {
        transform: translate(-50%, -50%);
    }
    
    #name_position_marker:hover {
        background-color: #dc2626;
        transform: translate(-50%, -50%) scale(1.1);
    }
    
    #certificate_preview:hover {
        filter: brightness(1.05);
    }
`;
document.head.appendChild(style);
</script>

@endsection