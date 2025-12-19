<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge-platform mb-3">
                    <i class="fas fa-chart-pie me-2"></i>Platform Rekomendasi Saham Terpercaya
                </span>
                <h1 class="hero-title">Investasi Cerdas dari <span class="highlight">Analisis</span> untuk <span class="highlight">Pemula</span></h1>
                <p class="hero-description">Dapatkan rekomendasi saham yang dipersonalisasi berdasarkan profil risiko, budget dan tujuan investasi Anda. Mulai perjalanan investasi dengan langkah yang tepat.</p>
                
                <div class="hero-buttons">
                    <a href="<?= BASE_URL; ?>topsis" class="btn btn-cta-primary">
                        <i class="fas fa-rocket me-2"></i>Dapatkan Rekomendasi Gratis
                    </a>
                    <a href="<?= BASE_URL; ?>about" class="btn btn-cta-secondary">
                        <i class="fas fa-play-circle me-2"></i>Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="<?= ASSETS_URL; ?>images/stock-chart.jpg" alt="Stock Chart" class="img-fluid">
                        <div class="profit-badge">
                            <span>Profit Bulanan</span>
                            <strong>+25.5%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Market Overview Stats - Separate Row -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="hero-stats">
                    <?php if (isset($data['marketOverview']) && $data['marketOverview']): 
                        $market = $data['marketOverview'];
                    ?>
                    <!-- Last Update Date -->
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Update Terakhir</p>
                            <h3 class="stat-value"><?= $market['last_update_formatted']; ?></h3>
                        </div>
                    </div>
                    
                    <!-- Total Stocks Monitored -->
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Saham Dipantau</p>
                            <h3 class="stat-value"><?= $market['stock_count']; ?> Saham</h3>
                        </div>
                    </div>
                    
                    <!-- Top Gainer -->
                    <div class="stat-item stat-gainer">
                        <div class="stat-icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Top Gainer</p>
                            <h3 class="stat-value">
                                <?php if ($market['top_gainer']): ?>
                                    <?= $market['top_gainer']['kode_saham']; ?>
                                    <span class="stat-change text-success">+<?= $market['top_gainer']['return']; ?>%</span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </h3>
                        </div>
                    </div>
                    
                    <!-- Top Loser -->
                    <div class="stat-item stat-loser">
                        <div class="stat-icon">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Top Loser</p>
                            <h3 class="stat-value">
                                <?php if ($market['top_loser']): ?>
                                    <?= $market['top_loser']['kode_saham']; ?>
                                    <span class="stat-change text-danger"><?= $market['top_loser']['return']; ?>%</span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </h3>
                        </div>
                    </div>
                    
                    <!-- Market Average (Full Width) -->
                    <div class="stat-item stat-market-avg">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Kondisi Pasar (<?= $market['period']; ?>)</p>
                            <h3 class="stat-value">
                                <span class="<?= $market['sentiment_class']; ?>">
                                    <?= $market['market_sentiment']; ?>
                                </span>
                                <span class="stat-subtitle">Rata-rata Return: 
                                    <strong class="<?= $market['average_return'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                        <?= $market['average_return']; ?>%
                                    </strong>
                                </span>
                            </h3>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    <!-- Fallback jika data tidak tersedia -->
                    <div class="stat-item">
                        <h3>-</h3>
                        <p>Data Tidak Tersedia</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Trust Indicators -->
    <div class="trust-indicators">
        <div class="container">
            <div class="trust-items">
                <span><i class="fas fa-check-circle"></i> Data Terpercaya</span>
                <span><i class="fas fa-check-circle"></i> Rekomendasi Akurat</span>
                <span><i class="fas fa-check-circle"></i> Metode TOPSIS Terverifikasi</span>
            </div>
        </div>
    </div>
</section>

