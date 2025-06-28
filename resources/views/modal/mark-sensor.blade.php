<!-- Modal แบบชิดขวา -->
<div id="modal-sensor" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex justify-end hidden">
    <div class="relative bg-white w-[400px] h-full shadow-lg flex flex-col">
        <div class="flex items-center justify-between p-6 border-b">
            <h2 class="text-xl font-semibold"><i class="fa-solid fa-circle-info"></i> ข้อมูลเกษตรกรรม</h2>
            <button onclick="document.getElementById('modal-sensor').classList.add('hidden')"
                class="text-gray-500 hover:text-red-600 text-2xl font-bold">
                &times;
            </button>
        </div>
        <div class="p-6 overflow-y-auto" id="sensor-content">
            <div class="max-w-sm rounded-2xl overflow-hidden shadow-lg bg-white">
                <strong id="sensor-name" class="text-xl">ชื่อ sensor</strong>
            </div>
            <br>
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
