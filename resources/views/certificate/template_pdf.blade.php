<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $participant->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            width: 297mm;  /* A4 Landscape width */
            height: 210mm; /* A4 Landscape height */
            position: relative;
            font-family: 'THSarabunNew', 'Sarabun', 'Garuda', sans-serif;
        }
        
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .certificate-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .participant-name {
            position: absolute;
            /* ✅ ใช้ตำแหน่งจาก Database */
            top: {{ ($activity->position_y / 1000) * 100 }}%;
            left: {{ ($activity->position_x / 1000) * 100 }}%;
            transform: translate(-50%, -50%);
            
            /* ✅ ขนาดฟอนต์ Fix ที่ 48pt (แก้ไขได้ตรงนี้) */
            font-size: 28pt;
            
            /* ✅ สีฟอนต์ Fix เป็นสีดำ (แก้ไขได้ตรงนี้) */
            color: #000000;
            
            text-align: center;
            white-space: nowrap;
            /* font-weight: ; */
            
            @if(isset($preview) && $preview)
            /* สีแดงสำหรับ Preview */
            color: #FF0000 !important;
            @endif
        }
    </style>
</head>
<body>
    <div class="certificate-container">
   <!-- รูปพื้นหลังใบประกาศ -->
        @php
            $imagePath = storage_path('app/public/' . $activity->certificate_img);
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageMime = mime_content_type($imagePath);
        @endphp
        <img src="data:{{ $imageMime }};base64,{{ $imageData }}" 
             alt="Certificate Background" 
             class="certificate-bg">
    
        <!-- ชื่อผู้เข้าร่วม -->
        <div class="participant-name">
            {{ $participant->name }}
        </div>
    </div>
</body>
</html>