@extends('partials.layout')

@section('title', 'ใบประกาศนียบัตร - ' . $activity->activity_name)

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mb-4">
                <img src="{{ asset('storage/' . $activity->certificate_img) }}" 
                     alt="Certificate Template" 
                     class="mx-auto max-w-xs rounded-lg shadow-lg">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $activity->activity_name }}</h1>
            <p class="text-lg text-gray-600">{{ $activity->agency->agency_name }} - {{ $activity->branch->branch_name }}</p>
            <p class="text-sm text-gray-500">วันที่จัดกิจกรรม: {{ \Carbon\Carbon::parse($activity->activity_date)->format('d/m/Y') }}</p>
        </div>

        <!-- Certificate Download Form -->
        <div class="bg-white rounded-lg shadow-md p-6 max-w-md mx-auto">
            <h2 class="text-xl font-semibold mb-4 text-center">ดาวน์โหลดใบประกาศนียบัตร</h2>
            
            <form action="{{ route('certificate.download') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="activity_id" value="{{ $activity->activity_id }}">
                
                <div>
                    <label for="participant_name" class="block text-sm font-medium text-gray-700 mb-1">
                        ชื่อผู้เข้าร่วมกิจกรรม
                    </label>
                    <input type="text" 
                           id="participant_name" 
                           name="participant_name" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="กรุณาใส่ชื่อของท่าน">
                </div>

                <div>
                    <label for="access_code" class="block text-sm font-medium text-gray-700 mb-1">
                        รหัสเข้าถึง
                    </label>
                    <input type="text" 
                           id="access_code" 
                           name="access_code" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="กรุณาใส่รหัสเข้าถึง">
                </div>

                @error('error')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    ดาวน์โหลดใบประกาศ
                </button>
            </form>
        </div>

        <!-- Information -->
        <div class="mt-8 text-center text-sm text-gray-600">
            <p>• กรุณาใส่ชื่อและรหัสเข้าถึงที่ได้รับจากผู้จัดกิจกรรม</p>
            <p>• ชื่อต้องตรงกับที่ลงทะเบียนในระบบ</p>
            <p>• หากมีปัญหาในการดาวน์โหลด กรุณาติดต่อผู้จัดกิจกรรม</p>
        </div>
    </div>
</div>
@endsection
