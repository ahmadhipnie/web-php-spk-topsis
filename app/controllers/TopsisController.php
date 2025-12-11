<?php

/**
 * TopsisController
 * Controller untuk perhitungan TOPSIS
 */
class TopsisController extends Controller
{
    // Dummy mode - tidak pakai database dulu
    // private $sahamModel;

    // public function __construct()
    // {
    //     $this->sahamModel = $this->model('SahamModel');
    // }

    /**
     * Method index - halaman utama perhitungan TOPSIS
     */
    public function index()
    {
        // Dummy data untuk tampilan
        $dummyData = [
            (object)[
                'id' => 1,
                'kode_saham' => 'BBCA',
                'nama_saham' => 'Bank Central Asia Tbk',
                'harga' => 8500,
                'volume' => 1500000,
                'market_cap' => 500000000000
            ],
            (object)[
                'id' => 2,
                'kode_saham' => 'BBRI',
                'nama_saham' => 'Bank Rakyat Indonesia Tbk',
                'harga' => 4500,
                'volume' => 2000000,
                'market_cap' => 450000000000
            ],
            (object)[
                'id' => 3,
                'kode_saham' => 'TLKM',
                'nama_saham' => 'Telkom Indonesia Tbk',
                'harga' => 3200,
                'volume' => 1200000,
                'market_cap' => 300000000000
            ]
        ];

        $data = [
            'title' => 'Perhitungan TOPSIS',
            'saham' => $dummyData
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ambil data dari form
            $saham_ids = $_POST['saham_ids'] ?? [];
            $kriteria = [
                'harga' => [
                    'bobot' => $_POST['bobot_harga'] ?? 0,
                    'tipe' => $_POST['tipe_harga'] ?? 'cost'
                ],
                'volume' => [
                    'bobot' => $_POST['bobot_volume'] ?? 0,
                    'tipe' => $_POST['tipe_volume'] ?? 'benefit'
                ],
                'market_cap' => [
                    'bobot' => $_POST['bobot_market_cap'] ?? 0,
                    'tipe' => $_POST['tipe_market_cap'] ?? 'benefit'
                ]
            ];

            // Dummy data saham yang dipilih
            $allDummyData = [
                (object)[
                    'id' => 1,
                    'kode_saham' => 'BBCA',
                    'nama_saham' => 'Bank Central Asia Tbk',
                    'harga' => 8500,
                    'volume' => 1500000,
                    'market_cap' => 500000000000
                ],
                (object)[
                    'id' => 2,
                    'kode_saham' => 'BBRI',
                    'nama_saham' => 'Bank Rakyat Indonesia Tbk',
                    'harga' => 4500,
                    'volume' => 2000000,
                    'market_cap' => 450000000000
                ],
                (object)[
                    'id' => 3,
                    'kode_saham' => 'TLKM',
                    'nama_saham' => 'Telkom Indonesia Tbk',
                    'harga' => 3200,
                    'volume' => 1200000,
                    'market_cap' => 300000000000
                ]
            ];

            // Filter data berdasarkan ID yang dipilih
            $saham_data = array_filter($allDummyData, function ($item) use ($saham_ids) {
                return in_array($item->id, $saham_ids);
            });

            // Proses perhitungan TOPSIS (placeholder)
            // $hasil = TopsisHelper::hitung($saham_data, $kriteria);

            // Untuk sementara, redirect ke halaman hasil dengan session data
            $_SESSION['saham_data'] = array_values($saham_data);
            $_SESSION['kriteria'] = $kriteria;

            $this->redirect('topsis/hasil');
        }
    }

