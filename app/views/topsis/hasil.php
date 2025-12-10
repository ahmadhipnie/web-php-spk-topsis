<!-- Hasil Perhitungan TOPSIS -->
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="fas fa-chart-line"></i> Hasil Perhitungan TOPSIS</h2>
        <hr>
    </div>
</div>

<!-- Kriteria & Bobot -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Kriteria & Bobot yang Digunakan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6>Harga</h6>
                            <h4 class="text-primary"><?= $kriteria['harga']['bobot'] ?? 0; ?></h4>
                            <span class="badge bg-<?= ($kriteria['harga']['tipe'] ?? 'cost') == 'cost' ? 'danger' : 'success'; ?>">
                                <?= strtoupper($kriteria['harga']['tipe'] ?? 'cost'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6>Volume</h6>
                            <h4 class="text-success"><?= $kriteria['volume']['bobot'] ?? 0; ?></h4>
                            <span class="badge bg-<?= ($kriteria['volume']['tipe'] ?? 'benefit') == 'cost' ? 'danger' : 'success'; ?>">
                                <?= strtoupper($kriteria['volume']['tipe'] ?? 'benefit'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6>Market Cap</h6>
                            <h4 class="text-warning"><?= $kriteria['market_cap']['bobot'] ?? 0; ?></h4>
                            <span class="badge bg-<?= ($kriteria['market_cap']['tipe'] ?? 'benefit') == 'cost' ? 'danger' : 'success'; ?>">
                                <?= strtoupper($kriteria['market_cap']['tipe'] ?? 'benefit'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Matriks Keputusan -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-table"></i> Matriks Keputusan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Alternatif</th>
                                <th>Kode</th>
                                <th>Nama Saham</th>
                                <th>Harga (C1)</th>
                                <th>Volume (C2)</th>
                                <th>Market Cap (C3)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($saham)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($saham as $s) : ?>
                                    <tr>
                                        <td><strong>A<?= $no++; ?></strong></td>
                                        <td><?= $s->kode_saham; ?></td>
                                        <td><?= $s->nama_saham; ?></td>
                                        <td>Rp <?= number_format($s->harga, 0, ',', '.'); ?></td>
                                        <td><?= number_format($s->volume, 0, ',', '.'); ?></td>
                                        <td>Rp <?= number_format($s->market_cap, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hasil Ranking (Placeholder) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-trophy"></i> Hasil Ranking Saham</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> Perhitungan TOPSIS belum diimplementasikan secara lengkap.
                    Ini adalah template tampilan hasil. Implementasi algoritma TOPSIS dapat dilakukan di TopsisHelper.php
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-success">
                            <tr>
                                <th>Ranking</th>
                                <th>Kode Saham</th>
                                <th>Nama Saham</th>
                                <th>Nilai Preferensi (V)</th>
                                <th>Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($saham)) : ?>
                                <?php
                                $rank = 1;
                                // Placeholder: Urutkan berdasarkan market_cap untuk demo
                                usort($saham, function ($a, $b) {
                                    return $b->market_cap <=> $a->market_cap;
                                });
                                ?>
                                <?php foreach ($saham as $s) : ?>
                                    <tr>
                                        <td>
                                            <h5>
                                                <?php if ($rank == 1): ?>
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-crown"></i> #<?= $rank; ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">#<?= $rank; ?></span>
                                                <?php endif; ?>
                                            </h5>
                                        </td>
                                        <td><strong><?= $s->kode_saham; ?></strong></td>
                                        <td><?= $s->nama_saham; ?></td>
                                        <td>
                                            <span class="badge bg-info">0.000</span>
                                            <small class="text-muted">(Placeholder)</small>
                                        </td>
                                        <td>
                                            <?php if ($rank == 1): ?>
                                                <span class="badge bg-success">Sangat Direkomendasikan</span>
                                            <?php elseif ($rank == 2): ?>
                                                <span class="badge bg-primary">Direkomendasikan</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Pertimbangkan</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $rank++; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tombol Aksi -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <a href="<?= BASE_URL; ?>topsis" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> Hitung Ulang
            </a>
            <button class="btn btn-primary btn-lg" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak Hasil
            </button>
        </div>
    </div>
</div>