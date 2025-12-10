<?php

/**
 * SahamController
 * Controller untuk mengelola data saham
 */
class SahamController extends Controller
{
    // Dummy mode - tidak pakai database dulu
    // private $sahamModel;

    // public function __construct()
    // {
    //     $this->sahamModel = $this->model('SahamModel');
    // }

    /**
     * Method index - menampilkan semua data saham
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
            'title' => 'Data Saham',
            'saham' => $dummyData
        ];

        $this->view('templates/header', $data);
        $this->view('saham/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Method tambah - menampilkan form tambah saham
     */
    public function tambah()
    {
        $data = [
            'title' => 'Tambah Data Saham'
        ];

        $this->view('templates/header', $data);
        $this->view('saham/tambah', $data);
        $this->view('templates/footer');
    }

    /**
     * Method simpan - menyimpan data saham
     */
    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Dummy: langsung redirect ke halaman index
            // TODO: Implementasi simpan ke database
            $this->redirect('saham');
        }
    }

    /**
     * Method edit - menampilkan form edit saham
     */
    public function edit($id)
    {
        // Dummy data untuk edit
        $dummySaham = (object)[
            'id' => $id,
            'kode_saham' => 'BBCA',
            'nama_saham' => 'Bank Central Asia Tbk',
            'harga' => 8500,
            'volume' => 1500000,
            'market_cap' => 500000000000
        ];

        $data = [
            'title' => 'Edit Data Saham',
            'saham' => $dummySaham
        ];

        $this->view('templates/header', $data);
        $this->view('saham/edit', $data);
        $this->view('templates/footer');
    }

    /**
     * Method update - mengupdate data saham
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Dummy: langsung redirect ke halaman index
            // TODO: Implementasi update ke database
            $this->redirect('saham');
        }
    }

    /**
     * Method hapus - menghapus data saham
     */
    public function hapus($id)
    {
        // Dummy: langsung redirect ke halaman index
        // TODO: Implementasi hapus dari database
        $this->redirect('saham');
    }
}
