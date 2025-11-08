@extends('partials.dashboard')

@section('title', 'เพิ่มใบประกาศ')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">เพิ่มใบประกาศ - {{ $activity->activity_name }}</h2>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-gray-200">
    <!-- ฟอร์มอัปโหลดใบประกาศ -->
    <form action="{{ route('activity.storeCertificate') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-8">
        @csrf
        
        <!-- Hidden Activity ID -->
        <input type="hidden" name="activity_id" value="{{ $activity->activity_id }}">

        <!-- รูปใบประกาศ -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                รูปใบประกาศ <span class="text-red-500">*</span>
            </label>
            <input type="file" 
                   name="certificate_img" 
                   id="certificate_img" 
                   accept="image/png,image/jpg,image/jpeg"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                   required>
            <p class="text-sm text-gray-500 mt-1">รองรับไฟล์ PNG, JPG, JPEG ขนาดไม่เกิน 2MB</p>
            @error('certificate_img')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Certificate Preview and Position Selector -->
        <div id="certificate_preview_section" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-3">
                กำหนดตำแหน่งชื่อบนใบประกาศ <span class="text-red-500">*</span>
            </label>
            <div class="bg-gray-50 p-4 rounded-lg border-2 border-dashed border-gray-300">
                <div class="relative inline-block">
                    <img id="certificate_preview" 
                         src="" 
                         alt="Certificate Preview" 
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

        <!-- ตำแหน่งชื่อบนใบประกาศ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    ตำแหน่ง X (แนวนอน) <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="position_x" 
                       id="position_x" 
                       value="{{ old('position_x', $activity->position_x ?? 0) }}" 
                       min="0" 
                       max="1000"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required>
                <p class="text-xs text-gray-500 mt-1">สามารถแก้ไขค่าได้โดยตรง (0-1000)</p>
                @error('position_x')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    ตำแหน่ง Y (แนวตั้ง) <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="position_y" 
                       id="position_y" 
                       value="{{ old('position_y', $activity->position_y ?? 0) }}" 
                       min="0" 
                       max="1000"
                       class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       required>
                <p class="text-xs text-gray-500 mt-1">สามารถแก้ไขค่าได้โดยตรง (0-1000)</p>
                @error('position_y')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- ปุ่มบันทึกและยกเลิก -->
        <div class="flex gap-4 pt-4">
            <button type="submit" 
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                💾 บันทึกใบประกาศ
            </button>
            <a href="{{ route('manage-activities') }}" 
               class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 shadow-md hover:shadow-lg text-center">
                ← กลับ
            </a>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>คำแนะนำ:</strong> ตำแหน่ง X และ Y จะถูกคำนวณอัตโนมัติเมื่อคุณคลิกบนรูป 
                        หรือคุณสามารถใส่ตัวเลขเองได้โดยตรง (แนะนำ: X=500, Y=400 สำหรับตำแหน่งกลางๆ)
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
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
            
            // Update preview based on current input values
            updatePreviewFromInputs();
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('certificate_preview_section').classList.add('hidden');
    }
});

// Update preview when inputs change manually
document.getElementById('position_x').addEventListener('input', updatePreviewFromInputs);
document.getElementById('position_y').addEventListener('input', updatePreviewFromInputs);

function updatePreviewFromInputs() {
    const positionX = parseInt(document.getElementById('position_x').value) || 0;
    const positionY = parseInt(document.getElementById('position_y').value) || 0;
    const preview = document.getElementById('certificate_preview');
    const rect = preview.getBoundingClientRect();
    
    if (rect.width > 0 && rect.height > 0) {
        const x = (positionX / 1000) * rect.width;
        const y = (positionY / 1000) * rect.height;
        
        updateMarkerPosition(x, y);
    }
}

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
    updateMarkerPosition(x, y);
    
    // Update input values
    document.getElementById('position_x').value = relativeX;
    document.getElementById('position_y').value = relativeY;
}

function updateMarkerPosition(x, y) {
    const marker = document.getElementById('name_position_marker');
    const previewText = document.getElementById('preview_text');
    
    // Update marker position
    marker.style.left = x + 'px';
    marker.style.top = y + 'px';
    marker.classList.remove('hidden');
    
    // Update preview text position (centered on marker)
    previewText.style.left = x + 'px';
    previewText.style.top = (y - 10) + 'px';
    previewText.style.transform = 'translateX(-50%)';
    previewText.classList.remove('hidden');
    
    // Add visual feedback
    marker.style.animation = 'pulse 0.3s ease-in-out';
    setTimeout(() => {
        marker.style.animation = '';
    }, 300);
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