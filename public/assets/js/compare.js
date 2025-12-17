// Stock Comparison JavaScript - WITH DEBUG
let priceChart = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== COMPARE PAGE DEBUG ===');
    console.log('1. Page loaded');
    console.log('2. BASE_URL:', window.BASE_URL);
    
    // Check if form exists
    const compareForm = document.getElementById('compareForm');
    console.log('3. Form found:', !!compareForm);
    
    if (!compareForm) {
        console.error('❌ Compare form not found!');
        alert('ERROR: Form tidak ditemukan! Periksa ID form di HTML.');
        return;
    }
    
    console.log('4. Form element:', compareForm);
    
    // Check if selects exist
    const stock1 = document.getElementById('stock1');
    const stock2 = document.getElementById('stock2');
    const stock3 = document.getElementById('stock3');
    console.log('5. Stock selects found:', {
        stock1: !!stock1,
        stock2: !!stock2,
        stock3: !!stock3
    });
    
    // Check if radio buttons exist
    const daysRadios = document.querySelectorAll('input[name="days"]');
    console.log('6. Days radio buttons found:', daysRadios.length);
    
    // Attach submit event
    compareForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('7. ✓ Form submitted!');
        loadComparisonData();
    });
    
    console.log('8. Submit event listener attached');
    console.log('=== END DEBUG ===');
    
    // Prevent duplicate stock selection
    if (stock1) stock1.addEventListener('change', checkDuplicates);
    if (stock2) stock2.addEventListener('change', checkDuplicates);
    if (stock3) stock3.addEventListener('change', checkDuplicates);
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
    
    console.log('Selected stocks:', { stock1, stock2, stock3, days });
    
    if (!stock1 || !stock2) {
        alert('Pilih minimal 2 saham untuk dibandingkan!');
        return;
    }
    
    // Show loading spinner
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('resultsSection').style.display = 'none';
    
    // Prepare form data
    const formData = new FormData();
    formData.append('stock1', stock1);
    formData.append('stock2', stock2);
    if (stock3) formData.append('stock3', stock3);
    formData.append('days', days);
    
    const url = window.BASE_URL + 'compare/getData';
    console.log('Fetching from URL:', url);
    
    // AJAX request
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response received:', data);
        if (data.success) {
            renderComparison(data);
        } else {
            alert(data.message || 'Gagal memuat data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data');
    })
    .finally(() => {
        document.getElementById('loadingSpinner').style.display = 'none';
    });
}

