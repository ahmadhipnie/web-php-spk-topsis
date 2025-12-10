<?php

/**
 * Router untuk PHP Built-in Server
 * Untuk menangani URL rewriting tanpa /public
 */

// Ambil URI path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = urldecode($uri);

// Handle file statis (CSS, JS, images, dll)
// Cek di folder assets
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $uri)) {
    // Cek apakah file ada di public/assets
    $filePath = __DIR__ . $uri;
    if (file_exists($filePath)) {
        return false; // PHP server akan serve file ini
    }
}

// Clean URI - hapus leading/trailing slash
$uri = trim($uri, '/');

// Set $_GET['url'] untuk routing MVC
if (!empty($uri)) {
    $_GET['url'] = $uri;
}

// Load index.php
require __DIR__ . '/index.php';
