<!-- Edit Data Saham -->
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="fas fa-edit"></i> Edit Data Saham</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Form Edit Saham</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL; ?>saham/update" method="POST">
                    <input type="hidden" name="id" value="<?= $saham->id ?? ''; ?>">

                    <div class="mb-3">
                        <label for="kode_saham" class="form-label">Kode Saham <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_saham" name="kode_saham" value="<?= $saham->kode_saham ?? ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="nama_saham" class="form-label">Nama Saham <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_saham" name="nama_saham" value="<?= $saham->nama_saham ?? ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="harga" name="harga" value="<?= $saham->harga ?? ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="volume" class="form-label">Volume Perdagangan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="volume" name="volume" value="<?= $saham->volume ?? ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="market_cap" class="form-label">Market Cap (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="market_cap" name="market_cap" value="<?= $saham->market_cap ?? ''; ?>" required>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= BASE_URL; ?>saham" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Info Data</h5>
            </div>
            <div class="card-body">
                <p><strong>Data Sebelumnya:</strong></p>
                <table class="table table-sm">
                    <tr>
                        <td>Kode</td>
                        <td>:</td>
                        <td><strong><?= $saham->kode_saham ?? '-'; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td><?= $saham->nama_saham ?? '-'; ?></td>
                    </tr>
                    <tr>
                        <td>Harga</td>
                        <td>:</td>
                        <td>Rp <?= isset($saham->harga) ? number_format($saham->harga, 0, ',', '.') : '-'; ?></td>
                    </tr>
                </table>
                <div class="alert alert-info mt-3">
                    <small><i class="fas fa-info-circle"></i> Edit data dengan hati-hati!</small>
                </div>
            </div>
        </div>
    </div>
</div>