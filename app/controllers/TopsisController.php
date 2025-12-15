<?php

/**
 * TopsisController
 * Controller untuk perhitungan TOPSIS dengan database
 */
class TopsisController extends Controller
{
    private $sahamModel;

    public function __construct()
    {
        $this->sahamModel = $this->model('SahamModel');
    }

    /**
     * Method index - halaman form input kriteria TOPSIS
     */
    public function index()
    {
        $data = [
            'title' => 'Analisis TOPSIS'
        ];

        $this->view('templates/header', $data);
        $this->view('topsis/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Method hitung - memproses perhitungan TOPSIS
     */
    public function hitung()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . BASE_URL . 'topsis');
            exit;
        }

        // Start output buffering to prevent header issues
        ob_start();
        
        // Debug file untuk troubleshooting
        $debugFile = __DIR__ . '/../../debug_topsis.txt';
        file_put_contents($debugFile, date('Y-m-d H:i:s') . " - TOPSIS Start\n", FILE_APPEND);

        try {
            // 1. Ambil data dari form
            $nama = $_POST['nama'] ?? '';
            $budget = str_replace('.', '', $_POST['budget'] ?? '0'); // Remove thousand separator
            $budget = (float) $budget;
            $profilRisiko = $_POST['profil_risiko'] ?? 'moderat';
            $jangkaWaktu = $_POST['jangka_waktu'] ?? 'menengah';
            $sektor = $_POST['sektor'] ?? 'semua';
            
            // Debug log
            file_put_contents($debugFile, "Input - Nama: $nama, Budget: $budget, Profil: $profilRisiko, Sektor: $sektor\n", FILE_APPEND);

            // Validasi input
            if (empty($nama)) {
                throw new Exception('Nama investor harus diisi');
            }

            if ($budget < 1000000) {
                throw new Exception('Budget minimal Rp 1.000.000');
            }

            // 2. Ambil data saham dari database (filter by budget)
            $sahamData = $this->sahamModel->getSahamForTopsis($sektor, $budget);
            
            // Debug log
            file_put_contents($debugFile, "Saham ditemukan: " . count($sahamData) . "\n", FILE_APPEND);

            if (empty($sahamData)) {
                file_put_contents($debugFile, "ERROR: Tidak ada saham\n", FILE_APPEND);
                throw new Exception('Tidak ada saham yang sesuai dengan kriteria budget Rp ' . number_format($budget, 0, ',', '.') . ' dan sektor ' . $sektor);
            }

            // 3. Get bobot berdasarkan profil risiko
            $bobot = TopsisHelper::getBobotByProfilRisiko($profilRisiko);

            // 4. Hitung TOPSIS
            $hasilTopsis = TopsisHelper::hitungTopsis($sahamData, $bobot);

            // 5. Simpan data investor
            $dataInvestor = [
                'nama' => $nama,
                'preferensi' => $profilRisiko,
                'kriteria' => json_encode([
                    'budget' => $budget,
                    'profil_risiko' => $profilRisiko,
                    'jangka_waktu' => $jangkaWaktu,
                    'sektor' => $sektor,
                    'bobot' => $bobot
                ])
            ];

            $idInvestor = $this->sahamModel->insertInvestor($dataInvestor);
            
            // Data untuk session (struktur lengkap untuk view)
            $investorDataForSession = [
                'nama_investor' => $nama,
                'budget' => $budget,
                'profil_risiko' => $profilRisiko,
                'jangka_waktu' => $jangkaWaktu,
                'sektor' => $sektor,
                'bobot' => $bobot
            ];

            // 6. Simpan hasil rekomendasi dan topsis ke database
            $tanggal = date('Y-m-d');
            
            foreach ($hasilTopsis as $hasil) {
                $saham = $hasil['saham'];
                
                // Simpan rekomendasi
                $dataRekomendasi = [
                    'id_investor' => $idInvestor,
                    'id_saham' => $saham->id_saham,
                    'peringkat' => $hasil['ranking'],
                    'tanggal_rekomendasi' => $tanggal
                ];
                $resultRek = $this->sahamModel->insertRekomendasi($dataRekomendasi);
                
                if (!$resultRek) {
                    throw new Exception('Gagal menyimpan rekomendasi untuk saham ' . $saham->kode_saham);
                }

                // Simpan data topsis
                $dataTopsis = [
                    'id_saham' => $saham->id_saham,
                    'normalisasi_data' => TopsisHelper::formatNormalisasiToJson($hasil['normalisasi']),
                    'jarak_solusi_ideal' => TopsisHelper::formatJarakToJson(
                        $hasil['jarak_positif'],
                        $hasil['jarak_negatif'],
                        $hasil['skor']
                    ),
                    'bobot_kriteria' => TopsisHelper::formatBobotToJson($bobot)
                ];
                $resultTopsis = $this->sahamModel->insertTopsis($dataTopsis);
                
                if (!$resultTopsis) {
                    throw new Exception('Gagal menyimpan data TOPSIS untuk saham ' . $saham->kode_saham);
                }
            }

            // 7. Simpan ID investor ke session untuk ditampilkan di halaman hasil
            $_SESSION['id_investor'] = $idInvestor;
            
            // Transform hasil TOPSIS untuk view
            $hasilForSession = [];
            foreach ($hasilTopsis as $hasil) {
                $saham = $hasil['saham'];
                $hasilForSession[] = [
                    'id_saham' => $saham->id_saham,
                    'kode_saham' => $saham->kode_saham,
                    'nama_saham' => $saham->nama_saham,
                    'sektor' => $saham->sektor,
                    'eps' => $saham->EPS,
                    'per' => $saham->PER,
                    'roe' => $saham->ROE,
                    'harga_buka' => $saham->harga_buka,
                    'harga_tutup' => $saham->harga_tutup,
                    'harga_tertinggi' => $saham->harga_tertinggi,
                    'harga_terendah' => $saham->harga_terendah,
                    'volume' => $saham->volume,
                    'tanggal' => $saham->tanggal,
                    'skor' => $hasil['skor'],
                    'ranking' => $hasil['ranking'],
                    'normalisasi' => $hasil['normalisasi'],
                    'jarak_positif' => $hasil['jarak_positif'],
                    'jarak_negatif' => $hasil['jarak_negatif']
                ];
            }
            
            $_SESSION['hasil_topsis'] = $hasilForSession;
            $_SESSION['investor_data'] = $investorDataForSession;

            // Debug log
            file_put_contents($debugFile, "Data saved to session. Investor ID: $idInvestor\n", FILE_APPEND);
            file_put_contents($debugFile, "Redirecting to hasil...\n\n", FILE_APPEND);

            // Clean output buffer
            ob_end_clean();

            // 8. Redirect ke halaman hasil
            Flasher::setFlash('Analisis TOPSIS', 'berhasil dilakukan', 'success');
            header('Location: ' . BASE_URL . 'topsis/hasil');
            exit;

        } catch (Exception $e) {
            // Clean output buffer
            ob_end_clean();
            
            // Log error untuk debugging
            $debugFile = __DIR__ . '/../../debug_topsis.txt';
            file_put_contents($debugFile, "ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            file_put_contents($debugFile, "TRACE: " . $e->getTraceAsString() . "\n\n", FILE_APPEND);
            
            Flasher::setFlash('Error', $e->getMessage(), 'danger');
            header('Location: ' . BASE_URL . 'topsis');
            exit;
        }
    }

