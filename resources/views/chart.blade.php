<!-- ส่วน form เลือกวันที่ -->
<form method="GET" class="mb-4 row g-2">
    <div class="col-auto">
        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
    </div>
    <div class="col-auto">
        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-primary" type="submit">ค้นหา</button>
    </div>
</form>

<div class="row">
    <!-- กราฟรวมความชื้น -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">ความชื้นรวม (%)</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="humCombinedChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- กราฟรวมอุณหภูมิ -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">อุณหภูมิรวม (°C)</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="tempCombinedChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- กราฟแสงภายนอก -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">แสงภายนอก (lux)</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="lightOutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- กราฟแสงภายใน -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">แสงภายใน (lux)</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="lightInChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function createChart(ctxId, label, color, dataValues) {
    const ctx = document.getElementById(ctxId);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: label,
                data: dataValues,
                borderColor: color,
                backgroundColor: color + '33',
                fill: true,
                tension: 0.3,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

// กราฟเดิม
createChart('humOutChart', 'ความชื้นภายนอก (%)', '#2196F3', @json($humid_out));
createChart('humInChart', 'ความชื้นภายใน (%)', '#4CAF50', @json($humid_in));
createChart('tempOutChart', 'อุณหภูมิภายนอก (°C)', '#FF9800', @json($temp_out));
createChart('tempInChart', 'อุณหภูมิภายใน (°C)', '#E91E63', @json($temp_in));
createChart('tanChart', 'TAN (mg/L)', '#9C27B0', @json($tan));
createChart('nh3Chart', 'NH3 (ppm)', '#795548', @json($nh3));

// กราฟใหม่
createChart('humCombinedChart', 'ความชื้นรวม (%)', '#00BCD4', @json(array_map(function($o,$i){ return $o+$i; }, $humid_out, $humid_in)));
createChart('tempCombinedChart', 'อุณหภูมิรวม (°C)', '#FF5722', @json(array_map(function($o,$i){ return $o+$i; }, $temp_out, $temp_in)));
createChart('lightOutChart', 'แสงภายนอก (lux)', '#FFC107', @json($light_out));
createChart('lightInChart', 'แสงภายใน (lux)', '#8BC34A', @json($light_in));
</script>
