<?php
// Konfigurasi Aplikasi

// Base URL - Sederhana untuk PHP built-in server
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
define('BASE_URL', $protocol . '://' . $host . '/');

// Assets URL - Point ke public/assets
define('ASSETS_URL', BASE_URL . 'public/assets/');

// App Info
define('APP_NAME', 'Sistem Rekomendasi Saham TOPSIS');
define('APP_VERSION', '1.0.0');

// Path
define('ROOT_PATH', dirname(__DIR__, 2) . '/');
define('APP_PATH', ROOT_PATH . 'app/');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
