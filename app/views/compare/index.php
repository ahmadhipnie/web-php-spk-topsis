<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>Bandingkan Saham</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($data['stocks'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Tidak ada data saham!</strong> 
                        Pastikan database memiliki data saham untuk dibandingkan.
                    </div>
                    <?php else: ?>
                    <!-- Stock Selection Form -->
                    <form id="compareForm">
                        <div class="row mb-4">
                            <!-- Stock 1 -->
                            <div class="col-md-4 mb-3">
                                <label for="stock1" class="form-label fw-bold">Saham 1 <span class="text-danger">*</span></label>
                                <select class="form-select" id="stock1" name="stock1" required>
                                    <option value="">-- Pilih Saham --</option>
                                    <?php foreach ($data['stocks'] as $stock): ?>
                                        <option value="<?= $stock->kode_saham; ?>">
                                            <?= $stock->kode_saham; ?> - <?= $stock->nama_saham; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Stock 2 -->
                            <div class="col-md-4 mb-3">
                                <label for="stock2" class="form-label fw-bold">Saham 2 <span class="text-danger">*</span></label>
                                <select class="form-select" id="stock2" name="stock2" required>
                                    <option value="">-- Pilih Saham --</option>
                                    <?php foreach ($data['stocks'] as $stock): ?>
                                        <option value="<?= $stock->kode_saham; ?>">
                                            <?= $stock->kode_saham; ?> - <?= $stock->nama_saham; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Stock 3 (Optional) -->
                            <div class="col-md-4 mb-3">
                                <label for="stock3" class="form-label fw-bold">Saham 3 <span class="text-muted">(Opsional)</span></label>
                                <select class="form-select" id="stock3" name="stock3">
                                    <option value="">-- Pilih Saham --</option>
                                    <?php foreach ($data['stocks'] as $stock): ?>
                                        <option value="<?= $stock->kode_saham; ?>">
                                            <?= $stock->kode_saham; ?> - <?= $stock->nama_saham; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Date Range Buttons -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">Period Perbandingan</label>
                                <div class="btn-group w-100 date-range-buttons" role="group">
                                    <input type="radio" class="btn-check" name="days" id="days7" value="7">
                                    <label class="btn btn-outline-primary" for="days7">7 Hari</label>

                                    <input type="radio" class="btn-check" name="days" id="days30" value="30" checked>
                                    <label class="btn btn-outline-primary" for="days30">30 Hari</label>

                                    <input type="radio" class="btn-check" name="days" id="days90" value="90">
                                    <label class="btn btn-outline-primary" for="days90">90 Hari</label>

                                    <input type="radio" class="btn-check" name="days" id="days180" value="180">
                                    <label class="btn btn-outline-primary" for="days180">180 Hari</label>

                                    <input type="radio" class="btn-check" name="days" id="days365" value="365">
                                    <label class="btn btn-outline-primary" for="days365">365 Hari</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-graph-up me-2"></i>Bandingkan Saham
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section (Hidden by default) -->
    <div id="resultsSection" style="display: none;">
        <!-- Price Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up-arrow me-2"></i>Grafik Perbandingan Harga</h5>
                    </div>
                    <div class="card-body">
                        <div id="priceChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-table me-2"></i>Tabel Perbandingan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered comparison-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center stock-column" id="header1">Saham 1</th>
                                        <th class="text-center stock-column" id="header2">Saham 2</th>
                                        <th class="text-center stock-column" id="header3" style="display: none;">Saham 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Basic Info -->
                                    <tr class="table-secondary">
                                        <td colspan="4" class="fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi Dasar</td>
                                    </tr>
                                    <tr>
                                        <td>Kode Saham</td>
                                        <td class="text-center" id="code1">-</td>
                                        <td class="text-center" id="code2">-</td>
                                        <td class="text-center" id="code3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Saham</td>
                                        <td class="text-center" id="name1">-</td>
                                        <td class="text-center" id="name2">-</td>
                                        <td class="text-center" id="name3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Sektor</td>
                                        <td class="text-center" id="sector1">-</td>
                                        <td class="text-center" id="sector2">-</td>
                                        <td class="text-center" id="sector3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Harga Terakhir</td>
                                        <td class="text-center" id="price1">-</td>
                                        <td class="text-center" id="price2">-</td>
                                        <td class="text-center" id="price3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Volume</td>
                                        <td class="text-center" id="volume1">-</td>
                                        <td class="text-center" id="volume2">-</td>
                                        <td class="text-center" id="volume3" style="display: none;">-</td>
                                    </tr>

                                    <!-- Performance Metrics -->
                                    <tr class="table-secondary">
                                        <td colspan="4" class="fw-bold"><i class="bi bi-graph-up me-2"></i>Kinerja (Period Terpilih)</td>
                                    </tr>
                                    <tr>
                                        <td>Return (%)</td>
                                        <td class="text-center" id="return1">-</td>
                                        <td class="text-center" id="return2">-</td>
                                        <td class="text-center" id="return3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Perubahan Harga</td>
                                        <td class="text-center" id="priceChange1">-</td>
                                        <td class="text-center" id="priceChange2">-</td>
                                        <td class="text-center" id="priceChange3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Harga Tertinggi</td>
                                        <td class="text-center" id="highest1">-</td>
                                        <td class="text-center" id="highest2">-</td>
                                        <td class="text-center" id="highest3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Harga Terendah</td>
                                        <td class="text-center" id="lowest1">-</td>
                                        <td class="text-center" id="lowest2">-</td>
                                        <td class="text-center" id="lowest3" style="display: none;">-</td>
                                    </tr>

                                    <!-- Risk Metrics -->
                                    <tr class="table-secondary">
                                        <td colspan="4" class="fw-bold"><i class="bi bi-shield-exclamation me-2"></i>Risiko</td>
                                    </tr>
                                    <tr>
                                        <td>Volatilitas (%)</td>
                                        <td class="text-center" id="volatility1">-</td>
                                        <td class="text-center" id="volatility2">-</td>
                                        <td class="text-center" id="volatility3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Risk-Adjusted Return</td>
                                        <td class="text-center" id="riskAdjusted1">-</td>
                                        <td class="text-center" id="riskAdjusted2">-</td>
                                        <td class="text-center" id="riskAdjusted3" style="display: none;">-</td>
                                    </tr>

                                    <!-- Financial Ratios -->
                                    <tr class="table-secondary">
                                        <td colspan="4" class="fw-bold"><i class="bi bi-calculator me-2"></i>Rasio Keuangan</td>
                                    </tr>
                                    <tr>
                                        <td>EPS (Earning Per Share)</td>
                                        <td class="text-center" id="eps1">-</td>
                                        <td class="text-center" id="eps2">-</td>
                                        <td class="text-center" id="eps3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>PER (Price Earning Ratio)</td>
                                        <td class="text-center" id="per1">-</td>
                                        <td class="text-center" id="per2">-</td>
                                        <td class="text-center" id="per3" style="display: none;">-</td>
                                    </tr>
                                    <tr>
                                        <td>ROE (Return on Equity) %</td>
                                        <td class="text-center" id="roe1">-</td>
                                        <td class="text-center" id="roe2">-</td>
                                        <td class="text-center" id="roe3" style="display: none;">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Winner Badge -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-success" id="winnerAlert" style="display: none;">
                                    <h5 class="alert-heading"><i class="bi bi-trophy-fill me-2"></i>Saham Terbaik</h5>
                                    <p class="mb-0" id="winnerText"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="text-center mt-4" style="display: none;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Memuat data perbandingan...</p>
    </div>

    <!-- Definisikan BASE_URL -->
<script>
    window.BASE_URL = '<?= BASE_URL; ?>';
    console.log('BASE_URL:', window.BASE_URL);
</script>

<!-- ApexCharts untuk grafik -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Compare Script - INLINE -->
<script>
// Stock Comparison JavaScript
let priceChart = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== Compare Page Loaded ===');
    console.log('BASE_URL:', window.BASE_URL);
    
    const compareForm = document.getElementById('compareForm');
    console.log('Form found:', !!compareForm);
    
    if (!compareForm) {
        console.error('❌ Form not found!');
        alert('ERROR: Form tidak ditemukan!');
        return;
    }
    
    // Handle form submit
    compareForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('✓ Form submitted!');
        loadComparisonData();
    });
    
    // Prevent duplicate selection
    document.getElementById('stock1').addEventListener('change', checkDuplicates);
    document.getElementById('stock2').addEventListener('change', checkDuplicates);
    document.getElementById('stock3').addEventListener('change', checkDuplicates);
    
    console.log('=== Initialization Complete ===');
});