<!-- Stock Chart Section -->
<section class="stock-chart-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-badge"><i class="fas fa-chart-line me-2"></i>Monitor Pasar Real-time</span>
            <h2 class="section-title">Pergerakan Harga Saham</h2>
            <p class="section-description">Pantau pergerakan harga saham favorit Anda dengan grafik interaktif dan data historis lengkap</p>
        </div>

        <!-- Chart Controls -->
        <div class="chart-controls">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6 mb-3">
                    <label class="control-label"><i class="fas fa-building me-2"></i>Pilih Saham</label>
                    <select id="stockSelect" class="form-select form-select-lg">
                        <option value="BBCA">BBCA - Bank Central Asia</option>
                        <!-- Will be populated via JavaScript -->
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <label class="control-label"><i class="fas fa-calendar-alt me-2"></i>Rentang Waktu</label>
                    <select id="periodeSelect" class="form-select form-select-lg">
                        <option value="7">7 Hari</option>
                        <option value="30" selected>30 Hari (1 Bulan)</option>
                        <option value="90">90 Hari (3 Bulan)</option>
                        <option value="180">180 Hari (6 Bulan)</option>
                        <option value="365">365 Hari (1 Tahun)</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-12 mb-3">
                    <label class="control-label">&nbsp;</label>
                    <div class="d-grid gap-2 d-md-flex">
                        <button id="downloadBtn" class="btn btn-download-data me-md-2 flex-fill">
                            <i class="fas fa-download me-2"></i>Download Data CSV
                        </button>
                        <button id="btnUpdateData" class="btn btn-outline-primary flex-fill" data-bs-toggle="modal" data-bs-target="#modalUpdateData">
                            <i class="fas fa-sync-alt me-2"></i>Update Data Saham
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="chart-container-wrapper">
            <!-- Loading Overlay -->
            <div id="chartLoading" class="chart-loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Memuat data saham...</p>
            </div>

            <!-- Candlestick Chart (Harga) -->
            <div class="chart-box">
                <h5 class="chart-box-title">
                    <i class="fas fa-chart-candlestick me-2"></i>Grafik Harga Saham (OHLC)
                    <span class="badge bg-info ms-2">Candlestick</span>
                </h5>
                <div id="priceChart"></div>
            </div>

            <!-- Volume Chart -->
            <div class="chart-box mt-4">
                <h5 class="chart-box-title">
                    <i class="fas fa-chart-bar me-2"></i>Volume Transaksi
                    <span class="badge bg-success ms-2">Bar Chart</span>
                </h5>
                <div id="volumeChart"></div>
            </div>

            <!-- Chart Info -->
            <div class="chart-info mt-3">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-label">Total Data</span>
                            <strong id="infoTotalData" class="info-value">-</strong>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-label">Harga Tertinggi</span>
                            <strong id="infoHighest" class="info-value">-</strong>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-label">Harga Terendah</span>
                            <strong id="infoLowest" class="info-value">-</strong>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-label">Perubahan</span>
                            <strong id="infoChange" class="info-value">-</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sector Analysis Section -->
