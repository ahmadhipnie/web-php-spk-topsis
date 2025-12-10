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
        $saham_data = $_SESSION['saham_data'] ?? [];
        $kriteria = $_SESSION['kriteria'] ?? [];

        $data = [
            'title' => 'Hasil Perhitungan TOPSIS',
            'saham' => $saham_data,
            'kriteria' => $kriteria
        ];

        $this->view('templates/header', $data);
        $this->view('topsis/hasil', $data);
        $this->view('templates/footer');
    }
}
