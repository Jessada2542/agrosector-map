@extends('layouts.app')
@section('content')
    <div class="p-6 rounded-xl shadow-sm border border-green-200 mb-6">
        <h1 class="text-2xl font-bold text-green-700 mb-2">📊 Dashboard</h1>
        <p class="text-green-900">สรุปข้อมูลภาพรวมของระบบในวันนี้</p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
            <div class="text-green-600 text-lg font-semibold mb-2">👥 ผู้ใช้งาน</div>
            <div class="text-3xl font-bold text-green-800">124 คน</div>
            <p class="text-green-700 mt-2">เพิ่มขึ้น 12% จากเมื่อวาน</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
            <div class="text-green-600 text-lg font-semibold mb-2">💰 รายได้</div>
            <div class="text-3xl font-bold text-green-800">฿5,420</div>
            <p class="text-green-700 mt-2">รายได้วันนี้</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
            <div class="text-green-600 text-lg font-semibold mb-2">🔔 การแจ้งเตือน</div>
            <div class="text-3xl font-bold text-green-800">3 รายการ</div>
            <p class="text-green-700 mt-2">แจ้งเตือนที่ยังไม่อ่าน</p>
        </div>
    </div>
@endsection
