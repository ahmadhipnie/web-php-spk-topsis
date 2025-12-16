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

                <!-- Nama Investor -->
                <div class="form-group-custom mb-4">
                    <label class="form-label-custom">
                        <i class="fas fa-user icon-label"></i>
                        Nama Investor
                    </label>
                    <input
                        type="text"
                        class="form-control-custom"
                        name="nama"
                        placeholder="Masukkan nama Anda"
                        required>
                    <small class="form-hint">Nama akan digunakan untuk identifikasi hasil analisis Anda</small>
                </div>

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

                <!-- Advanced Settings: Custom Weights (Optional) -->
                <div class="form-group-custom mb-5">
                    <div class="advanced-settings-toggle">
                        <button type="button" id="toggleAdvanced" class="btn-advanced-toggle">
                            <i class="fas fa-sliders-h me-2"></i>
                            <span>Pengaturan Lanjutan: Atur Bobot Kriteria (Opsional)</span>
                            <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
                        </button>
                    </div>

                    <div id="advancedSettings" class="advanced-settings-content" style="display: none;">
                        <div class="advanced-info-box">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>Tentang Bobot Kriteria:</strong>
                                <p>Secara default, sistem menggunakan bobot berdasarkan profil risiko Anda. Jika ingin lebih spesifik dalam analisis, Anda bisa mengatur bobot manual untuk setiap kriteria di bawah ini.</p>
                            </div>
                        </div>

                        <input type="hidden" name="use_custom_weight" id="useCustomWeight" value="false">

                        <!-- EPS Slider -->
                        <div class="weight-slider-group">
                            <div class="weight-header">
                                <label class="weight-label">
                                    <i class="fas fa-dollar-sign weight-icon"></i>
                                    EPS (Earnings Per Share)
                                    <span class="weight-tooltip" data-tooltip="Laba bersih per lembar saham. Semakin tinggi, semakin baik profitabilitas.">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </label>
                                <div class="weight-value-box">
                                    <span class="weight-value" id="epsValue">35</span>%
                                </div>
                            </div>
                            <input type="range" class="weight-slider" id="epsSlider" name="weight_eps" min="0" max="100" value="35" step="1">
                            <div class="weight-description">
                                <small>Focus: <strong>Profitabilitas & Keuntungan Perusahaan</strong></small>
                            </div>
                        </div>

                        <!-- PER Slider -->
                        <div class="weight-slider-group">
                            <div class="weight-header">
                                <label class="weight-label">
                                    <i class="fas fa-tag weight-icon"></i>
                                    PER (Price to Earnings Ratio)
                                    <span class="weight-tooltip" data-tooltip="Perbandingan harga saham dengan laba. Semakin rendah, semakin murah valuasinya.">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </label>
                                <div class="weight-value-box">
                                    <span class="weight-value" id="perValue">35</span>%
                                </div>
                            </div>
                            <input type="range" class="weight-slider" id="perSlider" name="weight_per" min="0" max="100" value="35" step="1">
                            <div class="weight-description">
                                <small>Focus: <strong>Valuasi Harga & Value Investing</strong></small>
                            </div>
                        </div>

                        <!-- ROE Slider -->
                        <div class="weight-slider-group">
                            <div class="weight-header">
                                <label class="weight-label">
                                    <i class="fas fa-chart-line weight-icon"></i>
                                    ROE (Return on Equity)
                                    <span class="weight-tooltip" data-tooltip="Return dari modal pemegang saham. Semakin tinggi, semakin efisien perusahaan menggunakan modal.">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </label>
                                <div class="weight-value-box">
                                    <span class="weight-value" id="roeValue">30</span>%
                                </div>
                            </div>
                            <input type="range" class="weight-slider" id="roeSlider" name="weight_roe" min="0" max="100" value="30" step="1">
                            <div class="weight-description">
                                <small>Focus: <strong>Efisiensi Modal & Pengelolaan</strong></small>
                            </div>
                        </div>

                        <!-- Total Weight Indicator -->
                        <div class="weight-total-box">
                            <div class="weight-total-label">
                                <i class="fas fa-calculator"></i>
                                Total Bobot:
                            </div>
                            <div class="weight-total-value" id="totalWeight">
                                <span id="totalWeightNumber">100</span>%
                                <span class="weight-status" id="weightStatus">
                                    <i class="fas fa-check-circle"></i> Valid
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="advanced-actions">
                            <button type="button" id="resetWeights" class="btn-reset-weights">
                                <i class="fas fa-undo"></i>
                                Reset ke Default
                            </button>
                            <small class="text-muted">
                                <i class="fas fa-lightbulb"></i>
                                Tips: Bobot akan otomatis menyesuaikan agar total selalu 100%
                            </small>
                        </div>
                    </div>
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

        // ========================================
        // ADVANCED SETTINGS: Weight Sliders Logic
        // ========================================

        const toggleBtn = document.getElementById('toggleAdvanced');
        const advancedContent = document.getElementById('advancedSettings');
        const toggleIcon = toggleBtn.querySelector('.toggle-icon');
        const useCustomWeightInput = document.getElementById('useCustomWeight');

        // Toggle Advanced Settings
        toggleBtn.addEventListener('click', function() {
            const isHidden = advancedContent.style.display === 'none';
            advancedContent.style.display = isHidden ? 'block' : 'none';
            toggleIcon.classList.toggle('fa-chevron-down');
            toggleIcon.classList.toggle('fa-chevron-up');
            
            // Set flag when user opens advanced settings
            if (isHidden) {
                useCustomWeightInput.value = 'true';
            }
        });

        // Slider Elements
        const epsSlider = document.getElementById('epsSlider');
        const perSlider = document.getElementById('perSlider');
        const roeSlider = document.getElementById('roeSlider');
        
        const epsValue = document.getElementById('epsValue');
        const perValue = document.getElementById('perValue');
        const roeValue = document.getElementById('roeValue');
        
        const totalWeightNumber = document.getElementById('totalWeightNumber');
        const weightStatus = document.getElementById('weightStatus');

        // Risk Profile Default Weights
        const defaultWeights = {
            'konservatif': { eps: 20, per: 50, roe: 30 },
            'moderat': { eps: 35, per: 35, roe: 30 },
            'agresif': { eps: 50, per: 15, roe: 35 }
        };

        let currentProfile = 'moderat';
        let isAdjusting = false;

        // Update display values
        function updateDisplay() {
            const eps = parseInt(epsSlider.value);
            const per = parseInt(perSlider.value);
            const roe = parseInt(roeSlider.value);
            const total = eps + per + roe;

            epsValue.textContent = eps;
            perValue.textContent = per;
            roeValue.textContent = roe;
            totalWeightNumber.textContent = total;

            // Update slider bar gradients
            updateSliderBackground(epsSlider, eps);
            updateSliderBackground(perSlider, per);
            updateSliderBackground(roeSlider, roe);

            // Update status indicator
            if (total === 100) {
                weightStatus.innerHTML = '<i class="fas fa-check-circle"></i> Valid';
                weightStatus.className = 'weight-status weight-status-valid';
            } else {
                weightStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> Invalid';
                weightStatus.className = 'weight-status weight-status-invalid';
            }
        }

        // Update slider background gradient based on value
        function updateSliderBackground(slider, value) {
            const percentage = value;
            slider.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${percentage}%, #e5e7eb ${percentage}%, #e5e7eb 100%)`;
        }

        // Auto-adjust sliders to maintain 100% total
        function adjustSliders(changedSlider) {
            if (isAdjusting) return;
            isAdjusting = true;

            const eps = parseInt(epsSlider.value);
            const per = parseInt(perSlider.value);
            const roe = parseInt(roeSlider.value);
            const total = eps + per + roe;

            if (total !== 100) {
                const diff = total - 100;
                
                // Distribute difference to other sliders
                if (changedSlider === 'eps') {
                    const perShare = Math.floor(diff / 2);
                    const roeShare = diff - perShare;
                    
                    perSlider.value = Math.max(0, Math.min(100, per - perShare));
                    roeSlider.value = Math.max(0, Math.min(100, roe - roeShare));
                } else if (changedSlider === 'per') {
                    const epsShare = Math.floor(diff / 2);
                    const roeShare = diff - epsShare;
                    
                    epsSlider.value = Math.max(0, Math.min(100, eps - epsShare));
                    roeSlider.value = Math.max(0, Math.min(100, roe - roeShare));
                } else if (changedSlider === 'roe') {
                    const epsShare = Math.floor(diff / 2);
                    const perShare = diff - epsShare;
                    
                    epsSlider.value = Math.max(0, Math.min(100, eps - epsShare));
                    perSlider.value = Math.max(0, Math.min(100, per - perShare));
                }

                // Final adjustment to ensure exactly 100%
                const newTotal = parseInt(epsSlider.value) + parseInt(perSlider.value) + parseInt(roeSlider.value);
                if (newTotal !== 100) {
                    // Adjust the slider that wasn't changed
                    if (changedSlider !== 'roe') {
                        roeSlider.value = parseInt(roeSlider.value) + (100 - newTotal);
                    } else if (changedSlider !== 'per') {
                        perSlider.value = parseInt(perSlider.value) + (100 - newTotal);
                    } else {
                        epsSlider.value = parseInt(epsSlider.value) + (100 - newTotal);
                    }
                }
            }

            updateDisplay();
            isAdjusting = false;
        }

        // Slider event listeners
        epsSlider.addEventListener('input', function() {
            adjustSliders('eps');
        });

        perSlider.addEventListener('input', function() {
            adjustSliders('per');
        });

        roeSlider.addEventListener('input', function() {
            adjustSliders('roe');
        });

        // Reset to default based on risk profile
        document.getElementById('resetWeights').addEventListener('click', function() {
            const weights = defaultWeights[currentProfile];
            epsSlider.value = weights.eps;
            perSlider.value = weights.per;
            roeSlider.value = weights.roe;
            updateDisplay();
        });

        // Update current profile when risk profile changes
        document.querySelectorAll('input[name="profil_risiko"]').forEach(radio => {
            radio.addEventListener('change', function() {
                currentProfile = this.value;
                // Auto-update sliders if advanced settings is open
                if (advancedContent.style.display === 'block') {
                    const weights = defaultWeights[currentProfile];
                    epsSlider.value = weights.eps;
                    perSlider.value = weights.per;
                    roeSlider.value = weights.roe;
                    updateDisplay();
                }
            });
        });

        // Initialize display
        updateDisplay();
    });
</script>