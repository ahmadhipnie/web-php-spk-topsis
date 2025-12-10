<!-- Tambah Data Saham -->
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="fas fa-plus-circle"></i> Tambah Data Saham</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Tambah Saham</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL; ?>saham/simpan" method="POST">
                    <div class="mb-3">
                        <label for="kode_saham" class="form-label">Kode Saham <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_saham" name="kode_saham" placeholder="Contoh: BBCA" required>
                        <small class="text-muted">Kode saham di BEI (4 karakter)</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_saham" class="form-label">Nama Saham <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_saham" name="nama_saham" placeholder="Contoh: Bank Central Asia Tbk" required>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 8500" required>
                        <small class="text-muted">Harga per lembar saham</small>
                    </div>

                    <div class="mb-3">
                        <label for="volume" class="form-label">Volume Perdagangan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="volume" name="volume" placeholder="Contoh: 1000000" required>
                        <small class="text-muted">Volume perdagangan harian</small>
                    </div>

                    <div class="mb-3">
                        <label for="market_cap" class="form-label">Market Cap (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="market_cap" name="market_cap" placeholder="Contoh: 500000000000" required>
                        <small class="text-muted">Kapitalisasi pasar perusahaan</small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= BASE_URL; ?>saham" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Petunjuk</h5>
            </div>
            <div class="card-body">
                <p><strong>Keterangan Field:</strong></p>
                <ul>
                    <li><strong>Kode Saham:</strong> Kode ticker saham (4 huruf)</li>
                    <li><strong>Nama Saham:</strong> Nama lengkap perusahaan</li>
                    <li><strong>Harga:</strong> Harga terakhir per lembar</li>
                    <li><strong>Volume:</strong> Jumlah lot yang diperdagangkan</li>
                    <li><strong>Market Cap:</strong> Total nilai pasar perusahaan</li>
                </ul>
                <div class="alert alert-warning mt-3">
                    <small><i class="fas fa-exclamation-triangle"></i> Pastikan semua data diisi dengan benar!</small>
                </div>
            </div>
        </div>
    </div>
</div>