function checkDuplicates() {
    const stock1 = document.getElementById('stock1').value;
    const stock2 = document.getElementById('stock2').value;
    const stock3 = document.getElementById('stock3').value;
    
    if (stock1 && stock2 && stock1 === stock2) {
        alert('Saham 1 dan Saham 2 tidak boleh sama!');
        document.getElementById('stock2').value = '';
        return;
    }
    
    if (stock1 && stock3 && stock1 === stock3) {
        alert('Saham 1 dan Saham 3 tidak boleh sama!');
        document.getElementById('stock3').value = '';
        return;
    }
    
    if (stock2 && stock3 && stock2 === stock3) {
        alert('Saham 2 dan Saham 3 tidak boleh sama!');
        document.getElementById('stock3').value = '';
        return;
    }
}

function loadComparisonData() {
    console.log('loadComparisonData called');
    
    const stock1 = document.getElementById('stock1').value;
    const stock2 = document.getElementById('stock2').value;
    const stock3 = document.getElementById('stock3').value;
    const days = document.querySelector('input[name="days"]:checked').value;
    
    console.log('Selected:', { stock1, stock2, stock3, days });
    
    if (!stock1 || !stock2) {
        alert('Pilih minimal 2 saham untuk dibandingkan!');
        return;
    }
    
    // Show loading
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('resultsSection').style.display = 'none';
    
    // Prepare form data
    const formData = new FormData();
    formData.append('stock1', stock1);
    formData.append('stock2', stock2);
    if (stock3) formData.append('stock3', stock3);
    formData.append('days', days);
    
    const url = window.BASE_URL + 'compare/getData';
    console.log('Fetching from:', url);
    
    // AJAX request
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('HTTP error ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Data received:', data);
        if (data.success) {
            renderComparison(data);
        } else {
            alert(data.message || 'Gagal memuat data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    })
    .finally(() => {
        document.getElementById('loadingSpinner').style.display = 'none';
    });
}

function renderComparison(data) {
    const { chartData, comparisonData, stocks } = data;
    console.log('Rendering comparison for:', stocks);
    
    // Show results
    document.getElementById('resultsSection').style.display = 'block';
    
    // Show/hide third column
    const hasThirdStock = stocks.length === 3;
    const thirdColElements = ['header3', 'code3', 'name3', 'sector3', 'price3', 'volume3', 
                               'return3', 'priceChange3', 'highest3', 'lowest3', 
                               'volatility3', 'riskAdjusted3', 'eps3', 'per3', 'roe3'];
    
    thirdColElements.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = hasThirdStock ? 'table-cell' : 'none';
    });
    
    // Render chart & table
    renderPriceChart(chartData, stocks);
    renderComparisonTable(comparisonData, stocks);
    showWinner(comparisonData, stocks);
    
    // Scroll to results
    document.getElementById('resultsSection').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
}

