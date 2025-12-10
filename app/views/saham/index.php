<!-- Data Saham Page -->
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="fas fa-database"></i> Data Saham</h2>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <a href="<?= BASE_URL; ?>saham/tambah" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Data Saham
        </a>
    </div>
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" class="form-control" id="searchInput" placeholder="Cari saham..." onkeyup="searchTable('searchInput', 'tableSaham')">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableSaham">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Saham</th>
                                <th>Nama Saham</th>
                                <th>Harga</th>
                                <th>Volume</th>
                                <th>Market Cap</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($saham)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($saham as $s) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><strong><?= $s->kode_saham; ?></strong></td>
                                        <td><?= $s->nama_saham; ?></td>
                                        <td>Rp <?= number_format($s->harga, 0, ',', '.'); ?></td>
                                        <td><?= number_format($s->volume, 0, ',', '.'); ?></td>
                                        <td>Rp <?= number_format($s->market_cap, 0, ',', '.'); ?></td>
                                        <td>
                                            <a href="<?= BASE_URL; ?>saham/edit/<?= $s->id; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= BASE_URL; ?>saham/hapus/<?= $s->id; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete('Apakah Anda yakin ingin menghapus data saham ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle"></i> Belum ada data saham. Silakan tambah data terlebih dahulu.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>