    /**
     * Method hasil - menampilkan hasil perhitungan TOPSIS
     */
    public function hasil()
    {
        // Cek apakah ada data dari session, kalau tidak pakai dummy
        $saham_data = $_SESSION['saham_data'] ?? null;
        $kriteria_session = $_SESSION['kriteria'] ?? null;

        // Jika tidak ada data dari perhitungan, gunakan data dummy lengkap
        if (empty($saham_data)) {
            $saham_data = [
                (object)[
                    'id' => 1,
                    'kode_saham' => 'BBCA',
                    'nama_saham' => 'Bank Central Asia Tbk',
                    'harga' => 9875,
                    'volume' => 15234567,
                    'market_cap' => 1210000000000,
                    'sektor' => 'Perbankan',
                    'pe_ratio' => 25.2,
                    'dividend_yield' => 2.8
                ],
                (object)[
                    'id' => 2,
                    'kode_saham' => 'BBRI',
                    'nama_saham' => 'Bank Rakyat Indonesia Tbk',
                    'harga' => 4580,
                    'volume' => 28456123,
                    'market_cap' => 864000000000,
                    'sektor' => 'Perbankan',
                    'pe_ratio' => 12.8,
                    'dividend_yield' => 3.5
                ],
                (object)[
                    'id' => 3,
                    'kode_saham' => 'TLKM',
                    'nama_saham' => 'Telkom Indonesia Tbk',
                    'harga' => 3890,
                    'volume' => 45678901,
                    'market_cap' => 387000000000,
                    'sektor' => 'Telekomunikasi',
                    'pe_ratio' => 18.5,
                    'dividend_yield' => 4.2
                ],
                (object)[
                    'id' => 4,
                    'kode_saham' => 'ASII',
                    'nama_saham' => 'Astra International Tbk',
                    'harga' => 5275,
                    'volume' => 12345678,
                    'market_cap' => 295000000000,
                    'sektor' => 'Otomotif',
                    'pe_ratio' => 15.3,
                    'dividend_yield' => 3.8
                ],
                (object)[
                    'id' => 5,
                    'kode_saham' => 'UNVR',
                    'nama_saham' => 'Unilever Indonesia Tbk',
                    'harga' => 3650,
                    'volume' => 8765432,
                    'market_cap' => 165000000000,
                    'sektor' => 'Konsumer',
                    'pe_ratio' => 28.7,
                    'dividend_yield' => 5.1
                ],
                (object)[
                    'id' => 6,
                    'kode_saham' => 'ICBP',
                    'nama_saham' => 'Indofood CBP Sukses Makmur Tbk',
                    'harga' => 10200,
                    'volume' => 5432109,
                    'market_cap' => 98000000000,
                    'sektor' => 'Konsumer',
                    'pe_ratio' => 22.4,
                    'dividend_yield' => 4.6
                ]
            ];
        }

        // Data kriteria lengkap (untuk header card)
        $kriteria = [
            'budget' => 50000000,                    // Rp 50 juta
            'profil_risiko' => 'moderat',            // konservatif/moderat/agresif
            'jangka_waktu' => 'menengah',            // pendek/menengah/panjang
            'sektor' => 'semua',                     // perbankan/teknologi/semua

            // Bobot untuk perhitungan TOPSIS
            'harga' => [
                'bobot' => $kriteria_session['harga']['bobot'] ?? 0.25,
                'tipe' => $kriteria_session['harga']['tipe'] ?? 'cost'
            ],
            'volume' => [
                'bobot' => $kriteria_session['volume']['bobot'] ?? 0.35,
                'tipe' => $kriteria_session['volume']['tipe'] ?? 'benefit'
            ],
            'market_cap' => [
                'bobot' => $kriteria_session['market_cap']['bobot'] ?? 0.40,
                'tipe' => $kriteria_session['market_cap']['tipe'] ?? 'benefit'
            ]
        ];

        $data = [
            'title' => 'Hasil Perhitungan TOPSIS',
            'saham' => $saham_data,
            'kriteria' => $kriteria
        ];

        $this->view('templates/header', $data);
        $this->view('topsis/hasil', $data);
        $this->view('templates/footer');
    }

