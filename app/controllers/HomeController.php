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
            'heading' => 'Selamat Datang di SahamPintar',
            'description' => 'Sistem Rekomendasi Saham menggunakan metode TOPSIS untuk investor pemula',
            'isHomePage' => true
        ];

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer', $data);
    }

    /**
     * Method about - halaman tentang
     */
    public function about()
    {
        // Debug: pastikan method ini dipanggil
        error_log("About method called!");

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
