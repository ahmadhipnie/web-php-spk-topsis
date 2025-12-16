// Stock Chart - Home Page
// ApexCharts untuk Candlestick dan Volume Chart

let priceChartInstance = null;
let volumeChartInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Stock Chart Initialized');
    console.log('BASE_URL:', BASE_URL);
    
    // Load stock list
    loadStockList();
    
    // Load default chart (BBCA, 30 hari)
    loadChartData('BBCA', 30);
    
    // Event listeners
    document.getElementById('stockSelect').addEventListener('change', function() {
        const kode = this.value;
        const periode = document.getElementById('periodeSelect').value;
        loadChartData(kode, periode);
    });
    
    document.getElementById('periodeSelect').addEventListener('change', function() {
        const kode = document.getElementById('stockSelect').value;
        const periode = this.value;
        loadChartData(kode, periode);
    });
    
    document.getElementById('downloadBtn').addEventListener('click', function() {
        const kode = document.getElementById('stockSelect').value;
        const periode = document.getElementById('periodeSelect').value;
        downloadCSV(kode, periode);
    });
});

/**
 * Load stock list untuk dropdown
 */
function loadStockList() {
    console.log('Loading stock list from:', BASE_URL + 'saham/getStockList');
    
    fetch(BASE_URL + 'saham/getStockList')
        .then(response => {
            console.log('Stock list response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Stock list data:', data);
            
            if (data.success) {
                const select = document.getElementById('stockSelect');
                select.innerHTML = '';
                
                data.data.forEach(stock => {
                    const option = document.createElement('option');
                    option.value = stock.kode_saham;
                    option.textContent = `${stock.kode_saham} - ${stock.nama_saham}`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error loading stock list:', error));
}

/**
 * Load chart data dari API
 */
function loadChartData(kode_saham, periode) {
    console.log(`Loading chart data: ${kode_saham}, periode: ${periode} days`);
    
    // Show loading
    document.getElementById('chartLoading').style.display = 'flex';
    
    const url = `${BASE_URL}saham/getStockData?kode_saham=${kode_saham}&periode=${periode}`;
    console.log('Fetching from:', url);
    
    fetch(url)
        .then(response => {
            console.log('Chart data response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Chart data:', data);
            
            if (data.success) {
                renderPriceChart(data.data, data.info);
                renderVolumeChart(data.data);
                updateChartInfo(data.data, data.info);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            alert('Gagal memuat data. Silakan coba lagi.');
        })
        .finally(() => {
            // Hide loading
            document.getElementById('chartLoading').style.display = 'none';
        });
}

/**
 * Render Candlestick Chart (Harga OHLC)
 */
function renderPriceChart(chartData, info) {
    const options = {
        series: [{
            name: 'Harga',
            data: chartData.ohlc
        }],
        chart: {
            type: 'candlestick',
            height: 400,
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                }
            },
            animations: {
                enabled: true,
                speed: 800
            }
        },
        title: {
            text: `${info.kode_saham} - Periode ${info.periode}`,
            align: 'left',
            style: {
                fontSize: '16px',
                fontWeight: '600'
            }
        },
        xaxis: {
            type: 'datetime',
            labels: {
                datetimeUTC: false
            }
        },
        yaxis: {
            tooltip: {
                enabled: true
            },
            labels: {
                formatter: function(value) {
                    return 'Rp ' + value.toLocaleString('id-ID');
                }
            }
        },
        tooltip: {
            custom: function({seriesIndex, dataPointIndex, w}) {
                const data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                const date = new Date(data.x);
                
                return `<div class="custom-tooltip">
                    <div class="tooltip-title">${date.toLocaleDateString('id-ID')}</div>
                    <div class="tooltip-row">
                        <span>Buka:</span>
                        <strong>Rp ${data.y[0].toLocaleString('id-ID')}</strong>
                    </div>
                    <div class="tooltip-row">
                        <span>Tertinggi:</span>
                        <strong>Rp ${data.y[1].toLocaleString('id-ID')}</strong>
                    </div>
                    <div class="tooltip-row">
                        <span>Terendah:</span>
                        <strong>Rp ${data.y[2].toLocaleString('id-ID')}</strong>
                    </div>
                    <div class="tooltip-row">
                        <span>Tutup:</span>
                        <strong>Rp ${data.y[3].toLocaleString('id-ID')}</strong>
                    </div>
                </div>`;
            }
        },
        plotOptions: {
            candlestick: {
                colors: {
                    upward: '#22c55e',
                    downward: '#ef4444'
                }
            }
        },
        grid: {
            borderColor: '#f1f1f1'
        }
    };

    // Destroy previous chart if exists
    if (priceChartInstance) {
        priceChartInstance.destroy();
    }

    // Render new chart
    priceChartInstance = new ApexCharts(document.querySelector("#priceChart"), options);
    priceChartInstance.render();
}

/**
 * Render Volume Bar Chart
 */
function renderVolumeChart(chartData) {
    const options = {
        series: [{
            name: 'Volume',
            data: chartData.volume
        }],
        chart: {
            type: 'bar',
            height: 200,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                speed: 800
            }
        },
        plotOptions: {
            bar: {
                colors: {
                    ranges: [{
                        from: 0,
                        to: 999999999999,
                        color: '#3b82f6'
                    }]
                },
                columnWidth: '80%'
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            type: 'datetime',
            labels: {
                show: false
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    if (value >= 1000000) {
                        return (value / 1000000).toFixed(1) + 'M';
                    } else if (value >= 1000) {
                        return (value / 1000).toFixed(0) + 'K';
                    }
                    return value;
                }
            }
        },
        tooltip: {
            custom: function({seriesIndex, dataPointIndex, w}) {
                const data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                const date = new Date(data.x);
                
                return `<div class="custom-tooltip">
                    <div class="tooltip-title">${date.toLocaleDateString('id-ID')}</div>
                    <div class="tooltip-row">
                        <span>Volume:</span>
                        <strong>${data.y.toLocaleString('id-ID')}</strong>
                    </div>
                </div>`;
            }
        },
        grid: {
            borderColor: '#f1f1f1'
        }
    };

    // Destroy previous chart if exists
    if (volumeChartInstance) {
        volumeChartInstance.destroy();
    }

    // Render new chart
    volumeChartInstance = new ApexCharts(document.querySelector("#volumeChart"), options);
    volumeChartInstance.render();
}

/**
 * Update chart info boxes
 */
function updateChartInfo(chartData, info) {
    // Total data
    document.getElementById('infoTotalData').textContent = info.total_data + ' hari';
    
    // Calculate highest, lowest, and change
    const prices = chartData.prices.close;
    const highest = Math.max(...prices);
    const lowest = Math.min(...prices);
    const firstPrice = prices[0];
    const lastPrice = prices[prices.length - 1];
    const change = ((lastPrice - firstPrice) / firstPrice) * 100;
    
    document.getElementById('infoHighest').textContent = 'Rp ' + highest.toLocaleString('id-ID');
    document.getElementById('infoLowest').textContent = 'Rp ' + lowest.toLocaleString('id-ID');
    
    const changeEl = document.getElementById('infoChange');
    const changeText = (change >= 0 ? '+' : '') + change.toFixed(2) + '%';
    changeEl.textContent = changeText;
    changeEl.className = 'info-value ' + (change >= 0 ? 'text-success' : 'text-danger');
}

/**
 * Download data as CSV
 */
function downloadCSV(kode_saham, periode) {
    window.location.href = `${BASE_URL}saham/downloadCSV?kode_saham=${kode_saham}&periode=${periode}`;
}