    /**
     * Method detail - menampilkan detail saham
     * @param string $kode_saham - Kode saham yang akan ditampilkan
     */
    public function detail($kode_saham = null)
    {
        // Data dummy lengkap untuk semua saham
        $allSaham = [
            'BBCA' => (object)[
                'kode_saham' => 'BBCA',
                'nama_saham' => 'Bank Central Asia Tbk',
                'harga' => 9875,
                'volume' => 15234567,
                'market_cap' => 1210000000000,
                'sektor' => 'Perbankan',
                'pe_ratio' => 25.2,
                'dividend_yield' => 2.8,
                'deskripsi' => 'Bank Central Asia adalah bank swasta terbesar di Indonesia dengan jaringan cabang yang luas dan layanan perbankan digital terdepan. BCA dikenal dengan stabilitas keuangan yang kuat dan kualitas aset yang baik.'
            ],
            'BBRI' => (object)[
                'kode_saham' => 'BBRI',
                'nama_saham' => 'Bank Rakyat Indonesia Tbk',
                'harga' => 4580,
                'volume' => 28456123,
                'market_cap' => 864000000000,
                'sektor' => 'Perbankan',
                'pe_ratio' => 12.8,
                'dividend_yield' => 3.5,
                'deskripsi' => 'Bank Rakyat Indonesia adalah bank BUMN terbesar dengan fokus pada segmen mikro, kecil, dan menengah. BRI memiliki jaringan terluas di Indonesia dan merupakan pemimpin dalam pembiayaan UMKM.'
            ],
            'TLKM' => (object)[
                'kode_saham' => 'TLKM',
                'nama_saham' => 'Telkom Indonesia Tbk',
                'harga' => 3890,
                'volume' => 45678901,
                'market_cap' => 387000000000,
                'sektor' => 'Telekomunikasi',
                'pe_ratio' => 18.5,
                'dividend_yield' => 4.2,
                'deskripsi' => 'Telkom Indonesia adalah perusahaan telekomunikasi terbesar di Indonesia dengan layanan telepon, internet, dan digital. Telkom terus bertransformasi menjadi perusahaan digital dengan berbagai layanan digital dan ekosistem TelkomGroup.'
            ],
            'ASII' => (object)[
                'kode_saham' => 'ASII',
                'nama_saham' => 'Astra International Tbk',
                'harga' => 5275,
                'volume' => 12345678,
                'market_cap' => 295000000000,
                'sektor' => 'Otomotif',
                'pe_ratio' => 15.3,
                'dividend_yield' => 3.8,
                'deskripsi' => 'Astra International adalah konglomerat dengan bisnis di otomotif, alat berat, agribisnis, dan infrastruktur. Astra memegang merek-merek terkenal seperti Toyota, Daihatsu, dan Honda di Indonesia.'
            ],
            'UNVR' => (object)[
                'kode_saham' => 'UNVR',
                'nama_saham' => 'Unilever Indonesia Tbk',
                'harga' => 2690,
                'volume' => 8765432,
                'market_cap' => 63000000000,
                'sektor' => 'Barang Konsumsi',
                'pe_ratio' => 28.5,
                'dividend_yield' => 4.2,
                'deskripsi' => 'Unilever Indonesia adalah produsen terkemuka produk consumer goods dengan brand-brand terkenal seperti Dove, Lifebuoy, dan Sunsilk. Perusahaan memiliki distribusi yang luas dan brand equity yang kuat di Indonesia.'
            ],
            'ICBP' => (object)[
                'kode_saham' => 'ICBP',
                'nama_saham' => 'Indofood CBP Sukses Makmur Tbk',
                'harga' => 10200,
                'volume' => 5432109,
                'market_cap' => 98000000000,
                'sektor' => 'Makanan & Minuman',
                'pe_ratio' => 22.4,
                'dividend_yield' => 4.6,
                'deskripsi' => 'Indofood CBP adalah produsen mi instan, dairy, makanan ringan, dan minuman dengan brand seperti Indomie, Chitato, dan Club. Perusahaan memiliki pangsa pasar yang dominan di kategori mi instan.'
            ]
        ];

        // Cari saham berdasarkan kode
        $saham = $allSaham[$kode_saham] ?? null;

        // Jika saham tidak ditemukan, redirect ke hasil
        if (!$saham) {
            $this->redirect('topsis/hasil');
            return;
        }

        $data = [
            'title' => 'Detail Saham - ' . $kode_saham,
            'saham' => $saham
        ];

        $this->view('templates/header', $data);
        $this->view('topsis/detail', $data);
        $this->view('templates/footer');
    }
}
