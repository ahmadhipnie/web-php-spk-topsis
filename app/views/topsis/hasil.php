<!-- Hasil Perhitungan TOPSIS - Modern Design -->

<!-- Hero Header dengan Kriteria -->
<section class="hasil-hero">
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <a href="<?= BASE_URL; ?>" class="back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

        <div class="hero-card-gradient">
            <div class="row align-items-center">
                <div class="col-lg-12 mb-3">
                    <h1 class="hero-title">Rekomendasi Saham Anda</h1>
                    <p class="hero-subtitle">Berdasarkan kriteria yang Anda pilih</p>
                </div>
            </div>

            <!-- Kriteria Info Cards -->
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Budget Investasi</div>
                        <div class="kriteria-value">
                            Rp <?= isset($kriteria['budget']) ? number_format($kriteria['budget'], 0, ',', '.') : '10.000.000'; ?>
                        </div>
                        <div class="kriteria-detail">Dana yang dialokasikan</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Profil Risiko</div>
                        <div class="kriteria-value">
                            <?= isset($kriteria['profil_risiko']) ? ucfirst($kriteria['profil_risiko']) : 'Moderat'; ?>
                        </div>
                        <div class="kriteria-detail">Tingkat toleransi risiko</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Jangka Waktu</div>
                        <div class="kriteria-value">
                            <?= isset($kriteria['jangka_waktu']) ? ucfirst($kriteria['jangka_waktu']) : 'Menengah'; ?>
                        </div>
                        <div class="kriteria-detail">Periode investasi</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Sektor</div>
                        <div class="kriteria-value">
                            <?= isset($kriteria['sektor']) ? ucfirst($kriteria['sektor']) : 'Semua'; ?>
                        </div>
                        <div class="kriteria-detail">Preferensi sektor saham</div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Hasil Rekomendasi -->
<section class="hasil-rekomendasi">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?= !empty($saham) ? count($saham) : '0'; ?> Saham Direkomendasikan</h2>
            <p class="section-description">
                Berdasarkan profil investasi Anda, berikut adalah saham-saham yang paling sesuai dengan
                kriteria yang telah ditentukan. Anda dapat melihat detail lebih lanjut untuk setiap saham.
            </p>
        </div>

        <!-- List Saham Cards -->
        <div class="row">
            <?php if (!empty($saham)) : ?>
                <?php
                // Sort saham by ranking (placeholder - sesuaikan dengan logic TOPSIS Anda)
                usort($saham, function ($a, $b) {
                    return $b->market_cap <=> $a->market_cap;
                });
                $rank = 1;
                ?>

                <?php foreach ($saham as $s) : ?>
                    <div class="col-12 mb-3">
                        <div class="saham-card">
                            <!-- Header Card -->
                            <div class="saham-card-header">
                                <div class="saham-badge-container">
                                    <?php if ($rank == 1): ?>
                                        <span class="badge-best">Resiko Rendah</span>
                                    <?php elseif ($rank <= 3): ?>
                                        <span class="badge-good">Resiko Sedang</span>
                                    <?php else: ?>
                                        <span class="badge-normal">Pertimbangkan</span>
                                    <?php endif; ?>

                                    <div class="ranking-number">#<?= $rank; ?></div>
                                </div>

                                <div class="saham-info">
                                    <h3 class="saham-code"><?= $s->kode_saham; ?></h3>
                                    <p class="saham-name"><?= $s->nama_saham; ?></p>
                                    <span class="saham-tag">Perbankan</span>
                                </div>

                                <div class="saham-score">
                                    <div class="score-label">Skor TOPSIS</div>
                                    <div class="score-value">
                                        <?php
                                        // Placeholder score - ganti dengan hasil TOPSIS sebenarnya
                                        $score = number_format(0.95 - ($rank * 0.05), 3);
                                        echo $score;
                                        ?>
                                    </div>
                                    <div class="score-trend">
                                        <i class="fas fa-arrow-up"></i> <?= rand(1, 5); ?>%
                                    </div>
                                </div>
                            </div>

                            <!-- Alert Info -->
                            <?php if ($rank <= 2): ?>
                                <div class="saham-alert">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Mengapa direkomendasikan?</strong>
                                    Saham memiliki fundamental kuat dengan P/E ratio ideal. Dividend yield menarik dan
                                    pertumbuhan modal yang stabil. Cocok untuk investasi jangka menengah hingga panjang.
                                </div>
                            <?php endif; ?>

                            <!-- Detail Grid -->
                            <div class="saham-details">
                                <div class="detail-item">
                                    <div class="detail-label">Market Cap</div>
                                    <div class="detail-value">Rp <?= number_format($s->market_cap / 1000, 1); ?> T</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">P/E Ratio</div>
                                    <div class="detail-value"><?= isset($s->pe_ratio) ? number_format($s->pe_ratio, 1) : '20.0'; ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Dividend Yield</div>
                                    <div class="detail-value"><?= isset($s->dividend_yield) ? number_format($s->dividend_yield, 1) : '3.5'; ?> %</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Potensi Return 5.12% per tahun</div>
                                    <div class="detail-link">
                                        <a href="<?= BASE_URL; ?>topsis/detail/<?= $s->kode_saham; ?>">
                                            Lihat Detail <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $rank++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <h3>Belum Ada Data Saham</h3>
                        <p>Silakan tambahkan data saham terlebih dahulu atau lakukan perhitungan TOPSIS.</p>
                        <a href="<?= BASE_URL; ?>saham" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Tambah Data Saham
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>