<section class="sector-analysis-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-badge"><i class="fas fa-layer-group me-2"></i>Analisis Sektor</span>
            <h2 class="section-title">Perbandingan Performa Antar Sektor</h2>
            <p class="section-description">Lihat sektor mana yang paling menguntungkan dan saham terbaik di setiap sektor</p>
        </div>

        <!-- Period Filter -->
        <div class="sector-controls">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <label class="control-label"><i class="fas fa-calendar-alt me-2"></i>Periode Analisis</label>
                    <select id="sectorPeriodeSelect" class="form-select form-select-lg">
                        <option value="7">7 Hari Terakhir</option>
                        <option value="30" selected>30 Hari Terakhir</option>
                        <option value="90">90 Hari Terakhir</option>
                        <option value="365">1 Tahun Terakhir</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Sector Performance Chart -->
        <div class="sector-chart-wrapper">
            <!-- Loading Overlay -->
            <div id="sectorLoading" class="chart-loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Menganalisis performa sektor...</p>
            </div>

            <div class="chart-box">
                <h5 class="chart-box-title">
                    <i class="fas fa-chart-bar me-2"></i>Rata-rata Return Per Sektor
                    <span class="badge bg-primary ms-2">Bar Chart</span>
                </h5>
                <div id="sectorPerformanceChart"></div>
            </div>
        </div>

        <!-- Top Stocks by Sector -->
        <div class="sector-stocks-wrapper mt-5">
            <h4 class="mb-4 text-center">
                <i class="fas fa-trophy me-2"></i>Top 3 Saham Terbaik Per Sektor
            </h4>
            <div id="sectorStocksGrid" class="row g-4">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-badge"><i class="fas fa-star me-2"></i>Fitur Unggulan</span>
            <h2 class="section-title">Mengapa Memilih SahamPintar?</h2>
            <p class="section-description">Platform all-in-one yang dirancang khusus untuk membantu investor pemula mengambil keputusan investasi dengan lebih cerdas dan terinformasi.</p>
        </div>
        
        <div class="row g-4 mt-4">
            <!-- Feature 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-blue">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5>Analisis Risiko Personal</h5>
                    <p>Sistem mengevaluasi profil risiko Anda secara komprehensif untuk memberikan skor investasi informasi dalam bentuk peringkat saham terbaik.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Profil risiko terintegrasi</li>
                        <li><i class="fas fa-check"></i> Skor investasi transparan</li>
                    </ul>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-green">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5>Data & Grafik Real-Time</h5>
                    <p>Akses analisis saham dengan melihat analisis fundamental yang telah diperhitungkan dengan perhitungan yang akurat, valid dan terpercaya.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Analisis harga realtime</li>
                        <li><i class="fas fa-check"></i> Analisis fundamental akurat</li>
                    </ul>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-purple">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5>Edukasi Investasi</h5>
                    <p>Selain hanya rekomendasi, kami juga memberikan panduan edukasi lengkap tentang pasar saham agar Anda bisa terus belajar dan berkembang.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Panduan mudah dipahami</li>
                        <li><i class="fas fa-check"></i> Tips investasi terkini</li>
                    </ul>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-orange">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <h5>Diversifikasi Portfolio</h5>
                    <p>Rekomendasi diversifikasi berbagai jenis saham untuk membantu meminimalkan risiko investasi Anda.</p>
                </div>
            </div>
            
            <!-- Feature 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-teal">
                        <i class="fas fa-search-dollar"></i>
                    </div>
                    <h5>Rekomendasi Investasi</h5>
                    <p>AI-powered recommendation engine memberikan saran saham terbaik berdasarkan data lengkap dan perspektif.</p>
                </div>
            </div>
            
            <!-- Feature 6 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-red">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h5>Target Finansial</h5>
                    <p>Tetapkan target investasi dan kami akan memberikan rekomendasi saham dengan proyeksi keuntungan yang realistis.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Steps Section -->
<section class="steps-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-badge"><i class="fas fa-list-ol me-2"></i>Langkah</span>
            <h2 class="section-title">3 Langkah Mudah Investasi Cerdas</h2>
            <p class="section-description">Proses yang sederhana dan cepat untuk mulai mendapatkan rekomendasi dan analisis saham terbaik.</p>
        </div>
        
        <div class="row g-4 mt-4">
            <!-- Step 1 -->
            <div class="col-lg-4">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5>Isi Profil Investor</h5>
                    <p>Tentukan profil risiko, pengalaman investasi, modal awal, target keuangan dan prioritas saham.</p>
                    <a href="<?= BASE_URL; ?>saham" class="step-link">Mulai <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="col-lg-4">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5>Analisis Otomatis</h5>
                    <p>Sistem akan memproses data saham dan mencocokannya dengan kriteria fundamental yang telah ditentukan.</p>
                    <a href="<?= BASE_URL; ?>topsis" class="step-link">Analisis <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="col-lg-4">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5>Terima Rekomendasi</h5>
                    <p>Dapatkan daftar saham terbaik lengkap dengan analisis dan kesimpulan berdasarkan peringkat TOPSIS.</p>
                    <a href="<?= BASE_URL; ?>topsis/hasil" class="step-link">Lihat Hasil <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Cards Section -->
<section class="info-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="info-card info-card-blue">
                    <div class="info-image">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h5>Perbandingan Kinerja Saham</h5>
                    <p>Bandingkan performa berbagai saham secara visual dengan grafik interaktif dan data historis.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="info-card info-card-purple">
                    <div class="info-image">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h5>Analisis Dengan Metode TOPSIS</h5>
                    <p>Teknologi metode TOPSIS menganalisis ribuan data untuk memberikan rekomendasi yang akurat dan terpercaya.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2>Siap Memulai Investasi Cerdas?</h2>
        <p>Bergabunglah dengan ribuan investor yang telah mempercayakan analisis saham mereka kepada SahamPintar.</p>
        <a href="<?= BASE_URL; ?>topsis" class="btn btn-cta-white">
            <i class="fas fa-rocket me-2"></i>Mulai Sekarang - Gratis!
        </a>
    </div>
