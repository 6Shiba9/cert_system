<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate - {{ $participant->name }}</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url({{ public_path('fonts/THSarabunNew-Regular.ttf') }}) format('truetype');
        }
        
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url({{ public_path('fonts/THSarabunNew-Bold.ttf') }}) format('truetype');
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            margin: 0;
            size: A4 landscape;
        }
        
        body {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            font-family: 'THSarabunNew', sans-serif;
            overflow: hidden;
        }
        
        .certificate-container {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }
        
        .certificate-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            object-fit: contain; /* คงสัดส่วน แต่อาจมีพื้นที่ว่าง */
            object-position: center;
        }
        
        .participant-name {
            position: absolute;
            top: {{ ($activity->position_y / 1000) * 100 -2}}%;
            left: {{ ($activity->position_x / 1000) * 100 }}%;
            transform: translate(-50%, -50%);
            font-size: 39pt;
            color: #000000;
            text-align: center;
            white-space: nowrap;
            font-family: 'THSarabunNew', sans-serif;
            font-weight: bold;
            
            @if(isset($preview) && $preview)
            color: #FF0000 !important;
            @endif
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        @php
            $imagePath = storage_path('app/public/' . $activity->certificate_img);
            
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                $imageMime = mime_content_type($imagePath);
            } else {
                $imageData = '';
                $imageMime = 'image/png';
            }
        @endphp
        
        @if($imageData)
        <img src="data:{{ $imageMime }};base64,{{ $imageData }}" 
             alt="Certificate Background" 
             class="certificate-bg">
        @endif
        
        <div class="participant-name">
            {{ $participant->name }}
        </div>
    </div>
</body>
</html>