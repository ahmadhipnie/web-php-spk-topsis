// Detail Charts - Grafik untuk Halaman Detail Saham
// Data akan di-inject dari PHP via global variable

document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah data saham tersedia (di-inject dari PHP)
    if (typeof sahamChartData === 'undefined') {
        console.error('Data saham tidak tersedia untuk chart');
        return;
    }

    // Chart 1: Kriteria Fundamental (Horizontal Bar Chart)
    const ctx1 = document.getElementById('criteriaChart');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['EPS', 'PER', 'ROE'],
                datasets: [{
                    label: 'Nilai',
                    data: [
                        sahamChartData.eps, 
                        sahamChartData.per, 
                        sahamChartData.roe
                    ],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',   // Green for EPS
                        'rgba(59, 130, 246, 0.8)',  // Blue for PER
                        'rgba(168, 85, 247, 0.8)'   // Purple for ROE
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed.x;
                                if (label === 'PER') {
                                    return label + ': ' + value.toFixed(2) + 'x';
                                } else if (label === 'ROE') {
                                    return label + ': ' + value.toFixed(2) + '%';
                                } else {
                                    return label + ': ' + value.toFixed(2);
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Data Harga (Vertical Bar Chart)
    const ctx2 = document.getElementById('priceChart');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Buka', 'Tertinggi', 'Terendah', 'Tutup'],
                datasets: [{
                    label: 'Harga (Rp)',
                    data: [
                        sahamChartData.hargaBuka, 
                        sahamChartData.hargaTertinggi, 
                        sahamChartData.hargaTerendah, 
                        sahamChartData.hargaTutup
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',   // Blue for Open
                        'rgba(34, 197, 94, 0.8)',    // Green for High
                        'rgba(239, 68, 68, 0.8)',    // Red for Low
                        'rgba(168, 85, 247, 0.8)'    // Purple for Close
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                        'rgb(168, 85, 247)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                // Format yang lebih akurat berdasarkan rentang nilai
                                if (value >= 1000000) {
                                    // Jika >= 1 juta, tampilkan dalam juta (misal: 1.5jt)
                                    return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                                } else if (value >= 1000) {
                                    // Jika >= 1000, tampilkan dalam ribuan dengan 1 desimal (misal: 5.5k)
                                    return 'Rp ' + (value/1000).toFixed(1) + 'k';
                                } else {
                                    // Jika < 1000, tampilkan langsung
                                    return 'Rp ' + value.toFixed(0);
                                }
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });
    }
});
