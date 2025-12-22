    </main>

    <!-- Modal Laporan -->
    <div class="modal fade" id="modalLaporan" tabindex="-1" aria-labelledby="modalLaporanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLaporanLabel">
                        <i class="fas fa-file-download me-2"></i>Unduh Laporan Analisis Saham
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content -->
                    <div id="laporanContent">
                        <!-- Configuration Section -->
                        <div class="laporan-config">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-cog me-2"></i>Pilih Periode Analisis
                            </h6>
                            <div class="mb-4">
                                <label for="laporanPeriodeSelect" class="form-label">Pilih Periode Analisis</label>
                                <select id="laporanPeriodeSelect" class="form-select">
                                    <option value="7">7 Hari Terakhir</option>
                                    <option value="30" selected>30 Hari Terakhir</option>
                                    <option value="90">90 Hari Terakhir (3 Bulan)</option>
                                    <option value="365">365 Hari Terakhir (1 Tahun)</option>
                                </select>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Laporan berisi: Top 10 saham terbaik, perubahan peringkat, dan sektor yang mendominasi
                                </small>
                            </div>
                        </div>

                        <!-- Download Section -->
                        <div class="laporan-download mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-download me-2"></i>Unduh Format
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <button type="button" id="btnDownloadPDF" class="btn btn-danger btn-lg w-100">
                                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                                    </button>
                                    <small class="text-muted d-block mt-2 text-center">
                                        <i class="fas fa-info-circle me-1"></i>Format untuk dicetak
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" id="btnDownloadCSV" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-file-csv me-2"></i>Download CSV
                                    </button>
                                    <small class="text-muted d-block mt-2 text-center">
                                        <i class="fas fa-info-circle me-1"></i>Format untuk Excel
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-main bg-dark text-white text-center py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="mb-1">
                        <i class="fas fa-chart-line me-2"></i><strong>SahamPintar</strong>
                    </p>
                    <p class="mb-1 small text-white-50">Sistem Rekomendasi Saham dengan Metode TOPSIS</p>
                    <p class="mb-0 small text-white-50">&copy; <?= date('Y'); ?> SahamPintar - v<?= APP_VERSION; ?></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>

    <!-- Define BASE_URL globally for all pages -->
    <script>
        window.BASE_URL = '<?= BASE_URL; ?>';
        console.log('BASE_URL defined:', window.BASE_URL);
    </script>

    <!-- Custom JS -->
    <script src="<?= ASSETS_URL; ?>js/script.js"></script>
    <!-- Laporan Modal JS -->
    <script src="<?= ASSETS_URL; ?>js/laporan.js"></script>
    <!-- Compare JS (only on compare page) -->
    <?php if (isset($data['judul']) && $data['judul'] === 'Bandingkan Saham'): ?>
        <script src="<?= ASSETS_URL; ?>js/compare.js"></script>
    <?php endif; ?>
    </body>

    </html>