<div id="modal-report" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-center text-xl font-bold mb-4">สร้างรายงาน</h2>
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">รูปภาพ <span class="text-sm text-red-700">*สูงสุด 3 ภาพ</span></label>
            <input type="file" id="image" multiple accept="image/*" class="mt-1 block w-full" />
            <div id="image-preview" class="flex flex-wrap gap-2 mt-2"></div>
        </div>
        <div class="mb-4">
            <label for="detail" class="block text-sm font-medium text-gray-700">เนื้อหา</label>
            <textarea class="border border-gray-300 rounded-lg p-2 w-full mt-1" id="detail" cols="30" rows="3"></textarea>
        </div>
        <div class="flex justify-center gap-2">
            <button id="btn-report-submit" class="px-4 py-2 bg-blue-500 rounded hover:bg-blue-400 text-white">สร้าง</button>
            <button class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 closeModal">ปิด</button>
        </div>
    </div>
</div>
