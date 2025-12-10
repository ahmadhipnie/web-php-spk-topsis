<?php

/**
 * Entry point aplikasi untuk PHP Built-in Server
 * Jalankan dengan: php -S localhost:8000
 */

// Handle static files (CSS, JS, images)
if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Jika request untuk file statis, serve langsung
    if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $uri)) {
        $filePath = __DIR__ . $uri;
        if (file_exists($filePath)) {
            return false; // Biarkan PHP server handle file ini
        }
    }

    // Set $_GET['url'] dari REQUEST_URI untuk routing
    $path = trim($uri, '/');
    if (!empty($path)) {
        $_GET['url'] = $path;
    }
}

// Load konfigurasi
require_once 'app/config/config.php';

// Load core classes
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';

// Jalankan aplikasi
$app = new App();