function renderPriceChart(chartData, stocks) {
    console.log('Rendering chart...');
    
    const series = [];
    const colors = ['#0d6efd', '#198754', '#ffc107'];
    
    stocks.forEach((stockCode, index) => {
        if (chartData[stockCode]) {
            series.push({
                name: `${stockCode} - ${chartData[stockCode].name}`,
                data: chartData[stockCode].data.map(item => ({
                    x: new Date(item.date).getTime(),
                    y: parseFloat(item.price)
                }))
            });
        }
    });
    
    const options = {
        series: series,
        chart: {
            type: 'line',
            height: 400,
            zoom: { enabled: true },
            toolbar: { show: true }
        },
        stroke: {
            width: 3,
            curve: 'smooth'
        },
        colors: colors.slice(0, stocks.length),
        dataLabels: { enabled: false },
        xaxis: {
            type: 'datetime',
            labels: { format: 'dd MMM' }
        },
        yaxis: {
            title: { text: 'Harga (Rp)' },
            labels: {
                formatter: function(value) {
                    return formatNumber(value);
                }
            }
        },
        tooltip: {
            shared: true,
            x: { format: 'dd MMM yyyy' },
            y: {
                formatter: function(value) {
                    return 'Rp ' + formatNumber(value);
                }
            }
        },
        legend: { position: 'top' }
    };
    
    if (priceChart) priceChart.destroy();
    
    priceChart = new ApexCharts(document.querySelector("#priceChart"), options);
    priceChart.render();
}

