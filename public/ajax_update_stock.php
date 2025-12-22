<?php
// ============================================================
// PENTING: Jangan ada output apapun sebelum baris ini!
// ============================================================

// Set maximum execution time (5 menit untuk scraping)
set_time_limit(300);

// Set header JSON dan suppress error display
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering to catch any stray output
ob_start();

// Load config (app config and database constants)
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';

// Configuration
$dryRun = false; // Set true untuk testing tanpa Python
// Prefer environment override; default to 'python' which should be on PATH
$pythonBin = getenv('PYTHON_BIN') ?: 'python';
$scriptPath = realpath(__DIR__ . '/../app/python/stock_scraper_daily.py'); // resolve real path

// Ensure scriptPath exists is handled later

// Get mode
$mode = isset($_POST['mode']) ? $_POST['mode'] : 'single';

// Validate Python script exists
if (!$dryRun && !file_exists($scriptPath)) {
    // Clean any output buffer
    ob_clean();

    echo json_encode([
        'success' => false,
        'message' => 'Python script not found at: ' . $scriptPath
    ]);
    exit;
}

// Create PDO connection
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $dbh = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Database connection error: ' . $e->getMessage()
    ]);
    exit;
}

if ($mode === 'all') {
    // ========== MODE UPDATE SEMUA SAHAM ==========

    if ($dryRun) {
        ob_clean();
        echo json_encode([
            'success' => true,
            'total_stocks' => 3,
            'success_count' => 3,
            'failed_count' => 0,
            'data' => [
                ['tanggal' => date('Y-m-d'), 'kode_saham' => 'BBCA', 'nama_saham' => 'Bank Central Asia', 'sektor' => 'Perbankan', 'harga_buka' => 9000, 'harga_tertinggi' => 9100, 'harga_terendah' => 8900, 'harga_tutup' => 9050, 'volume' => 10000, 'eps' => 850, 'per' => 12.5, 'roe' => 18.2],
                ['tanggal' => date('Y-m-d'), 'kode_saham' => 'BBRI', 'nama_saham' => 'Bank Rakyat Indonesia', 'sektor' => 'Perbankan', 'harga_buka' => 5000, 'harga_tertinggi' => 5100, 'harga_terendah' => 4900, 'harga_tutup' => 5050, 'volume' => 15000, 'eps' => 600, 'per' => 10.2, 'roe' => 16.5],
                ['tanggal' => date('Y-m-d'), 'kode_saham' => 'TLKM', 'nama_saham' => 'Telkom Indonesia', 'sektor' => 'Telekomunikasi', 'harga_buka' => 4000, 'harga_tertinggi' => 4100, 'harga_terendah' => 3900, 'harga_tutup' => 4050, 'volume' => 20000, 'eps' => 280, 'per' => 11.2, 'roe' => 14.8]
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }

    // Helper for quoting arguments cross-platform
    function shell_quote($arg)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Use double quotes on Windows
            return '"' . str_replace('"', '\\"', $arg) . '"';
        }
        return escapeshellarg($arg);
    }

    // Build command safely (quote appropriately for OS)
    $cmd = shell_quote($pythonBin) . ' ' . shell_quote($scriptPath);

    // Include stderr in output for debugging
    $cmd .= ' 2>&1';

    // Execute Python script
    exec($cmd, $outputLines, $exitCode);
    $output = implode("\n", $outputLines);

    // Log raw output for debugging (optional - comment out in production)
    error_log("Python output: " . $output);
    error_log("Exit code: " . $exitCode);

    // Clean output - remove any non-JSON content
    $cleanOutput = trim($output);

    // Find JSON in output (in case there's extra text)
    $firstBrace = strpos($cleanOutput, '{');
    $lastBrace = strrpos($cleanOutput, '}');

    if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
        $cleanOutput = substr($cleanOutput, $firstBrace, $lastBrace - $firstBrace + 1);
    }

    // Try decode JSON
    $data = json_decode($cleanOutput, true);

    if (!is_array($data) || empty($data) || !isset($data['success'])) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Gagal parsing output dari Python script',
            'raw' => substr($output, 0, 500),
            'exit_code' => $exitCode,
            'json_error' => json_last_error_msg()
        ]);
        exit;
    }

    if (!$data['success']) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Python script reported failure: ' . ($data['error'] ?? 'Unknown error'),
            'raw' => substr($output, 0, 500)
        ]);
        exit;
    }

    // Insert/Update data ke database
    $inserted = 0;
    $updated = 0;
    $failed = 0;

    if (isset($data['data']) && is_array($data['data'])) {
        foreach ($data['data'] as $stock) {
            try {
                // Cek apakah data sudah ada
                $sqlCheck = "SELECT id_saham FROM saham 
                            WHERE tanggal = :tanggal AND kode_saham = :kode";
                $stmt = $dbh->prepare($sqlCheck);
                $stmt->execute([
                    ':tanggal' => $stock['tanggal'],
                    ':kode' => $stock['kode_saham']
                ]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // UPDATE
                    $sql = "UPDATE saham SET
                            nama_saham = :nama_saham,
                            sektor = :sektor,
                            harga_buka = :harga_buka,
                            harga_tertinggi = :harga_tertinggi,
                            harga_terendah = :harga_terendah,
                            harga_tutup = :harga_tutup,
                            volume = :volume,
                            EPS = :eps,
                            PER = :per,
                            ROE = :roe
                            WHERE id_saham = :id";
                    $params = array();
                    $params[':nama_saham'] = $stock['nama_saham'];
                    $params[':sektor'] = $stock['sektor'];
                    $params[':harga_buka'] = $stock['harga_buka'];
                    $params[':harga_tertinggi'] = $stock['harga_tertinggi'];
                    $params[':harga_terendah'] = $stock['harga_terendah'];
                    $params[':harga_tutup'] = $stock['harga_tutup'];
                    $params[':volume'] = $stock['volume'];
                    $params[':eps'] = isset($stock['eps']) ? $stock['eps'] : null;
                    $params[':per'] = isset($stock['per']) ? $stock['per'] : null;
                    $params[':roe'] = isset($stock['roe']) ? $stock['roe'] : null;
                    $params[':id'] = $existing['id_saham'];
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute($params);
                    $updated++;
                } else {
                    // INSERT
                    $sql = "INSERT INTO saham
                            (tanggal, kode_saham, nama_saham, sektor,
                            harga_buka, harga_tertinggi, harga_terendah, harga_tutup, volume, EPS, PER, ROE)
                            VALUES
                            (:tanggal, :kode_saham, :nama_saham, :sektor,
                            :harga_buka, :harga_tertinggi, :harga_terendah, :harga_tutup, :volume, :eps, :per, :roe)";
                    $params = [
                        ':tanggal' => $stock['tanggal'],
                        ':kode_saham' => $stock['kode_saham'],
                        ':nama_saham' => $stock['nama_saham'],
                        ':sektor' => $stock['sektor'],
                        ':harga_buka' => $stock['harga_buka'],
                        ':harga_tertinggi' => $stock['harga_tertinggi'],
                        ':harga_terendah' => $stock['harga_terendah'],
                        ':harga_tutup' => $stock['harga_tutup'],
                        ':volume' => $stock['volume'],
                        ':eps' => isset($stock['eps']) ? $stock['eps'] : null,
                        ':per' => isset($stock['per']) ? $stock['per'] : null,
                        ':roe' => isset($stock['roe']) ? $stock['roe'] : null
                    ];
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute($params);
                    $inserted++;
                }
            } catch (Exception $e) {
                $failed++;
                error_log("Failed to insert/update stock: " . $e->getMessage());
            }
        }
    }

    // Clean buffer and send final response
    ob_clean();
    echo json_encode([
        'success' => true,
        'total_stocks' => $data['total_stocks'] ?? count($data['data'] ?? []),
        'scraped' => $data['success_count'] ?? ($data['total_stocks'] ?? 0),
        'inserted' => $inserted,
        'updated' => $updated,
        'failed' => $failed,
        'timestamp' => $data['timestamp'] ?? date('Y-m-d H:i:s')
    ]);
} else {
    // ========== MODE UPDATE 1 SAHAM ==========
    $kode = isset($_POST['kode_saham']) ? strtoupper(trim($_POST['kode_saham'])) : '';

    if ($kode === '') {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Kode saham tidak boleh kosong'
        ]);
        exit;
    }

    if ($dryRun) {
        ob_clean();
        echo json_encode([
            'success' => true,
            'mode' => 'single',
            'data' => [
                'tanggal' => date('Y-m-d'),
                'kode_saham' => $kode,
                'nama_saham' => 'Sample Stock',
                'sektor' => 'Testing',
                'harga_buka' => 1000,
                'harga_tertinggi' => 1100,
                'harga_terendah' => 900,
                'harga_tutup' => 1050,
                'volume' => 1000,
                'eps' => 100,
                'per' => 15,
                'roe' => 20
            ]
        ]);
        exit;
    }

    $cmd = shell_quote($pythonBin) . ' ' . shell_quote($scriptPath) . ' ' . shell_quote($kode) . ' 2>&1';
    exec($cmd, $outputLines, $exitCode);
    $output = implode("\n", $outputLines);

    // Clean output
    $cleanOutput = trim($output);
    $firstBrace = strpos($cleanOutput, '{');
    $lastBrace = strrpos($cleanOutput, '}');

    if ($firstBrace !== false && $lastBrace !== false) {
        $cleanOutput = substr($cleanOutput, $firstBrace, $lastBrace - $firstBrace + 1);
    }

    $data = json_decode($cleanOutput, true);

    if (!is_array($data) || empty($data) || !isset($data['success']) || !$data['success']) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => $data['error'] ?? 'Scraping gagal',
            'raw' => substr($output, 0, 500),
            'exit_code' => $exitCode
        ]);
        exit;
    }

    $stock = $data['data'];

    try {
        $sqlCheck = "SELECT id_saham FROM saham WHERE tanggal = :tanggal AND kode_saham = :kode";
        $stmt = $dbh->prepare($sqlCheck);
        $stmt->execute([
            ':tanggal' => $stock['tanggal'],
            ':kode' => $stock['kode_saham']
        ]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // UPDATE
            $sql = "UPDATE saham SET
                    nama_saham = :nama_saham,
                    sektor = :sektor,
                    harga_buka = :harga_buka,
                    harga_tertinggi = :harga_tertinggi,
                    harga_terendah = :harga_terendah,
                    harga_tutup = :harga_tutup,
                    volume = :volume,
                    EPS = :eps,
                    PER = :per,
                    ROE = :roe
                    WHERE id_saham = :id";
            $params = [
                ':nama_saham' => $stock['nama_saham'],
                ':sektor' => $stock['sektor'],
                ':harga_buka' => $stock['harga_buka'],
                ':harga_tertinggi' => $stock['harga_tertinggi'],
                ':harga_terendah' => $stock['harga_terendah'],
                ':harga_tutup' => $stock['harga_tutup'],
                ':volume' => $stock['volume'],
                ':eps' => isset($stock['eps']) ? $stock['eps'] : null,
                ':per' => isset($stock['per']) ? $stock['per'] : null,
                ':roe' => isset($stock['roe']) ? $stock['roe'] : null,
                ':id' => $existing['id_saham']
            ];
            $stmt = $dbh->prepare($sql);
            $stmt->execute($params);
            $mode = 'update';
        } else {
            // INSERT
            $sql = "INSERT INTO saham
                    (tanggal, kode_saham, nama_saham, sektor,
                    harga_buka, harga_tertinggi, harga_terendah, harga_tutup, volume, EPS, PER, ROE)
                    VALUES
                    (:tanggal, :kode_saham, :nama_saham, :sektor,
                    :harga_buka, :harga_tertinggi, :harga_terendah, :harga_tutup, :volume, :eps, :per, :roe)";
            $params = [
                ':tanggal' => $stock['tanggal'],
                ':kode_saham' => $stock['kode_saham'],
                ':nama_saham' => $stock['nama_saham'],
                ':sektor' => $stock['sektor'],
                ':harga_buka' => $stock['harga_buka'],
                ':harga_tertinggi' => $stock['harga_tertinggi'],
                ':harga_terendah' => $stock['harga_terendah'],
                ':harga_tutup' => $stock['harga_tutup'],
                ':volume' => $stock['volume'],
                ':eps' => isset($stock['eps']) ? $stock['eps'] : null,
                ':per' => isset($stock['per']) ? $stock['per'] : null,
                ':roe' => isset($stock['roe']) ? $stock['roe'] : null
            ];
            $stmt = $dbh->prepare($sql);
            $stmt->execute($params);
            $mode = 'insert';
        }

        ob_clean();
        echo json_encode([
            'success' => true,
            'mode' => $mode,
            'data' => $stock
        ]);
    } catch (Exception $e) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

// Flush buffer and exit
ob_end_flush();
exit;
