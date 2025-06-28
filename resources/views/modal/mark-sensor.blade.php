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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3">
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">ชื่อผู้ใช้</p>
                    <span id="user-name" class="text-gray-600">ชื่อ</span>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">พิกัด</p>
                    <span id="sensor-position" class="text-gray-600">ตำแหน่ง</span>
                </div>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <p class="text-green-900">สถานที่</p>
                <span id="sensor-address" class="text-gray-600">ที่อยู่</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3">
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">พืชที่ปลูก</p>
                    <span id="sensor-name" class="text-gray-600">ชื่อ</span>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">วันที่ปลูก</p>
                    <span id="sensor-date" class="text-gray-600">วันที่</span>
                </div>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <p class="text-green-900">รายละเอียด</p>
                <span id="sensor-detail" class="text-gray-600">Detail</span>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-center border border-collapse">
                        <thead>
                        <tr class="bg-gradient-to-r from-green-400 via-cyan-500 to-blue-500 text-white">
                            <th class="px-4 py-2">N mg/kg</th>
                            <th class="px-4 py-2">P mg/kg</th>
                            <th class="px-4 py-2">K mg/kg</th>
                            <th class="px-4 py-2">pH</th>
                            <th class="px-4 py-2">วัน/เดือน/ปี</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="bg-white text-gray-700">
                            <td class="px-4 py-2" id="sensor-n">14.00</td>
                            <td class="px-4 py-2" id="sensor-p">79.00</td>
                            <td class="px-4 py-2" id="sensor-k">72.00</td>
                            <td class="px-4 py-2" id="sensor-ph">756.00</td>
                            <td class="px-4 py-2" id="sensor-update">02/05/2568</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <p>ระดับไนโตรเจน (N)</p>
                <canvas id="grap-sensor-n" class="lineChart"></canvas>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <p>ระดับฟอสฟอรัส (P)</p>
                <canvas id="grap-sensor-p" class="lineChart"></canvas>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <p>ระดับโพแทสเซียม (K)</p>
                <canvas id="grap-sensor-k" class="lineChart"></canvas>
            </div>
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <p>ระดับกรดด่าง (pH)</p>
                <canvas id="grap-sensor-ph" class="lineChart"></canvas>
            </div>
        </div>
    </div>
</div>
