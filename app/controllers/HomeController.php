<?php

/**
 * HomeController
 * Controller untuk halaman utama/home
 */
class HomeController extends Controller
{
    /**
     * Method index - halaman utama
     */
    public function index()
    {
        $data = [
            'title' => 'Home',
            'heading' => 'Selamat Datang di ' . APP_NAME,
            'description' => 'Sistem Pendukung Keputusan untuk Rekomendasi Saham menggunakan metode TOPSIS'
        ];

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Method about - halaman tentang
     */
    public function about()
    {
        $data = [
            'title' => 'Tentang',
            'heading' => 'Tentang Aplikasi',
            'content' => 'Aplikasi ini menggunakan metode TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution) untuk memberikan rekomendasi saham terbaik berdasarkan kriteria yang telah ditentukan.'
        ];

        $this->view('templates/header', $data);
        $this->view('home/about', $data);
        $this->view('templates/footer');
    }
}
