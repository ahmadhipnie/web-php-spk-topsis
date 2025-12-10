<!-- About Page -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3><?= $heading ?? 'Tentang'; ?></h3>
            </div>
            <div class="card-body">
                <h5 class="card-title">Metode TOPSIS</h5>
                <p class="card-text">
                    <?= $content ?? 'TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution) adalah salah satu metode pengambilan keputusan multikriteria yang dikembangkan oleh Yoon dan Hwang pada tahun 1981.'; ?>
                </p>

                <h5 class="mt-4">Cara Kerja TOPSIS:</h5>
                <ol>
                    <li>Membuat matriks keputusan yang ternormalisasi</li>
                    <li>Membuat matriks keputusan yang ternormalisasi terbobot</li>
                    <li>Menentukan matriks solusi ideal positif dan negatif</li>
                    <li>Menghitung jarak antara nilai setiap alternatif dengan solusi ideal positif dan negatif</li>
                    <li>Menghitung nilai preferensi untuk setiap alternatif</li>
                </ol>

                <div class="alert alert-info mt-4" role="alert">
                    <i class="fas fa-info-circle"></i>
                    Aplikasi ini dikembangkan untuk membantu investor dalam mengambil keputusan investasi saham berdasarkan kriteria yang objektif.
                </div>
            </div>
        </div>
    </div>
</div>