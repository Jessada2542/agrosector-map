<!-- Modal แบบชิดขวา -->
<div id="modal-sensor" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-40 z-50 flex justify-end hidden">
    <div class="relative bg-gray-200 w-full sm:w-[600px] max-w-screen h-full shadow-lg flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between p-6">
            <h2 class="text-xl font-semibold">
                <i class="fa-solid fa-circle-info"></i> ข้อมูลเกษตรกรรม
            </h2>
            <button onclick="document.getElementById('modal-sensor').classList.add('hidden')"
                class="text-gray-500 hover:text-red-600 text-2xl font-bold">
                &times;
            </button>
        </div>

        <!-- Content -->
        <div class="p-4 sm:p-6 overflow-y-auto flex-1" id="sensor-content">

            <!-- กล่องข้อมูลผู้ใช้และพิกัด -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-3">
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">ชื่อผู้ใช้</p>
                    <span id="user-name" class="text-gray-600">ชื่อ</span>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">พิกัด</p>
                    <span id="sensor-position" class="text-gray-600">ตำแหน่ง</span>
                </div>
            </div>

            <!-- สถานที่ -->
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <p class="text-green-900">สถานที่</p>
                <span id="sensor-address" class="text-gray-600">ที่อยู่</span>
            </div>

            <!-- พืชที่ปลูก / วันที่ปลูก -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-3">
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">พืชที่ปลูก</p>
                    <span id="sensor-name" class="text-gray-600">ชื่อ</span>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3">
                    <p class="text-green-900">วันที่ปลูก</p>
                    <span id="sensor-date" class="text-gray-600">วันที่</span>
                </div>
            </div>

            <!-- รายละเอียด -->
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <p class="text-green-900">รายละเอียด</p>
                <span id="sensor-detail" class="text-gray-600">Detail</span>
            </div>

            <!-- ค่าที่วัด -->
            <div class="rounded-md overflow-hidden shadow-lg bg-white p-3 mb-3">
                <div class="mb-4">
                    <p class="text-gray-500 text-sm">เวลาอ่านค่า</p>
                    <p id="sensor-update" class="text-lg font-medium text-gray-800">02/05/2568 14:35</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-center">
                    <div id="box-n">
                        <p class="text-gray-500 text-sm">N (mg/kg)</p>
                        <p id="sensor-n" class="text-xl font-semibold text-green-600">14.00</p>
                    </div>
                    <div id="box-p">
                        <p class="text-gray-500 text-sm">P (mg/kg)</p>
                        <p id="sensor-p" class="text-xl font-semibold text-blue-600">79.00</p>
                    </div>
                    <div id="box-k">
                        <p class="text-gray-500 text-sm">K (mg/kg)</p>
                        <p id="sensor-k" class="text-xl font-semibold text-cyan-600">72.00</p>
                    </div>
                    <div id="box-ph">
                        <p class="text-gray-500 text-sm">pH</p>
                        <p id="sensor-ph" class="text-xl font-semibold text-purple-600">7.56</p>
                    </div>
                    <div id="box-s-h">
                        <p class="text-gray-500 text-sm">ความชื้นดิ้น (%)</p>
                        <p id="sensor-soil-humidity" class="text-xl font-semibold text-indigo-600">45.00</p>
                    </div>
                    <div id="box-s-t">
                        <p class="text-gray-500 text-sm">อุณหภูมิดิ้น (°C)</p>
                        <p id="sensor-soil-temperature" class="text-xl font-semibold text-indigo-600">45.00</p>
                    </div>
                    <div id="box-ec">
                        <p class="text-gray-500 text-sm">EC</p>
                        <p id="sensor-ec" class="text-xl font-semibold text-orange-600">40.00</p>
                    </div>
                    <div id="box-light">
                        <p class="text-gray-500 text-sm">แสง</p>
                        <p id="sensor-light" class="text-xl font-semibold text-orange-600">40.00</p>
                    </div>
                    <div id="box-a-h">
                        <p class="text-gray-500 text-sm">ความชื้น (%)</p>
                        <p id="sensor-air-humidity" class="text-xl font-semibold text-orange-600">40.00</p>
                    </div>
                    <div id="box-a-t">
                        <p class="text-gray-500 text-sm">อุณหภูมิ (°C)</p>
                        <p id="sensor-air-temperature" class="text-xl font-semibold text-indigo-600">45.00</p>
                    </div>
                    <div id="box-co2">
                        <p class="text-gray-500 text-sm">CO2 (ppm)</p>
                        <p id="sensor-co2" class="text-xl font-semibold text-indigo-600">45.00</p>
                    </div>
                    <div id="box-nh3">
                        <p class="text-gray-500 text-sm">NH3 (ppm)</p>
                        <p id="sensor-nh3" class="text-xl font-semibold text-indigo-600">45.00</p>
                    </div>
                </div>
            </div>

            <!-- กราฟทั้งหมด -->
            <div class="space-y-4">
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-n">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับไนโตรเจน (N)</p>
                    <canvas id="grap-sensor-n" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-p">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับฟอสฟอรัส (P)</p>
                    <canvas id="grap-sensor-p" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-k">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับโพแทสเซียม (K)</p>
                    <canvas id="grap-sensor-k" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chartph">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับกรดด่าง (pH)</p>
                    <canvas id="grap-sensor-ph" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-s-h">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับความชื้นดิน (Soil Humidity)</p>
                    <canvas id="grap-sensor-sh" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-s-t">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับอุณหภูมิดิน (Soil Temperature)</p>
                    <canvas id="grap-sensor-st" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-ec">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับการนำไฟฟ้า (EC)</p>
                    <canvas id="grap-sensor-ec" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-light">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับความเข้มแสง (lux)</p>
                    <canvas id="grap-sensor-light" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-a-h">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับความชื้น (Humidity)</p>
                    <canvas id="grap-sensor-ah" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-a-t">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับอุณหภูมิ (Temperature)</p>
                    <canvas id="grap-sensor-at" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-co2">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับคาร์บอนไดออกไซด์ (CO2)</p>
                    <canvas id="grap-sensor-co2" class="lineChart w-full min-w-0"></canvas>
                </div>
                <div class="rounded-md overflow-hidden shadow-lg bg-white p-3" id="box-chart-nh3">
                    <p class="text-green-800 text-sm font-semibold mb-2">ระดับแอมโมเนีย (NH3)</p>
                    <canvas id="grap-sensor-nh3" class="lineChart w-full min-w-0"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
