    @extends('partials.dashboard')

    @section('title', 'แก้ไขกิจกรรม')

    @section('content')

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">แก้ไขกิจกรรม</h2>

        <form action="{{ route('update-activity', $activity->activity_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- รหัสเข้าถึง -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสเข้าถึง</label>
                <input type="text" value="{{ $activity->access_code }}" readonly
                    class="w-full h-10 px-4 rounded-lg border-gray-300 bg-gray-50 text-gray-700 font-mono">
                <p class="text-sm text-gray-500 mt-1">รหัสนี้ใช้สำหรับให้ผู้เข้าร่วมเข้าถึงใบประกาศ</p>
            </div>

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
                            class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                    @php
                        $minStartDate = now()->gt($activity->start_date) ? $activity->start_date->format('Y-m-d') : date('Y-m-d');
                    @endphp
                    <input type="date" 
                        name="start_date" 
                        id="start_date"
                        min="{{ $minStartDate }}"
                        value="{{ old('start_date', $activity->start_date ? $activity->start_date->format('Y-m-d') : '') }}"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <p class="text-xs text-gray-500 mt-1">
                        @if(now()->gt($activity->start_date))
                            กิจกรรมนี้เริ่มไปแล้ว - สามารถแก้ไขได้
                        @else
                            ไม่สามารถเลือกวันที่ย้อนหลังได้
                        @endif
                    </p>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่สิ้นสุด</label>
                    <input type="date" 
                        name="end_date" 
                        id="end_date"
                        min="{{ old('start_date', $activity->start_date ? $activity->start_date->format('Y-m-d') : date('Y-m-d')) }}"
                        value="{{ old('end_date', $activity->end_date ? $activity->end_date->format('Y-m-d') : '') }}"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <p class="text-xs text-gray-500 mt-1">ต้องเท่ากับหรือหลังวันที่เริ่มต้น</p>
                    @error('end_date')
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

            <!-- ใบประกาศปัจจุบัน -->
            @if($activity->certificate_img)
            <div id="current_certificate_section">
                <label class="block text-sm font-medium text-gray-700 mb-1">ใบประกาศปัจจุบัน</label>
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <div class="relative inline-block">
                        <img id="current_certificate_preview" 
                            src="{{ asset('storage/' . $activity->certificate_img) }}" 
                            alt="Current Certificate" 
                            class="max-w-full max-h-96 rounded-lg border shadow-lg cursor-crosshair">
                        <div id="current_name_position_marker" 
                            class="absolute w-4 h-4 bg-red-500 border-2 border-white rounded-full shadow-lg cursor-move"
                            style="left: {{ ($activity->position_x / 1000) * 100 }}%; top: {{ ($activity->position_y / 1000) * 100 }}%; transform: translate(-50%, -50%);">
                        </div>
                        <div id="current_preview_text" 
                            class="absolute text-red-600 font-bold text-lg pointer-events-none"
                            style="left: {{ ($activity->position_x / 1000) * 100 }}%; top: calc({{ ($activity->position_y / 1000) * 100 }}% - 20px); transform: translateX(-50%); text-shadow: 1px 1px 2px white;">
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
                    {{ $activity->certificate_img ? 'เปลี่ยนรูปใบประกาศ' : 'รูปใบประกาศ' }} (PNG, JPG, JPEG)
                </label>
                <input type="file" name="certificate_img" id="certificate_img" accept="image/png,image/jpg,image/jpeg"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-sm text-gray-500 mt-1">รองรับไฟล์ PNG, JPG, JPEG ขนาดไม่เกิน 2MB (เลือกถ้าต้องการเปลี่ยนเท่านั้น)</p>
                @error('certificate_img')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Certificate Preview -->
            <div id="new_certificate_preview_section" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-3">กำหนดตำแหน่งชื่อบนใบประกาศใหม่</label>
                <div class="bg-blue-50 p-4 rounded-lg border-2 border-blue-300">
                    <div class="relative inline-block">
                        <img id="new_certificate_preview" src="" alt="New Certificate Preview" 
                            class="max-w-full max-h-96 border rounded-lg shadow-lg cursor-crosshair">
                        <div id="new_name_position_marker" 
                            class="absolute w-4 h-4 bg-red-500 border-2 border-white rounded-full shadow-lg cursor-move hidden">
                        </div>
                        <div id="new_preview_text" 
                            class="absolute text-red-600 font-bold text-lg pointer-events-none hidden"
                            style="text-shadow: 1px 1px 2px white;">
                            ตัวอย่างชื่อผู้เข้าร่วม
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-600">
                        <p>• คลิกบนรูปภาพเพื่อกำหนดตำแหน่งชื่อ</p>
                        <p>• ลากจุดแดงเพื่อปรับตำแหน่ง</p>
                    </div>
                </div>
            </div>

            <!-- ตำแหน่งชื่อบนใบประกาศ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง X (แนวนอน)</label>
                    <input type="number" name="position_x" id="position_x" value="{{ old('position_x', $activity->position_x ?? 0) }}" min="0" max="1000"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('position_x')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง Y (แนวตั้ง)</label>
                    <input type="number" name="position_y" id="position_y" value="{{ old('position_y', $activity->position_y ?? 0) }}" min="0" max="1000"
                        class="w-full h-10 px-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('position_y')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <!-- การตั้งค่าฟอนต์ -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border-2 border-purple-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    การตั้งค่าฟอนต์
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Font Size -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ขนาดฟอนต์ (Font Size)
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range" 
                                name="font_size" 
                                id="font_size" 
                                min="8" 
                                max="72" 
                                value="{{ old('font_size', $activity->font_size ?? 16) }}"
                                class="flex-1 h-2 bg-purple-200 rounded-lg appearance-none cursor-pointer">
                            <span id="font_size_display" 
                                class="w-16 text-center font-bold text-lg bg-white px-3 py-2 rounded-lg border-2 border-purple-300">
                                {{ old('font_size', $activity->font_size ?? 16) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">ลากเพื่อปรับขนาด (8-72px)</p>
                        @error('font_size')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Font Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            สีฟอนต์ (Font Color)
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="color" 
                                name="font_color" 
                                id="font_color" 
                                value="{{ old('font_color', $activity->font_color ?? '#000000') }}"
                                class="w-16 h-12 rounded-lg border-2 border-purple-300 cursor-pointer">
                            <input type="text" 
                                id="font_color_text" 
                                value="{{ old('font_color', $activity->font_color ?? '#000000') }}"
                                readonly
                                class="flex-1 h-12 px-4 rounded-lg border-2 border-purple-300 bg-white font-mono text-center">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">คลิกเพื่อเลือกสี (แนะนำ: #000000 สีดำ)</p>
                        @error('font_color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview Text -->
                <div class="mt-4 p-4 bg-white rounded-lg border-2 border-dashed border-purple-300">
                    <p class="text-sm text-gray-600 mb-2">ตัวอย่างข้อความ:</p>
                    <p id="font_preview_text" 
                    style="font-size: {{ old('font_size', $activity->font_size ?? 16) }}px; color: {{ old('font_color', $activity->font_color ?? '#000000') }}; transition: all 0.2s ease;"
                    class="font-bold text-center">
                        นายตัวอย่าง ทดสอบภาษาไทย
                    </p>
                </div>
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
    // Font Size Slider
    document.getElementById('font_size').addEventListener('input', function() {
        const size = this.value;
        document.getElementById('font_size_display').textContent = size;
        document.getElementById('font_preview_text').style.fontSize = size + 'px';
        updatePreviewFromInputs();
    });

    // Font Color Picker
    document.getElementById('font_color').addEventListener('input', function() {
        const color = this.value;
        document.getElementById('font_color_text').value = color;
        document.getElementById('font_preview_text').style.color = color;
        updatePreviewFromInputs();
    });
    // เพิ่มในส่วน script ที่มีอยู่แล้ว
    document.getElementById('start_date').addEventListener('change', function() {
        const endDateInput = document.getElementById('end_date');
        endDateInput.min = this.value;
        
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });
    // Agency-Branch dropdown
    const agencies = @json($agencies);
    document.addEventListener("DOMContentLoaded", function () {
        const agencySelect = document.getElementById("agency_id");
        const branchSelect = document.getElementById("branch_id");

        agencySelect.addEventListener("change", function () {
            const selectedAgencyId = this.value;
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
                branchSelect.disabled = true;
                branchSelect.removeAttribute("required");
            }
        });
    });

    // ==============================================
    // Certificate Position Handler (Current & New)
    // ==============================================

    let activePreview = 'current'; // 'current' or 'new'

    // When new file is selected
    document.getElementById('certificate_img').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const newPreview = document.getElementById('new_certificate_preview');
                const newSection = document.getElementById('new_certificate_preview_section');
                
                newPreview.src = e.target.result;
                newSection.classList.remove('hidden');
                
                // Hide current certificate section
                const currentSection = document.getElementById('current_certificate_section');
                if (currentSection) {
                    currentSection.style.opacity = '0.5';
                }
                
                activePreview = 'new';
                updatePreviewFromInputs();
            };
            reader.readAsDataURL(file);
        }
    });

    // Update preview when inputs change manually
    document.getElementById('position_x').addEventListener('input', updatePreviewFromInputs);
    document.getElementById('position_y').addEventListener('input', updatePreviewFromInputs);

    function updatePreviewFromInputs() {
        const positionX = parseInt(document.getElementById('position_x').value) || 0;
        const positionY = parseInt(document.getElementById('position_y').value) || 0;
        
        const previewId = activePreview === 'new' ? 'new_certificate_preview' : 'current_certificate_preview';
        const preview = document.getElementById(previewId);
        
        if (!preview) return;
        
        const rect = preview.getBoundingClientRect();
        
        if (rect.width > 0 && rect.height > 0) {
            const x = (positionX / 1000) * rect.width;
            const y = (positionY / 1000) * rect.height;
            
            updateMarkerPosition(x, y, activePreview);
        }
    }

    // Click handler for both certificates
    function setupClickHandler(previewId, markerPrefix) {
        const preview = document.getElementById(previewId);
        if (!preview) return;
        
        preview.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const relativeX = Math.round((x / rect.width) * 1000);
            const relativeY = Math.round((y / rect.height) * 1000);
            
            updatePosition(x, y, relativeX, relativeY, markerPrefix);
        });
    }

    // Setup click handlers for both previews
    setupClickHandler('current_certificate_preview', 'current');
    setupClickHandler('new_certificate_preview', 'new');

    // Drag functionality
    let isDragging = false;
    let dragOffset = { x: 0, y: 0 };

    function setupDragHandler(markerId, previewId, prefix) {
        const marker = document.getElementById(markerId);
        if (!marker) return;
        
        marker.addEventListener('mousedown', function(e) {
            isDragging = true;
            activePreview = prefix;
            
            const rect = this.getBoundingClientRect();
            dragOffset.x = e.clientX - rect.left - rect.width / 2;
            dragOffset.y = e.clientY - rect.top - rect.height / 2;
            
            document.addEventListener('mousemove', onDrag);
            document.addEventListener('mouseup', stopDrag);
            e.preventDefault();
        });
    }

    setupDragHandler('current_name_position_marker', 'current_certificate_preview', 'current');
    setupDragHandler('new_name_position_marker', 'new_certificate_preview', 'new');

    function onDrag(e) {
        if (!isDragging) return;
        
        const previewId = activePreview === 'new' ? 'new_certificate_preview' : 'current_certificate_preview';
        const preview = document.getElementById(previewId);
        const previewRect = preview.getBoundingClientRect();
        
        const x = e.clientX - previewRect.left - dragOffset.x;
        const y = e.clientY - previewRect.top - dragOffset.y;
        
        const constrainedX = Math.max(0, Math.min(x, previewRect.width));
        const constrainedY = Math.max(0, Math.min(y, previewRect.height));
        
        const relativeX = Math.round((constrainedX / previewRect.width) * 1000);
        const relativeY = Math.round((constrainedY / previewRect.height) * 1000);
        
        updatePosition(constrainedX, constrainedY, relativeX, relativeY, activePreview);
    }

    function stopDrag() {
        isDragging = false;
        document.removeEventListener('mousemove', onDrag);
        document.removeEventListener('mouseup', stopDrag);
    }

    function updatePosition(x, y, relativeX, relativeY, prefix) {
        updateMarkerPosition(x, y, prefix);
        document.getElementById('position_x').value = relativeX;
        document.getElementById('position_y').value = relativeY;
    }

    function updateMarkerPosition(x, y, prefix) {
        const marker = document.getElementById(prefix + '_name_position_marker');
        const previewText = document.getElementById(prefix + '_preview_text');
        
        if (!marker || !previewText) return;
        
        // Get font settings
        const fontSize = document.getElementById('font_size') ? document.getElementById('font_size').value : 16;
        const fontColor = document.getElementById('font_color') ? document.getElementById('font_color').value : '#FF0000';
        
        marker.style.left = x + 'px';
        marker.style.top = y + 'px';
        marker.classList.remove('hidden');
        
        // Apply font settings to preview
        previewText.style.left = x + 'px';
        previewText.style.top = (y - 20) + 'px';
        previewText.style.transform = 'translateX(-50%)';
        previewText.style.fontSize = fontSize + 'px';
        previewText.style.color = fontColor;
        previewText.classList.remove('hidden');
        
        marker.style.animation = 'pulse 0.3s ease-in-out';
        setTimeout(() => marker.style.animation = '', 300);
    }

    // CSS Animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.2); }
        }
        
        #current_name_position_marker,
        #new_name_position_marker {
            transform: translate(-50%, -50%);
        }
        
        #current_name_position_marker:hover,
        #new_name_position_marker:hover {
            background-color: #dc2626;
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        #current_certificate_preview:hover,
        #new_certificate_preview:hover {
            filter: brightness(1.05);
        }
    `;
    document.head.appendChild(style);
    </script>

    @endsection