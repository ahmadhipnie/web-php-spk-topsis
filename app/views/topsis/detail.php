<!-- Detail Saham - Halaman Detail -->

<!-- Back Button -->
<section class="detail-back">
    <div class="container">
        <a href="<?= BASE_URL; ?>topsis/hasil" class="back-link-detail">
            <i class="fas fa-arrow-left"></i> Kembali ke Hasil
        </a>
    </div>
</section>

<!-- Detail Card -->
<section class="detail-section">
    <div class="container">
        <div class="detail-card">
            <!-- Header Info -->
            <div class="detail-header">
                <div class="detail-header-left">
                    <div class="detail-badge-code">
                        <h1 class="stock-code"><?= htmlspecialchars($data['saham']->kode_saham); ?></h1>
                        <span class="badge-risk-low">Terverifikasi</span>
                    </div>

                    <h2 class="stock-name"><?= htmlspecialchars($data['saham']->nama_saham); ?></h2>

                    <div class="stock-tag">
                        <span><?= htmlspecialchars(ucfirst($data['saham']->sektor)); ?></span>
                    </div>
                </div>

                <div class="detail-header-right">
                    <div class="stock-price">Rp <?= number_format($data['saham']->harga_tutup, 0, ',', '.'); ?></div>
                    <div class="stock-change <?php 
                        $hargaBuka = floatval($data['saham']->harga_buka ?? 0);
                        $hargaTutup = floatval($data['saham']->harga_tutup ?? 0);
                        $kinerja = 0;
                        if ($hargaBuka > 0) {
                            $kinerja = (($hargaTutup - $hargaBuka) / $hargaBuka) * 100;
                        }
                        echo ($kinerja >= 0 ? 'positive' : 'negative'); 
                    ?>">
                        <i class="fas fa-arrow-<?= $kinerja >= 0 ? 'up' : 'down'; ?>"></i>
                        <?= number_format(abs($kinerja), 2); ?>%
                    </div>
                </div>
            </div>

            <!-- Grid Metrics -->
            <div class="detail-metrics">
                <div class="metric-item">
                    <div class="metric-label">EPS (Earnings Per Share)</div>
                    <div class="metric-value">
                        <?= number_format($data['saham']->EPS, 2); ?>
                    </div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">PER (Price Earnings Ratio)</div>
                    <div class="metric-value"><?= number_format($data['saham']->PER, 2); ?>x</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">ROE (Return on Equity)</div>
                    <div class="metric-value">
                        <?= number_format($data['saham']->ROE, 2); ?>%
                    </div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Harga Tutup per Lembar</div>
                    <div class="metric-value">
                        Rp <?= number_format($data['saham']->harga_tutup, 0, ',', '.'); ?>
                    </div>
                </div>
            </div>

            <!-- Alert Mengapa Cocok -->
            <div class="detail-alert">
                <div class="alert-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="alert-content">
                    <h4>Mengapa Saham ini Direkomendasikan?</h4>
                    <p>
                        Saham <strong><?= htmlspecialchars($data['saham']->kode_saham); ?></strong> memiliki kinerja fundamental yang solid 
                        dengan EPS <strong><?= number_format($data['saham']->EPS, 2); ?></strong>, 
                        PER <strong><?= number_format($data['saham']->PER, 2); ?>x</strong>, dan 
                        ROE <strong><?= number_format($data['saham']->ROE, 2); ?>%</strong>. 
                        <?php if ($kinerja > 0): ?>
                        Saham ini menunjukkan tren positif dengan pertumbuhan <strong><?= number_format($kinerja, 2); ?>%</strong>.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Tentang Perusahaan -->
        <div class="about-section">
            <h3 class="section-title-detail">Tentang Perusahaan</h3>
            <p class="about-text">
                <strong><?= htmlspecialchars($data['saham']->nama_saham); ?></strong> adalah perusahaan yang bergerak di sektor <strong><?= htmlspecialchars(ucfirst($data['saham']->sektor)); ?></strong> dengan kode saham <strong><?= htmlspecialchars($data['saham']->kode_saham); ?></strong>. Perusahaan ini memiliki fundamental yang solid dan merupakan pilihan investasi yang baik untuk investor dengan berbagai profil risiko.
            </p>
        </div>

        <!-- Analisis Investasi -->
        <div class="analysis-section">
            <h3 class="section-title-detail">Analisis Kriteria TOPSIS</h3>

            <div class="analysis-items">
                <div class="analysis-item">
                    <div class="analysis-icon positive">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="analysis-content">
                        <h4 class="analysis-title">EPS (Earnings Per Share)</h4>
                        <p class="analysis-desc">
                            <strong><?= number_format($data['saham']->EPS, 2); ?></strong> - 
                            <?php if ($data['saham']->EPS > 500): ?>
                            Sangat baik, menunjukkan profitabilitas yang tinggi
                            <?php elseif ($data['saham']->EPS > 200): ?>
                            Baik, laba per saham cukup stabil
                            <?php else: ?>
                            Cukup, masih dalam batas wajar
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="analysis-item">
                    <div class="analysis-icon positive">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="analysis-content">
                        <h4 class="analysis-title">PER (Price Earnings Ratio)</h4>
                        <p class="analysis-desc">
                            <strong><?= number_format($data['saham']->PER, 2); ?>x</strong> - 
                            <?php if ($data['saham']->PER < 15): ?>
                            Sangat menarik, valuasi rendah
                            <?php elseif ($data['saham']->PER < 25): ?>
                            Wajar, valuasi sesuai industri
                            <?php else: ?>
                            Tinggi, harga sudah cukup mahal
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="analysis-item">
                    <div class="analysis-icon positive">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="analysis-content">
                        <h4 class="analysis-title">ROE (Return on Equity)</h4>
                        <p class="analysis-desc">
                            <strong><?= number_format($data['saham']->ROE, 2); ?>%</strong> - 
                            <?php if ($data['saham']->ROE > 15): ?>
                            Sangat baik, efisiensi modal tinggi
                            <?php elseif ($data['saham']->ROE > 10): ?>
                            Baik, pengelolaan modal cukup efisien
                            <?php else: ?>
                            Cukup, masih perlu perbaikan
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="analysis-item">
                    <div class="analysis-icon <?= $kinerja >= 0 ? 'positive' : 'negative'; ?>">
                        <i class="fas fa-<?= $kinerja >= 0 ? 'check' : 'exclamation'; ?>-circle"></i>
                    </div>
                    <div class="analysis-content">
                        <h4 class="analysis-title">Kinerja Harga</h4>
                        <p class="analysis-desc">
                            <strong><?= number_format($kinerja, 2); ?>%</strong> - 
                            <?php if ($kinerja > 5): ?>
                            Tren naik signifikan
                            <?php elseif ($kinerja > 0): ?>
                            Tren naik moderat
                            <?php elseif ($kinerja > -5): ?>
                            Tren turun moderat
                            <?php else: ?>
                            Tren turun signifikan
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visualisasi Grafik -->
        <div class="chart-section">
            <h3 class="section-title-detail">Visualisasi Performa Saham</h3>
            
            <div class="row">
                <!-- Chart 1: Kriteria TOPSIS -->
                <div class="col-lg-6 mb-4">
                    <div class="chart-card">
                        <h4 class="chart-title">Kriteria Fundamental</h4>
                        <p class="chart-subtitle">Nilai EPS, PER, dan ROE</p>
                        <canvas id="criteriaChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Data Harga -->
                <div class="col-lg-6 mb-4">
                    <div class="chart-card">
                        <h4 class="chart-title">Pergerakan Harga Saham</h4>
                        <p class="chart-subtitle">Buka, Tertinggi, Terendah, Tutup</p>
                        <canvas id="priceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="detail-actions">
            <a href="<?= BASE_URL; ?>topsis/hasil" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Kembali ke Hasil
            </a>
            <a href="<?= BASE_URL; ?>topsis" class="btn btn-primary">
                <i class="fas fa-calculator"></i> Analisis Baru
            </a>
        </div>
    </div>
</section>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Data untuk Chart (Inject dari PHP) -->
<script>
const sahamChartData = {
    eps: <?= $data['saham']->EPS; ?>,
    per: <?= $data['saham']->PER; ?>,
    roe: <?= $data['saham']->ROE; ?>,
    hargaBuka: <?= $data['saham']->harga_buka; ?>,
    hargaTertinggi: <?= $data['saham']->harga_tertinggi; ?>,
    hargaTerendah: <?= $data['saham']->harga_terendah; ?>,
    hargaTutup: <?= $data['saham']->harga_tutup; ?>
};
</script>

<!-- Chart Initialization Script -->
<script src="<?= ASSETS_URL; ?>js/detail-charts.js"></script>