function renderComparison(data) {
    const { chartData, comparisonData, stocks } = data;
    
    // Show results section
    document.getElementById('resultsSection').style.display = 'block';
    
    // Show/hide third column based on stock count
    const hasThirdStock = stocks.length === 3;
    const thirdColumns = document.querySelectorAll('#header3, #code3, #name3, #sector3, #price3, #volume3, #return3, #priceChange3, #highest3, #lowest3, #volatility3, #riskAdjusted3, #eps3, #per3, #roe3');
    thirdColumns.forEach(col => {
        col.style.display = hasThirdStock ? 'table-cell' : 'none';
    });
    
    // Render price chart
    renderPriceChart(chartData, stocks);
    
    // Render comparison table
    renderComparisonTable(comparisonData, stocks);
    
    // Determine and show winner
    showWinner(comparisonData, stocks);
    
    // Scroll to results
    document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function renderPriceChart(chartData, stocks) {
    // Prepare series data for ApexCharts
    const series = [];
    const colors = ['#0d6efd', '#198754', '#ffc107']; // Blue, Green, Yellow
    
    stocks.forEach((stockCode, index) => {
        if (chartData[stockCode]) {
            series.push({
                name: `${stockCode} - ${chartData[stockCode].name}`,
                data: chartData[stockCode].data.map(item => ({
                    x: new Date(item.date).getTime(),
                    y: item.price
                }))
            });
        }
    });
    
    // Chart options
    const options = {
        series: series,
        chart: {
            type: 'line',
            height: 400,
            zoom: {
                enabled: true,
                type: 'x',
                autoScaleYaxis: true
            },
            toolbar: {
                show: true
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        stroke: {
            width: 3,
            curve: 'smooth'
        },
        colors: colors.slice(0, stocks.length),
        dataLabels: {
            enabled: false
        },
        markers: {
            size: 0,
            hover: {
                size: 6
            }
        },
        xaxis: {
            type: 'datetime',
            labels: {
                datetimeUTC: false,
                format: 'dd MMM'
            }
        },
        yaxis: {
            title: {
                text: 'Harga (Rp)'
            },
            labels: {
                formatter: function(value) {
                    return formatNumber(value);
                }
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            x: {
                format: 'dd MMM yyyy'
            },
            y: {
                formatter: function(value) {
                    return 'Rp ' + formatNumber(value);
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            fontSize: '14px',
            markers: {
                width: 12,
                height: 12,
                radius: 2
            }
        },
        grid: {
            borderColor: '#e7e7e7',
            strokeDashArray: 5
        }
    };
    
    // Destroy existing chart if exists
    if (priceChart) {
        priceChart.destroy();
    }
    
    // Create new chart
    priceChart = new ApexCharts(document.querySelector("#priceChart"), options);
    priceChart.render();
}

function renderComparisonTable(comparisonData, stocks) {
    stocks.forEach((stockCode, index) => {
        const data = comparisonData[stockCode];
        const colNum = index + 1;
        
        // Update headers
        document.getElementById(`header${colNum}`).textContent = data.kode_saham;
        
        // Basic Info
        document.getElementById(`code${colNum}`).textContent = data.kode_saham;
        document.getElementById(`name${colNum}`).textContent = data.nama_saham;
        document.getElementById(`sector${colNum}`).textContent = data.sektor || '-';
        document.getElementById(`price${colNum}`).innerHTML = formatCurrency(data.harga_terakhir);
        document.getElementById(`volume${colNum}`).textContent = formatVolume(data.volume);
        
        // Performance
        document.getElementById(`return${colNum}`).innerHTML = formatPercentage(data.return_pct);
        document.getElementById(`priceChange${colNum}`).innerHTML = formatPriceChange(data.price_change, data.price_change_pct);
        document.getElementById(`highest${colNum}`).innerHTML = formatCurrency(data.highest_price);
        document.getElementById(`lowest${colNum}`).innerHTML = formatCurrency(data.lowest_price);
        
        // Risk
        document.getElementById(`volatility${colNum}`).innerHTML = formatVolatility(data.volatility);
        document.getElementById(`riskAdjusted${colNum}`).innerHTML = formatRiskAdjusted(data.risk_adjusted_return);
        
        // Financial Ratios
        document.getElementById(`eps${colNum}`).textContent = data.eps || '-';
        document.getElementById(`per${colNum}`).textContent = data.per || '-';
        document.getElementById(`roe${colNum}`).textContent = data.roe ? data.roe + '%' : '-';
    });
    
    // Highlight best values
    highlightBestValues(comparisonData, stocks);
}

function highlightBestValues(comparisonData, stocks) {
    // Metrics where higher is better
    const higherBetter = ['return_pct', 'risk_adjusted_return', 'eps', 'roe'];
    // Metrics where lower is better
    const lowerBetter = ['volatility', 'per'];
    
    higherBetter.forEach(metric => {
        let maxValue = -Infinity;
        let maxIndex = -1;
        
        stocks.forEach((stockCode, index) => {
            const value = parseFloat(comparisonData[stockCode][metric]);
            if (!isNaN(value) && value > maxValue) {
                maxValue = value;
                maxIndex = index;
            }
        });
        
        if (maxIndex >= 0) {
            const elementIds = getElementIdForMetric(metric);
            elementIds.forEach(id => {
                const element = document.getElementById(`${id}${maxIndex + 1}`);
                if (element) element.classList.add('best-value');
            });
        }
    });
    
    lowerBetter.forEach(metric => {
        let minValue = Infinity;
        let minIndex = -1;
        
        stocks.forEach((stockCode, index) => {
            const value = parseFloat(comparisonData[stockCode][metric]);
            if (!isNaN(value) && value < minValue && value > 0) {
                minValue = value;
                minIndex = index;
            }
        });
        
        if (minIndex >= 0) {
            const elementIds = getElementIdForMetric(metric);
            elementIds.forEach(id => {
                const element = document.getElementById(`${id}${minIndex + 1}`);
                if (element) element.classList.add('best-value');
            });
        }
    });
}

function getElementIdForMetric(metric) {
    const mapping = {
        'return_pct': ['return'],
        'risk_adjusted_return': ['riskAdjusted'],
        'eps': ['eps'],
        'roe': ['roe'],
        'volatility': ['volatility'],
        'per': ['per']
    };
    return mapping[metric] || [];
}

function showWinner(comparisonData, stocks) {
    // Calculate overall score (simple scoring based on return and risk-adjusted return)
    let scores = {};
    
    stocks.forEach(stockCode => {
        const data = comparisonData[stockCode];
        // Simple scoring: return + risk_adjusted_return
        const score = parseFloat(data.return_pct) + (parseFloat(data.risk_adjusted_return) * 5);
        scores[stockCode] = {
            score: score,
            name: data.nama_saham
        };
    });
    
    // Find winner
    let winner = null;
    let maxScore = -Infinity;
    
    Object.keys(scores).forEach(code => {
        if (scores[code].score > maxScore) {
            maxScore = scores[code].score;
            winner = code;
        }
    });
    
    if (winner) {
        const winnerData = comparisonData[winner];
        const winnerText = `
            <strong>${winnerData.kode_saham} - ${winnerData.nama_saham}</strong> 
            memiliki performa terbaik dengan return <strong>${winnerData.return_pct}%</strong> 
            dan risk-adjusted return <strong>${winnerData.risk_adjusted_return}</strong> 
            dalam periode yang dipilih.
        `;
        
        document.getElementById('winnerText').innerHTML = winnerText;
        document.getElementById('winnerAlert').style.display = 'block';
    }
}

// Formatting Functions
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function formatCurrency(value) {
    return 'Rp ' + formatNumber(value);
}

function formatVolume(value) {
    if (value >= 1000000000) {
        return (value / 1000000000).toFixed(2) + 'B';
    } else if (value >= 1000000) {
        return (value / 1000000).toFixed(2) + 'M';
    } else if (value >= 1000) {
        return (value / 1000).toFixed(2) + 'K';
    }
    return formatNumber(value);
}

function formatPercentage(value) {
    const color = value >= 0 ? 'value-positive' : 'value-negative';
    const icon = value >= 0 ? '▲' : '▼';
    return `<span class="${color}">${icon} ${Math.abs(value)}%</span>`;
}

function formatPriceChange(change, changePct) {
    const color = change >= 0 ? 'value-positive' : 'value-negative';
    const icon = change >= 0 ? '▲' : '▼';
    return `<span class="${color}">${icon} Rp ${formatNumber(Math.abs(change))} (${Math.abs(changePct)}%)</span>`;
}

function formatVolatility(value) {
    let color = 'value-neutral';
    if (value < 1) color = 'value-positive';
    else if (value > 2) color = 'value-negative';
    
    return `<span class="${color}">${value}%</span>`;
}

function formatRiskAdjusted(value) {
    const color = value >= 0 ? 'value-positive' : 'value-negative';
    return `<span class="${color}">${value}</span>`;
}
