<!-- Modal แบบชิดขวา -->
<div id="modal-sensor" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex justify-end hidden">
    <div class="relative bg-gray-200 w-[500px] h-full shadow-lg flex flex-col">
        <div class="flex items-center justify-between p-6">
            <h2 class="text-xl font-semibold"><i class="fa-solid fa-circle-info"></i> ข้อมูลเกษตรกรรม</h2>
            <button onclick="document.getElementById('modal-sensor').classList.add('hidden')"
                class="text-gray-500 hover:text-red-600 text-2xl font-bold">
                &times;
            </button>
        </div>
        <div class="p-6 overflow-y-auto" id="sensor-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">ชื่อผู้ใช้</p>
                    <span id="user-name" class="text-gray-600">ชื่อ</span>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">พิกัด</p>
                    <span id="sensor-position" class="text-gray-600">ตำแหน่ง</span>
                </div>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                <p class="text-green-900">สถานที่</p>
                <span id="sensor-address" class="text-gray-600">ที่อยู่</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">พืชที่ปลูก</p>
                    <span id="sensor-name" class="text-gray-600">ชื่อ</span>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">วันที่ปลูก</p>
                    <span id="sensor-date" class="text-gray-600">วันที่</span>
                </div>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                <p class="text-green-900">รายละเอียด</p>
                <span id="sensor-detail" class="text-gray-600">Detail</span>
            </div>
            <span id="sensor-update" class="text-sm text-gray-500">อัพเดทล่าสุด เวลา</span>
            <p class="text-gray-700" id="sensor-n">Nitrogen (N)</p>
            <p class="text-gray-700" id="sensor-p">Phosphorus (P)</p>
            <p class="text-gray-700" id="sensor-k">Potassium (K)</p>
            <p class="text-gray-700" id="sensor-ph">pH</p>
            <canvas id="grap-sensor-n" class="lineChart"></canvas>
            <canvas id="grap-sensor-p" class="lineChart"></canvas>
            <canvas id="grap-sensor-k" class="lineChart"></canvas>
            <canvas id="grap-sensor-ph" class="lineChart"></canvas>
        </div>
    </div>
</div>
