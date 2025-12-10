<!-- Home Page -->
<div class="row">
    <div class="col-12">
        <div class="jumbotron bg-light p-5 rounded">
            <h1 class="display-4"><?= $heading ?? 'Selamat Datang'; ?></h1>
            <p class="lead"><?= $description ?? ''; ?></p>
            <hr class="my-4">
            <p>Mulai melakukan analisis saham menggunakan metode TOPSIS untuk mendapatkan rekomendasi saham terbaik.</p>
            <a class="btn btn-primary btn-lg" href="<?= BASE_URL; ?>topsis" role="button">
                <i class="fas fa-calculator"></i> Mulai Analisis
            </a>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="row mt-5">
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-database fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Data Saham</h5>
                <p class="card-text">Kelola dan lihat data saham yang akan dianalisis.</p>
                <a href="<?= BASE_URL; ?>saham" class="btn btn-outline-primary">Lihat Data</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-calculator fa-3x text-success mb-3"></i>
                <h5 class="card-title">Perhitungan TOPSIS</h5>
                <p class="card-text">Lakukan perhitungan menggunakan metode TOPSIS.</p>
                <a href="<?= BASE_URL; ?>topsis" class="btn btn-outline-success">Hitung</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                <h5 class="card-title">Hasil & Rekomendasi</h5>
                <p class="card-text">Lihat hasil analisis dan rekomendasi saham terbaik.</p>
                <a href="<?= BASE_URL; ?>topsis/hasil" class="btn btn-outline-info">Lihat Hasil</a>
            </div>
        </div>
    </div>
</div>