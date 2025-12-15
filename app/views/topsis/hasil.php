<!-- Hasil Perhitungan TOPSIS - Modern Design -->

<!-- Hero Header dengan Kriteria -->
<section class="hasil-hero">
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <a href="<?= BASE_URL; ?>topsis" class="back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Form
                </a>
            </div>
        </div>

        <div class="hero-card-gradient">
            <div class="row align-items-center">
                <div class="col-lg-12 mb-3">
                    <h1 class="hero-title">Rekomendasi Saham untuk <?= htmlspecialchars($data['investorData']['nama_investor']); ?></h1>
                    <p class="hero-subtitle">Berdasarkan kriteria yang Anda pilih dengan metode TOPSIS</p>
                </div>
            </div>

            <!-- Kriteria Info Cards -->
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Budget Investasi</div>
                        <div class="kriteria-value">
                            Rp <?= number_format($data['investorData']['budget'], 0, ',', '.'); ?>
                        </div>
                        <div class="kriteria-detail">Dana yang dialokasikan</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Profil Risiko</div>
                        <div class="kriteria-value">
                            <?= ucfirst($data['investorData']['profil_risiko']); ?>
                        </div>
                        <div class="kriteria-detail">Tingkat toleransi risiko</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Jangka Waktu</div>
                        <div class="kriteria-value">
                            <?= ucfirst($data['investorData']['jangka_waktu']); ?>
                        </div>
                        <div class="kriteria-detail">Periode investasi</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Sektor</div>
                        <div class="kriteria-value">
                            <?= ucfirst($data['investorData']['sektor']); ?>
                        </div>
                        <div class="kriteria-detail">Preferensi sektor saham</div>
                    </div>
                </div>
            </div>

            <!-- Bobot Kriteria -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="kriteria-info-card">
                        <div class="kriteria-label">Bobot Kriteria yang Digunakan</div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="kriteria-detail">
                                    <strong>EPS (Benefit):</strong> <?= number_format($data['investorData']['bobot']['EPS'] * 100, 0); ?>%
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="kriteria-detail">
                                    <strong>PER (Cost):</strong> <?= number_format($data['investorData']['bobot']['PER'] * 100, 0); ?>%
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="kriteria-detail">
                                    <strong>ROE (Benefit):</strong> <?= number_format($data['investorData']['bobot']['ROE'] * 100, 0); ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hasil Rekomendasi -->
<section class="hasil-rekomendasi">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?= count($data['hasilTopsis']); ?> Saham Direkomendasikan</h2>
            <p class="section-description">
                Berdasarkan profil investasi Anda, berikut adalah saham-saham yang paling sesuai dengan
                kriteria yang telah ditentukan. Saham diurutkan berdasarkan skor TOPSIS tertinggi.
            </p>
        </div>

        <!-- List Saham Cards -->
        <div class="row">
            <?php if (!empty($data['hasilTopsis'])) : ?>
                <?php
                $rank = 1;
                foreach ($data['hasilTopsis'] as $hasil) :
                    $s = $hasil; // Hasil sudah termasuk data saham
                ?>
                    <div class="col-12 mb-3">
                        <div class="saham-card">
                            <!-- Header Card -->
                            <div class="saham-card-header">
                                <div class="saham-badge-container">
                                    <?php if ($rank == 1): ?>
                                        <span class="badge-best">Terbaik</span>
                                    <?php elseif ($rank <= 3): ?>
                                        <span class="badge-good">Direkomendasikan</span>
                                    <?php else: ?>
                                        <span class="badge-normal">Pertimbangkan</span>
                                    <?php endif; ?>

                                    <div class="ranking-number">#<?= $rank; ?></div>
                                </div>

                                <div class="saham-info">
                                    <h3 class="saham-code"><?= htmlspecialchars($s['kode_saham']); ?></h3>
                                    <p class="saham-name"><?= htmlspecialchars($s['nama_saham']); ?></p>
                                    <span class="saham-tag"><?= htmlspecialchars(ucfirst($s['sektor'])); ?></span>
                                </div>

                                <div class="saham-score">
                                    <div class="score-label">Skor TOPSIS</div>
                                    <div class="score-value">
                                        <?= number_format($s['skor'], 4); ?>
                                    </div>
                                    <div class="score-trend">
                                        <?php
                                        // Hitung kinerja dari harga_buka dan harga_tutup
                                        $hargaBuka = floatval($s['harga_buka'] ?? 0);
                                        $hargaTutup = floatval($s['harga_tutup'] ?? 0);
                                        $kinerja = 0;
                                        if ($hargaBuka > 0) {
                                            $kinerja = (($hargaTutup - $hargaBuka) / $hargaBuka) * 100;
                                        }
                                        
                                        if ($kinerja > 0) {
                                            echo '<i class="fas fa-arrow-up"></i> ' . number_format($kinerja, 2) . '%';
                                        } elseif ($kinerja < 0) {
                                            echo '<i class="fas fa-arrow-down"></i> ' . number_format(abs($kinerja), 2) . '%';
                                        } else {
                                            echo '<i class="fas fa-minus"></i> 0.00%';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Alert Info -->
                            <?php if ($rank <= 2): ?>
                                <div class="saham-alert">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Mengapa direkomendasikan?</strong>
                                    Saham ini memiliki skor TOPSIS tertinggi berdasarkan kriteria EPS, PER, dan ROE yang sesuai dengan profil risiko <strong><?= ucfirst($data['investorData']['profil_risiko']); ?></strong> Anda.
                                </div>
                            <?php endif; ?>

                            <!-- Detail Grid -->
                            <div class="saham-details">
                                <div class="detail-item">
                                    <div class="detail-label">EPS (Earnings Per Share)</div>
                                    <div class="detail-value"><?= number_format($s['eps'], 2); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">PER (Price Earnings Ratio)</div>
                                    <div class="detail-value"><?= number_format($s['per'], 2); ?>x</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">ROE (Return on Equity)</div>
                                    <div class="detail-value"><?= number_format($s['roe'], 2); ?>%</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Harga Saham (Tutup)</div>
                                    <div class="detail-value">Rp <?= number_format($s['harga_tutup'], 0, ',', '.'); ?></div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="saham-action">
                                <a href="<?= BASE_URL; ?>topsis/detail/<?= $s['id_saham']; ?>" class="btn-detail">
                                    Lihat Detail Perhitungan <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php $rank++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <h3>Belum Ada Hasil Perhitungan</h3>
                        <p>Silakan lakukan analisis TOPSIS terlebih dahulu untuk mendapatkan rekomendasi saham.</p>
                        <a href="<?= BASE_URL; ?>topsis" class="btn btn-primary mt-3">
                            <i class="fas fa-calculator"></i> Mulai Analisis
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <?php if (!empty($data['hasilTopsis'])): ?>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?= BASE_URL; ?>topsis" class="btn btn-outline-primary me-2">
                    <i class="fas fa-redo"></i> Analisis Baru
                </a>
                <a href="<?= BASE_URL; ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>