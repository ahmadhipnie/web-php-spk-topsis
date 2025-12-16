// Sector Analysis - Home Page
// ApexCharts untuk Sector Performance Bar Chart & Top Stocks Cards

let sectorChartInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sector Analysis Initialized');
    
    // Load default analysis (30 hari)
    loadSectorAnalysis(30);
    
    // Event listener untuk periode filter
    document.getElementById('sectorPeriodeSelect').addEventListener('change', function() {
        const periode = this.value;
        loadSectorAnalysis(periode);
    });
});

/**
 * Load sector analysis data (performance + top stocks)
 */
function loadSectorAnalysis(periode) {
    console.log(`Loading sector analysis: periode ${periode} days`);
    
    // Show loading
    document.getElementById('sectorLoading').style.display = 'flex';
    
    // Fetch both API endpoints
    Promise.all([
        fetch(`${BASE_URL}sektor/getSectorPerformance?periode=${periode}`).then(r => r.json()),
        fetch(`${BASE_URL}sektor/getTopStocks?periode=${periode}`).then(r => r.json())
    ])
    .then(([performanceData, topStocksData]) => {
        console.log('Sector performance:', performanceData);
        console.log('Top stocks:', topStocksData);
        
        if (performanceData.success) {
            renderSectorPerformanceChart(performanceData.data, performanceData.info);
        }
        
        if (topStocksData.success) {
            renderTopStocksCards(topStocksData.data);
        }
    })
    .catch(error => {
        console.error('Error loading sector analysis:', error);
        alert('Gagal memuat analisis sektor. Silakan coba lagi.');
    })
    .finally(() => {
        // Hide loading
        document.getElementById('sectorLoading').style.display = 'none';
    });
}

/**
 * Render Sector Performance Bar Chart
 */
function renderSectorPerformanceChart(data, info) {
    // Prepare data for ApexCharts
    const categories = data.map(item => item.sektor);
    const values = data.map(item => item.avg_return);
    const colors = values.map(val => val >= 0 ? '#10b981' : '#ef4444'); // Green for positive, red for negative
    
    const options = {
        series: [{
            name: 'Avg Return (%)',
            data: values
        }],
        chart: {
            type: 'bar',
            height: 450,
            toolbar: {
                show: true,
                tools: {
                    download: true
                }
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                distributed: true,
                barHeight: '70%',
                dataLabels: {
                    position: 'top'
                }
            }
        },
        colors: colors,
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val.toFixed(2) + '%';
            },
            offsetX: 0,
            style: {
                fontSize: '12px',
                fontWeight: 'bold',
                colors: ['#fff']
            }
        },
        xaxis: {
            categories: categories,
            title: {
                text: 'Return (%)',
                style: {
                    fontSize: '14px',
                    fontWeight: 600
                }
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed(1) + '%';
                }
            }
        },
        yaxis: {
            title: {
                text: 'Sektor',
                style: {
                    fontSize: '14px',
                    fontWeight: 600
                }
            }
        },
        title: {
            text: `Performa Sektor (${info.periode})`,
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 600
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val, opts) {
                    const index = opts.dataPointIndex;
                    const stockCount = data[index].stock_count;
                    return `${val.toFixed(2)}% (${stockCount} saham)`;
                }
            }
        },
        legend: {
            show: false
        },
        grid: {
            borderColor: '#e5e7eb',
            xaxis: {
                lines: {
                    show: true
                }
            }
        }
    };
    
    // Destroy previous chart if exists
    if (sectorChartInstance) {
        sectorChartInstance.destroy();
    }
    
    // Render chart
    sectorChartInstance = new ApexCharts(
        document.querySelector("#sectorPerformanceChart"),
        options
    );
    sectorChartInstance.render();
}

/**
 * Render Top Stocks Cards per Sector
 */
function renderTopStocksCards(data) {
    const container = document.getElementById('sectorStocksGrid');
    container.innerHTML = ''; // Clear previous content
    
    // Icon mapping for sectors
    const sectorIcons = {
        'Perbankan': 'fa-university',
        'Teknologi': 'fa-microchip',
        'Energi': 'fa-bolt',
        'Pertambangan': 'fa-hard-hat',
        'Otomotif': 'fa-car',
        'Konsumer': 'fa-shopping-cart',
        'Kesehatan': 'fa-heartbeat',
        'Retail': 'fa-store',
        'Infrastruktur': 'fa-building',
        'Telekomunikasi': 'fa-signal'
    };
    
    data.forEach(sector => {
        const iconClass = sectorIcons[sector.sektor] || 'fa-chart-line';
        
        // Calculate average return for this sector
        const avgReturn = sector.top_stocks.reduce((sum, stock) => sum + stock.return, 0) / sector.top_stocks.length;
        const returnClass = avgReturn >= 0 ? 'text-success' : 'text-danger';
        const returnIcon = avgReturn >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
        
        // Create card HTML
        const cardHTML = `
            <div class="col-lg-6 col-md-12">
                <div class="sector-card">
                    <div class="sector-card-header">
                        <div class="sector-icon">
                            <i class="fas ${iconClass}"></i>
                        </div>
                        <div class="sector-info">
                            <h5 class="sector-name">${sector.sektor}</h5>
                            <span class="sector-return ${returnClass}">
                                <i class="fas ${returnIcon} me-1"></i>${avgReturn.toFixed(2)}% avg
                            </span>
                        </div>
                    </div>
                    <div class="sector-card-body">
                        ${sector.top_stocks.map((stock, index) => `
                            <div class="stock-item">
                                <div class="stock-rank">
                                    <span class="rank-badge rank-${index + 1}">#${index + 1}</span>
                                </div>
                                <div class="stock-details">
                                    <div class="stock-name-row">
                                        <strong class="stock-code">${stock.kode_saham}</strong>
                                        <span class="stock-return ${stock.return >= 0 ? 'text-success' : 'text-danger'}">
                                            <i class="fas ${stock.return >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'}"></i>
                                            ${stock.return.toFixed(2)}%
                                        </span>
                                    </div>
                                    <div class="stock-name-full">${stock.nama_saham}</div>
                                    <div class="stock-metrics">
                                        <span class="metric-badge">
                                            <i class="fas fa-chart-line me-1"></i>ROE: ${stock.roe.toFixed(2)}%
                                        </span>
                                        <span class="metric-badge">
                                            <i class="fas fa-coins me-1"></i>PER: ${stock.per.toFixed(2)}x
                                        </span>
                                        <span class="metric-badge">
                                            <i class="fas fa-dollar-sign me-1"></i>EPS: ${stock.eps.toFixed(0)}
                                        </span>
                                    </div>
                                    <div class="stock-price-info">
                                        <small>Harga: Rp ${formatNumber(stock.harga_akhir)} 
                                        <span class="text-muted">(dari Rp ${formatNumber(stock.harga_awal)})</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML += cardHTML;
    });
}

/**
 * Format number with thousand separators
 */
function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
