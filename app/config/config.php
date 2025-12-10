<?php
// Konfigurasi Aplikasi

// Base URL
define('BASE_URL', 'http://localhost/rekomendasi-saham-topsis/public/');

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
?>