</section>
<!-- Set BASE_URL for JavaScript -->
<script>
    const BASE_URL = '<?= BASE_URL; ?>';
    const ASSETS_URL = '<?= ASSETS_URL; ?>';
</script>

<!-- ApexCharts Library -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>

<!-- Stock Chart Script -->
<script src="<?= ASSETS_URL; ?>js/stock-chart.js"></script>

<!-- Sector Analysis Script -->
<script src="<?= ASSETS_URL; ?>js/sector-analysis.js"></script>

<!-- Update Data Modal -->
<div class="modal fade" id="modalUpdateData" tabindex="-1" aria-labelledby="modalUpdateDataLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalUpdateDataLabel">
          <i class="fas fa-sync-alt me-2"></i>Update Data Saham Harian
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Content will be implemented later -->
        <p class="text-muted">Ketika melakukan update data saham mohon jangan menutup menu ini.</p>



        <!-- Update status and progress -->
        <div id="updateStatus" class="alert d-none" role="alert"></div>

        <div id="updateProgress" class="d-none mt-3">
            <div class="progress">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%">0%</div>
            </div>
            <p id="progressText" class="mt-2 small text-muted">Menunggu...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" id="btnDoUpdateAll" class="btn btn-primary">
          <i class="fas fa-sync-alt me-2"></i>Update Semua Saham
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const doBtn = document.getElementById('btnDoUpdateAll');
    if (!doBtn) return;

    doBtn.addEventListener('click', function() {
        const btn = this;
        
        // Helper to ensure update UI elements exist
        function ensureUpdateElements() {
            let modalBody = document.querySelector('#modalUpdateData .modal-body');
            if (!modalBody) modalBody = document.body;
            
            let s = document.getElementById('updateStatus');
            if (!s) {
                s = document.createElement('div');
                s.id = 'updateStatus';
                s.className = 'alert d-none';
                s.setAttribute('role','alert');
                modalBody.insertBefore(s, modalBody.firstChild);
                console.log('Created updateStatus dynamically');
            }
            
            let pDiv = document.getElementById('updateProgress');
            if (!pDiv) {
                pDiv = document.createElement('div');
                pDiv.id = 'updateProgress';
                pDiv.className = 'mt-3';
                pDiv.innerHTML = `
                    <div class="progress">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%">0%</div>
                    </div>
                    <p id="progressText" class="mt-2 small text-muted">Menunggu...</p>
                `;
                modalBody.insertBefore(pDiv, s.nextSibling);
                console.log('Created updateProgress dynamically');
            }
            
            const pBar = document.getElementById('progressBar');
            const pText = document.getElementById('progressText');
            return {statusBox: s, progressDiv: pDiv, progressBar: pBar, progressText: pText};
        }
        
        // Utility escape HTML for safe display
        function escapeHtml(s) {
            return String(s).replace(/[&<>"']/g, function(c) {
                return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
            });
        }
        
        const els = ensureUpdateElements();
        const statusBox = els.statusBox;
        const progressDiv = els.progressDiv;
        const progressBar = els.progressBar;
        const progressText = els.progressText;
        
        // Disable button
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sedang Update...';
        
        // Show progress
        progressDiv.classList.remove('d-none');
        statusBox.classList.add('d-none');
        
        // Simulate progress
        let progress = 0;
        let interval = setInterval(() => {
            if (progress < 90) {
                progress += 10;
                progressBar.style.width = progress + '%';
                progressBar.textContent = progress + '%';
                progressText.textContent = 'Mengambil data saham... ' + progress + '%';
            }
        }, 3000);
        
        const formData = new FormData();
        formData.append('mode', 'all');
        // Append dry_run if checkbox checked
        const dryRunCheck = document.getElementById('updateDryRun');
        if (dryRunCheck && dryRunCheck.checked) formData.append('dry_run', '1');
        
        const base = (window.BASEURL || '<?= BASE_URL ?>');
        const endpoints = [
            base + '/public/ajax_update_stock.php',
        ];
        
        console.log('Trying endpoints:', endpoints);
        
        // Try first endpoint
        fetch(endpoints[0], {
            method: 'POST',
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                console.warn('First endpoint failed with status:', res.status);
                // Try fallback
                return fetch(endpoints[1], {
                    method: 'POST',
                    body: formData
                })
                .then(res2 => {
                    if (!res2.ok) {
                        return res2.text().then(t => {
                            throw new Error('Both endpoints failed. Primary: ' + res.status + ', Fallback: ' + res2.status);
                        });
                    }
                    console.log('Fallback endpoint succeeded');
                    return res2;
                });
            }
            console.log('Primary endpoint succeeded');
            return res;
        })
        .then(res => res.text())
        .then(text => {
            // Stop fake progress
            clearInterval(interval);
            progressBar.style.width = '100%';
            progressBar.textContent = '100%';
            progressDiv.classList.add('d-none');
            
            console.log('Raw response from server:', text);
            
            // Try parse JSON - STRIP non-JSON prefix/suffix
            let data = null;
            try {
                // Clean response - remove any non-JSON content before/after
                let cleanText = text.trim();
                
                // Find first { and last }
                const firstBrace = cleanText.indexOf('{');
                const lastBrace = cleanText.lastIndexOf('}');
                
                if (firstBrace !== -1 && lastBrace !== -1 && lastBrace > firstBrace) {
                    cleanText = cleanText.substring(firstBrace, lastBrace + 1);
                    console.log('Cleaned JSON:', cleanText);
                    data = JSON.parse(cleanText);
                } else {
                    throw new Error('No valid JSON found in response');
                }
            } catch(e) {
                console.error('Failed to parse JSON:', e);
                console.error('Raw output:', text);
                
                statusBox.className = 'alert alert-danger';
                statusBox.classList.remove('d-none');
                statusBox.innerHTML = '<strong>Gagal:</strong> gagal parsing output dari python script<br>' +
                    '<small>Kemungkinan ada error atau output debug dari server</small>' +
                    '<pre style="max-height:200px;overflow:auto;background:#f8f9fa;padding:8px;border-radius:4px;margin-top:8px;">' + 
                    escapeHtml(text) + '</pre>';
                
                // Re-enable button
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Update Semua Saham';
                return;
            }
            
            // If parsed successfully, handle normally
            if (data && data.success) {
                statusBox.className = 'alert alert-success';
                statusBox.classList.remove('d-none');
                statusBox.innerHTML = 
                    '<h6><i class="fas fa-check-circle me-2"></i>Update Berhasil!</h6>' +
                    '<ul class="mb-0 mt-2">' +
                    '<li>Total Saham: <strong>' + data.total_stocks + '</strong></li>' +
                    '<li>Berhasil Scrape: <strong>' + data.scraped + '</strong></li>' +
                    '<li>Data Baru: <strong>' + data.inserted + '</strong></li>' +
                    '<li>Data Diupdate: <strong>' + data.updated + '</strong></li>' +
                    (data.failed > 0 ? '<li class="text-danger">Gagal: <strong>' + data.failed + '</strong></li>' : '') +
                    '<li>Waktu: <strong>' + data.timestamp + '</strong></li>' +
                    '</ul>';
                
                // Close modal and refresh page after 3 seconds
                setTimeout(() => {
                    // Close modal
                    const modal = document.getElementById('modalUpdateData');
                    if (modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) {
                            bsModal.hide();
                        }
                    }
                    
                    // Refresh page to show updated data
                    window.location.reload();
                }, 3000);
            } else {
                statusBox.className = 'alert alert-danger';
                statusBox.classList.remove('d-none');
                statusBox.innerHTML = '<strong>Gagal:</strong> ' + 
                    (data && data.message ? data.message : 'Terjadi kesalahan.');
                
                if (data && data.raw) {
                    statusBox.innerHTML += '<pre style="max-height:200px;overflow:auto;background:#f8f9fa;padding:8px;border-radius:4px;margin-top:8px;">' + 
                        escapeHtml(data.raw) + '</pre>';
                }
            }
            
            // Enable button
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Update Semua Saham';
        })
        .catch(err => {
            console.error('Post failed:', err);
            
            clearInterval(interval);
            progressDiv.classList.add('d-none');
            
            statusBox.className = 'alert alert-danger';
            statusBox.classList.remove('d-none');
            statusBox.innerHTML = '<strong>Gagal:</strong> ' + escapeHtml(err.message || String(err));
            
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Update Semua Saham';
        });
    });
});
</script>


