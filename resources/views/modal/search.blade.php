<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-center text-xl font-bold mb-4">ค้นหาพื้นที่ที่ต้องการ</h2>
        <div class="mb-4">
            <label for="province" class="block text-sm font-medium text-gray-700">เลือกจังหวัด</label>
            <select id="province"
                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>เลือกจังหวัด</option>
                @foreach ($provinces as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="district" class="block text-sm font-medium text-gray-700">เลือกอำเภอ</label>
            <select id="district"
                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>เลือกอำเภอ</option>
                @foreach ($districts as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="subdistrict" class="block text-sm font-medium text-gray-700">เลือกตำบล</label>
            <select id="subdistrict"
                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>เลือกตำบล</option>
                @foreach ($subdistricts as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-center gap-2">
            <button id="btn-search" class="px-4 py-2 bg-blue-500 rounded hover:bg-blue-400 text-white">ค้นหา</button>
            <button class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 closeModal">ปิด</button>
        </div>
    </div>
</div>