function renderComparisonTable(comparisonData, stocks) {
    console.log('Rendering table...');
    
    stocks.forEach((stockCode, index) => {
        const data = comparisonData[stockCode];
        const col = index + 1;
        
        // Update header
        document.getElementById(`header${col}`).textContent = data.kode_saham;
        
        // Basic info
        document.getElementById(`code${col}`).textContent = data.kode_saham;
        document.getElementById(`name${col}`).textContent = data.nama_saham;
        document.getElementById(`sector${col}`).textContent = data.sektor || '-';
        document.getElementById(`price${col}`).innerHTML = formatCurrency(data.harga_terakhir);
        document.getElementById(`volume${col}`).textContent = formatVolume(data.volume);
        
        // Performance
        document.getElementById(`return${col}`).innerHTML = formatPercentage(data.return_pct);
        document.getElementById(`priceChange${col}`).innerHTML = formatPriceChange(data.price_change, data.price_change_pct);
        document.getElementById(`highest${col}`).innerHTML = formatCurrency(data.highest_price);
        document.getElementById(`lowest${col}`).innerHTML = formatCurrency(data.lowest_price);
        
        // Risk
        document.getElementById(`volatility${col}`).innerHTML = formatVolatility(data.volatility);
        document.getElementById(`riskAdjusted${col}`).textContent = data.risk_adjusted_return || '-';
        
        // Financial
        document.getElementById(`eps${col}`).textContent = data.eps || '-';
        document.getElementById(`per${col}`).textContent = data.per || '-';
        document.getElementById(`roe${col}`).textContent = data.roe ? data.roe + '%' : '-';
    });
}

function showWinner(comparisonData, stocks) {
    let winner = null;
    let maxScore = -Infinity;
    
    stocks.forEach(stockCode => {
        const data = comparisonData[stockCode];
        const score = parseFloat(data.return_pct) + (parseFloat(data.risk_adjusted_return) * 5);
        
        if (score > maxScore) {
            maxScore = score;
            winner = stockCode;
        }
    });
    
    if (winner) {
        const winnerData = comparisonData[winner];
        const text = `<strong>${winnerData.kode_saham} - ${winnerData.nama_saham}</strong> 
                      memiliki performa terbaik dengan return <strong>${winnerData.return_pct}%</strong> 
                      dan risk-adjusted return <strong>${winnerData.risk_adjusted_return}</strong> 
                      dalam periode yang dipilih.`;
        
        document.getElementById('winnerText').innerHTML = text;
        document.getElementById('winnerAlert').style.display = 'block';
    }
}

// Formatting functions
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(Math.round(num));
}

function formatCurrency(value) {
    return 'Rp ' + formatNumber(value);
}

function formatVolume(value) {
    if (value >= 1000000000) return (value / 1000000000).toFixed(2) + 'B';
    if (value >= 1000000) return (value / 1000000).toFixed(2) + 'M';
    if (value >= 1000) return (value / 1000).toFixed(2) + 'K';
    return formatNumber(value);
}

function formatPercentage(value) {
    const color = value >= 0 ? 'text-success' : 'text-danger';
    const icon = value >= 0 ? '▲' : '▼';
    return `<span class="${color}">${icon} ${Math.abs(value).toFixed(2)}%</span>`;
}

function formatPriceChange(change, changePct) {
    const color = change >= 0 ? 'text-success' : 'text-danger';
    const icon = change >= 0 ? '▲' : '▼';
    return `<span class="${color}">${icon} Rp ${formatNumber(Math.abs(change))} (${Math.abs(changePct).toFixed(2)}%)</span>`;
}

function formatVolatility(value) {
    return value.toFixed(2) + '%';
}
</script>

</div>
