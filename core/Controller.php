<?php

/**
 * Class Controller
 * Base controller yang akan di-extend oleh semua controller
 */
class Controller
{
    /**
     * Load view
     * @param string $view nama view
     * @param array $data data yang akan dikirim ke view
     */
    public function view($view, $data = [])
    {
        // Ekstrak data agar bisa diakses sebagai variabel
        extract($data);

        // Cek apakah file view ada
        if (file_exists(APP_PATH . 'views/' . $view . '.php')) {
            require_once APP_PATH . 'views/' . $view . '.php';
        } else {
            die('View tidak ditemukan: ' . $view);
        }
    }

    /**
     * Load model
     * @param string $model nama model
     * @return object instance model
     */
    public function model($model)
    {
        require_once APP_PATH . 'models/' . $model . '.php';
        return new $model;
    }

    /**
     * Redirect ke URL tertentu
     * @param string $url URL tujuan
     */
    public function redirect($url)
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}