    /**
     * Method hasil - menampilkan hasil perhitungan TOPSIS
     */
    public function hasil()
    {
        // Cek apakah ada data di session
        if (!isset($_SESSION['id_investor']) || !isset($_SESSION['hasil_topsis'])) {
            Flasher::setFlash('Analisis', 'belum dilakukan', 'warning');
            header('Location: ' . BASE_URL . 'topsis');
            exit;
        }

        $idInvestor = $_SESSION['id_investor'];
        $hasilTopsis = $_SESSION['hasil_topsis'];
        $investorData = $_SESSION['investor_data'];

        // Ambil data investor dari database
        $investor = $this->sahamModel->getInvestorById($idInvestor);

        $data = [
            'title' => 'Hasil Analisis TOPSIS',
            'investor' => $investor,
            'hasilTopsis' => $hasilTopsis,
            'investorData' => $investorData
        ];

        $this->view('templates/header', $data);
        $this->view('topsis/hasil', $data);
        $this->view('templates/footer');
    }

    /**
     * Method detail - menampilkan detail saham
     */
    public function detail($idSaham = null)
    {
        if ($idSaham === null) {
            header('Location: ' . BASE_URL . 'topsis/hasil');
            exit;
        }

        // Ambil data saham
        $saham = $this->sahamModel->getSahamById($idSaham);

        if (!$saham) {
            Flasher::setFlash('Saham', 'tidak ditemukan', 'danger');
            header('Location: ' . BASE_URL . 'topsis/hasil');
            exit;
        }

        $data = [
            'title' => 'Detail Saham - ' . $saham->kode_saham,
            'saham' => $saham
        ];

        $this->view('templates/header', $data);
        $this->view('topsis/detail', $data);
        $this->view('templates/footer');
    }
}