<!-- Form Input Kriteria TOPSIS -->

<!-- Hero Section - Terpisah dari Navbar -->
<section class="form-hero-section">
    <div class="container">
        <div class="hero-content-form text-center">
            <div class="hero-badge-form mb-3">
                <i class="fas fa-chart-line"></i>
                <span>Analisis Saham TOPSIS</span>
            </div>
            <h1 class="hero-title-form">Dapatkan Rekomendasi Saham Terbaik</h1>
            <p class="hero-subtitle-form">Sesuai dengan profil investasi dan tujuan keuangan Anda</p>
        </div>
    </div>
</section>

<!-- Form Section - Background Putih Solid -->
<section class="form-input-section">
    <div class="container">
        <div class="form-card-main">
            <form action="<?= BASE_URL; ?>topsis/hitung" method="POST" id="kriteriaForm">

                <!-- Budget Investasi -->
                <div class="form-group-custom mb-4">
                    <label class="form-label-custom">
                        <i class="fas fa-wallet icon-label"></i>
                        Budget Investasi
                    </label>
                    <div class="input-wrapper-custom">
                        <span class="input-prefix">Rp</span>
                        <input
                            type="text"
                            class="form-control-custom"
                            name="budget"
                            id="budgetInput"
                            placeholder="50.000.000"
                            required>
                    </div>
                    <small class="form-hint">Masukkan jumlah dana yang siap Anda investasikan</small>
                </div>

                <!-- Profil Risiko -->
                <div class="form-group-custom mb-4">
                    <label class="form-label-custom">
                        <i class="fas fa-chart-bar icon-label"></i>
                        Profil Risiko Anda
                    </label>
                    <div class="risk-cards">
                        <label class="risk-card">
                            <input type="radio" name="profil_risiko" value="konservatif" required>
                            <div class="risk-card-content">
                                <div class="risk-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h4>Konservatif</h4>
                                <p>Prioritas keamanan, return stabil</p>
                            </div>
                        </label>

                        <label class="risk-card">
                            <input type="radio" name="profil_risiko" value="moderat" checked>
                            <div class="risk-card-content">
                                <div class="risk-icon">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                <h4>Moderat</h4>
                                <p>Seimbang antara risiko & return</p>
                            </div>
                        </label>

                        <label class="risk-card">
                            <input type="radio" name="profil_risiko" value="agresif">
                            <div class="risk-card-content">
                                <div class="risk-icon">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <h4>Agresif</h4>
                                <p>Potensi return tinggi, risiko besar</p>
                            </div>
                        </label>
                    </div>
                    <small class="form-hint">Pilih profil risiko yang sesuai dengan toleransi Anda</small>
                </div>

                <!-- Jangka Waktu Investasi -->
                <div class="form-group-custom mb-4">
                    <label class="form-label-custom">
                        <i class="fas fa-clock icon-label"></i>
                        Jangka Waktu Investasi
                    </label>
                    <select class="form-select-custom" name="jangka_waktu" required>
                        <option value="">Pilih jangka waktu...</option>
                        <option value="pendek">Pendek (< 1 tahun)</option>
                        <option value="menengah" selected>Menengah (1-3 tahun)</option>
                        <option value="panjang">Panjang (> 3 tahun)</option>
                    </select>
                    <small class="form-hint">Berapa lama Anda berencana memegang saham ini?</small>
                </div>

                <!-- Preferensi Sektor -->
                <div class="form-group-custom mb-5">
                    <label class="form-label-custom">
                        <i class="fas fa-building icon-label"></i>
                        Preferensi Sektor
                    </label>
                    <select class="form-select-custom" name="sektor" required>
                        <option value="semua" selected>Semua Sektor</option>
                        <option value="perbankan">Perbankan</option>
                        <option value="teknologi">Teknologi</option>
                        <option value="konsumer">Barang Konsumsi</option>
                        <option value="energi">Energi</option>
                        <option value="properti">Properti</option>
                        <option value="infrastruktur">Infrastruktur</option>
                    </select>
                    <small class="form-hint">Sektor industri yang ingin Anda fokuskan</small>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit-form">
                        <i class="fas fa-rocket"></i>
                        Dapatkan Rekomendasi Gratis
                    </button>
                    <p class="form-footer-text">
                        <i class="fas fa-lock"></i> Data Anda aman dan tidak akan dibagikan
                    </p>
                </div>

            </form>
        </div>
    </div>
</section>

<!-- JavaScript untuk Format Rupiah -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const budgetInput = document.getElementById('budgetInput');

        // Format number dengan separator ribuan
        function formatRupiah(value) {
            const number = value.replace(/\D/g, '');
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        budgetInput.addEventListener('input', function(e) {
            const value = e.target.value;
            e.target.value = formatRupiah(value);
        });

        // Form validation
        document.getElementById('kriteriaForm').addEventListener('submit', function(e) {
            const budget = budgetInput.value.replace(/\D/g, '');
            if (parseInt(budget) < 1000000) {
                e.preventDefault();
                alert('Budget minimal Rp 1.000.000');
                return false;
            }
        });
    });
</script>