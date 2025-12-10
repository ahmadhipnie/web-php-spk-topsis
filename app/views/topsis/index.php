<!-- Perhitungan TOPSIS Page -->
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="fas fa-calculator"></i> Perhitungan TOPSIS</h2>
        <hr>
    </div>
</div>

<?php if (!empty($saham)) : ?>
    <form action="<?= BASE_URL; ?>topsis/hitung" method="POST">
        <!-- Pilih Saham -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-check-square"></i> Langkah 1: Pilih Saham yang Akan Dianalisis</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Pilih minimal 2 saham untuk dibandingkan
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAll" onclick="toggleAll(this)">
                                        </th>
                                        <th>Kode</th>
                                        <th>Nama Saham</th>
                                        <th>Harga</th>
                                        <th>Volume</th>
                                        <th>Market Cap</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($saham as $s) : ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="saham_ids[]" value="<?= $s->id; ?>" class="saham-checkbox">
                                            </td>
                                            <td><strong><?= $s->kode_saham; ?></strong></td>
                                            <td><?= $s->nama_saham; ?></td>
                                            <td>Rp <?= number_format($s->harga, 0, ',', '.'); ?></td>
                                            <td><?= number_format($s->volume, 0, ',', '.'); ?></td>
                                            <td>Rp <?= number_format($s->market_cap, 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Setting Kriteria & Bobot -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Langkah 2: Tentukan Bobot dan Tipe Kriteria</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Total bobot harus = 100% atau 1
                        </div>

                        <div class="row">
                            <!-- Kriteria Harga -->
                            <div class="col-md-4 mb-3">
                                <div class="card border-primary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Kriteria: Harga</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Bobot</label>
                                            <input type="number" step="0.01" class="form-control bobot-input" name="bobot_harga" value="0.30" min="0" max="1" required>
                                            <small class="text-muted">Rentang: 0 - 1</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tipe</label>
                                            <select name="tipe_harga" class="form-select">
                                                <option value="cost" selected>Cost (Semakin kecil semakin baik)</option>
                                                <option value="benefit">Benefit (Semakin besar semakin baik)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kriteria Volume -->
                            <div class="col-md-4 mb-3">
                                <div class="card border-success">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Kriteria: Volume</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Bobot</label>
                                            <input type="number" step="0.01" class="form-control bobot-input" name="bobot_volume" value="0.30" min="0" max="1" required>
                                            <small class="text-muted">Rentang: 0 - 1</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tipe</label>
                                            <select name="tipe_volume" class="form-select">
                                                <option value="benefit" selected>Benefit (Semakin besar semakin baik)</option>
                                                <option value="cost">Cost (Semakin kecil semakin baik)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kriteria Market Cap -->
                            <div class="col-md-4 mb-3">
                                <div class="card border-info">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Kriteria: Market Cap</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Bobot</label>
                                            <input type="number" step="0.01" class="form-control bobot-input" name="bobot_market_cap" value="0.40" min="0" max="1" required>
                                            <small class="text-muted">Rentang: 0 - 1</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tipe</label>
                                            <select name="tipe_market_cap" class="form-select">
                                                <option value="benefit" selected>Benefit (Semakin besar semakin baik)</option>
                                                <option value="cost">Cost (Semakin kecil semakin baik)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Total Bobot:</strong> <span id="totalBobot">1.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-lg btn-primary">
                        <i class="fas fa-calculator"></i> Hitung TOPSIS
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Toggle select all
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('.saham-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = source.checked);
        }

        // Calculate total bobot
        document.querySelectorAll('.bobot-input').forEach(input => {
            input.addEventListener('input', () => {
                let total = 0;
                document.querySelectorAll('.bobot-input').forEach(inp => {
                    total += parseFloat(inp.value) || 0;
                });
                document.getElementById('totalBobot').textContent = total.toFixed(2);

                // Warning jika tidak 1
                if (Math.abs(total - 1) > 0.01) {
                    document.getElementById('totalBobot').style.color = 'red';
                } else {
                    document.getElementById('totalBobot').style.color = 'green';
                }
            });
        });
    </script>

<?php else : ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        Belum ada data saham. Silakan <a href="<?= BASE_URL; ?>saham/tambah">tambah data saham</a> terlebih dahulu.
    </div>
<?php endif; ?>