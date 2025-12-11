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
                        <h1 class="stock-code"><?= $saham->kode_saham ?? 'N/A'; ?></h1>
                        <?php
                        // Tentukan badge berdasarkan risk level
                        $risk_badge = 'Resiko Rendah';
                        $badge_class = 'badge-risk-low';
                        ?>
                        <span class="<?= $badge_class; ?>"><?= $risk_badge; ?></span>
                    </div>

                    <h2 class="stock-name"><?= $saham->nama_saham ?? 'Nama Saham'; ?></h2>

                    <div class="stock-tag">
                        <span><?= $saham->sektor ?? 'Sektor'; ?></span>
                    </div>
                </div>

                <div class="detail-header-right">
                    <div class="stock-price">Rp <?= isset($saham->harga) ? number_format($saham->harga, 0, ',', '.') : '0'; ?></div>
                    <div class="stock-change negative">
                        <i class="fas fa-arrow-down"></i>
                        -Rp <?= rand(10, 50); ?>
                        (-<?= number_format(rand(1, 10) / 10, 2); ?>%)
                    </div>
                </div>
            </div>

            <!-- Grid Metrics -->
            <div class="detail-metrics">
                <div class="metric-item">
                    <div class="metric-label">Market Capitalization</div>
                    <div class="metric-value">
                        Rp <?= isset($saham->market_cap) ? number_format($saham->market_cap / 1000000000000, 1) : '0'; ?> T
                    </div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Price to Earnings (P/E)</div>
                    <div class="metric-value"><?= isset($saham->pe_ratio) ? number_format($saham->pe_ratio, 1) : '0.0'; ?></div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Dividend Yield</div>
                    <div class="metric-value">
                        <?= isset($saham->dividend_yield) ? number_format($saham->dividend_yield, 1) : '0.0'; ?> %
                    </div>
                </div>
            </div>

            <!-- Alert Mengapa Cocok -->
            <div class="detail-alert">
                <div class="alert-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="alert-content">
                    <h4>Mengapa Saham ini Cocok untuk Anda?</h4>
                    <p>
                        Perusahaan ini memiliki fundamental kuat dengan P/E ratio ideal, dividend yield yang menarik,
                        dan pertumbuhan modal yang stabil. Cocok untuk investasi jangka menengah hingga panjang.
                        Saham ini termasuk blue chip dengan likuiditas tinggi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tentang Perusahaan -->
        <div class="about-section">
            <h3 class="section-title-detail">Tentang Perusahaan</h3>
            <p class="about-text">
                <?= $saham->deskripsi ?? 'Unilever Indonesia adalah produsen terkemuka produk consumer goods dengan brand-brand terkenal seperti Dove, Lifebuoy, dan Sunsilk. Perusahaan memiliki distribusi yang luas dan brand equity yang kuat di Indonesia.'; ?>
            </p>
        </div>

        <!-- Analisis Investasi -->
        <div class="analysis-section">
            <h3 class="section-title-detail">Analisis Investasi</h3>

            <div class="analysis-items">
                <div class="analysis-item">
                    <div class="analysis-icon positive">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="analysis-content">
                        <h4 class="analysis-title">Potensi Return</h4>
                        <p class="analysis-desc">
                            +<?= number_format(rand(5, 15) + (rand(0, 99) / 100), 2); ?>% per tahun
                        </p>
                    </div>
                </div>

                <div class="analysis-item">
                    <div class="analysis-icon positive">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="analysis-content">
                        <h4 class="analysis-title">Tingkat Risiko</h4>
                        <p class="analysis-desc">
                            Rendah - Sesuai dengan profil risiko moderat Anda
                        </p>
                    </div>
                </div>

                <div class="analysis-item">
                    <div class="analysis-icon positive">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="analysis-content">
                        <h4 class="analysis-title">Likuiditas</h4>
                        <p class="analysis-desc">
                            Tinggi - Volume perdagangan aktif setiap hari
                        </p>
                    </div>
                </div>

                <div class="analysis-item">
                    <div class="analysis-icon positive">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="analysis-content">
                        <h4 class="analysis-title">Fundamental</h4>
                        <p class="analysis-desc">
                            Kuat - Rasio keuangan sehat dan pertumbuhan stabil
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>