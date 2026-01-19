<!-- template_pdf.blade.php -->
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
            object-fit: contain;
            object-position: center;
        }
        
        .participant-name {
            position: absolute;
            top: {{ ($activity->position_y / 1000) * 100 - 2 }}%;
            left: {{ ($activity->position_x / 1000) * 100 }}%;
            transform: translate(-50%, -50%);
            font-size: {{ ($activity->font_size + 15) ?? 16 }}pt;
            color: {{ $activity->font_color ?? '#000000' }};
            text-align: center;
            white-space: nowrap;
            font-family: 'THSarabunNew', sans-serif;
            font-weight: bold;
            
            @if(isset($preview) && $preview)
            color: {{ $activity->font_color ?? '#ff0000ff' }} !important;
            @endif
        }

        /* ✅ เพิ่ม QR Code และ Certificate ID */
        .verification-section {
            position: absolute;
            bottom: 15mm;
            right: 15mm;
            text-align: center;
        }

        .qr-code {
            width: 25mm;
            height: 25mm;
            border: 2px solid #000;
            padding: 2mm;
            background: white;
        }

        .certificate-id {
            font-size: 8pt;
            color: #333;
            margin-top: 2mm;
            font-family: 'THSarabunNew', sans-serif;
        }

        .verification-url {
            font-size: 7pt;
            color: #666;
            margin-top: 1mm;
        }

        /* ✅ เพิ่ม Digital Signature */
        .digital-signature {
            position: absolute;
            bottom: 15mm;
            left: 15mm;
            font-size: 7pt;
            color: #999;
            font-family: 'Courier New', monospace;
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

            // ✅ สร้าง Certificate ID (ไม่ซ้ำกัน)
            $certificateId = strtoupper(substr($activity->access_code, 0, 4)) . '-' . 
                            str_pad($participant->participant_id, 6, '0', STR_PAD_LEFT);
            
            // ✅ สร้าง Verification URL
            $verifyUrl = route('certificate.verify.public', $participant->certificate_token);
            
            // ✅ สร้าง Digital Signature (Hash)
            $signature = substr(hash('sha256', $participant->certificate_token . $participant->name . $activity->activity_id), 0, 16);
        @endphp
        
        @if($imageData)
        <img src="data:{{ $imageMime }};base64,{{ $imageData }}" 
             alt="Certificate Background" 
             class="certificate-bg">
        @endif
        
        <!-- ชื่อผู้เข้าร่วม -->
        <div class="participant-name">
            {{ $participant->name }}
        </div>

        @if(!isset($preview) || !$preview)
        <!-- ✅ QR Code พร้อม Certificate ID -->
        <div class="verification-section">
            @php
                // สร้าง QR Code URL
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($verifyUrl);
            @endphp
            <img src="{{ $qrUrl }}" alt="QR Code" class="qr-code">
            <div class="certificate-id">
                <strong>Certificate ID:</strong><br>
                {{ $certificateId }}
            </div>
            <div class="verification-url">
                Verify at: {{ config('app.url') }}
            </div>
        </div>

        <!-- ✅ Digital Signature -->
        <div class="digital-signature">
            Digital Signature: {{ $signature }}<br>
            Issued: {{ now()->format('Y-m-d H:i:s') }}
        </div>
        @endif
    </div>
</body>
</html>