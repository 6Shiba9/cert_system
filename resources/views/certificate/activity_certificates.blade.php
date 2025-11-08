@extends('partials.dashboard')

@section('title', 'รายการใบประกาศ')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">รายการใบประกาศ - {{ $activity->activity_name }}</h1>

    <div class="bg-white shadow-lg rounded-xl p-6">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">ชื่อ</th>
                    <th class="px-4 py-3 text-left">อีเมล</th>
                    <th class="px-4 py-3 text-left">จำนวนดาวน์โหลด</th>
                    <th class="px-4 py-3 text-left">ลิงก์ใบประกาศ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activity->participants as $index => $participant)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $participant->name }}</td>
                    <td class="px-4 py-3">{{ $participant->email ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                            {{ $participant->downloadLogs->count() }} ครั้ง
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <!-- ปุ่มดู PDF -->
                            <a href="{{ route('certificate.pdf', $participant->certificate_token) }}" 
                               target="_blank"
                               class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                📄 ดู PDF
                            </a>
                            
                            <!-- ปุ่มดาวน์โหลด -->
                            <a href="{{ route('certificate.pdf.download', $participant->certificate_token) }}" 
                               class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                ⬇️ ดาวน์โหลด
                            </a>
                            
                            <!-- คัดลอกลิงก์ -->
                            <button onclick="copyLink('{{ route('certificate.pdf', $participant->certificate_token) }}')"
                                    class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700">
                                📋 คัดลอกลิงก์
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('คัดลอกลิงก์เรียบร้อยแล้ว!\n' + url);
    });
}
</script>
@endsection