<?php

class SahamController extends Controller
{
    private $sahamModel;

    public function __construct()
    {
        $this->sahamModel = $this->model('SahamModel');
    }

    /**
     * API Endpoint: Get stock historical data
     * Route: /saham/getStockData
     */
    public function getStockData()
    {
        // Set header JSON
        header('Content-Type: application/json');

        // Ambil parameter dari GET
        $kode_saham = $_GET['kode_saham'] ?? 'BBCA';
        $periode = $_GET['periode'] ?? '30'; // 7, 30, 90, 180, 365

        // Hitung tanggal mulai berdasarkan periode
        $tanggal_akhir = date('Y-m-d');
        $tanggal_mulai = date('Y-m-d', strtotime("-$periode days"));

        try {
            // Ambil data historis dari model
            $data = $this->sahamModel->getStockHistorical($kode_saham, $tanggal_mulai, $tanggal_akhir);

            // Format data untuk ApexCharts
            $chartData = [
                'dates' => [],
                'prices' => [
                    'open' => [],
                    'high' => [],
                    'low' => [],
                    'close' => []
                ],
                'ohlc' => [], // Format untuk candlestick [timestamp, open, high, low, close]
                'volume' => []
            ];

            foreach ($data as $row) {
                $timestamp = strtotime($row->tanggal) * 1000; // Convert to milliseconds
                $chartData['dates'][] = date('Y-m-d', strtotime($row->tanggal));
                
                // Data untuk line chart
                $chartData['prices']['open'][] = floatval($row->harga_buka);
                $chartData['prices']['high'][] = floatval($row->harga_tertinggi);
                $chartData['prices']['low'][] = floatval($row->harga_terendah);
                $chartData['prices']['close'][] = floatval($row->harga_tutup);
                
                // Data untuk candlestick
                $chartData['ohlc'][] = [
                    'x' => $timestamp,
                    'y' => [
                        floatval($row->harga_buka),
                        floatval($row->harga_tertinggi),
                        floatval($row->harga_terendah),
                        floatval($row->harga_tutup)
                    ]
                ];
                
                // Data volume
                $chartData['volume'][] = [
                    'x' => $timestamp,
                    'y' => intval($row->volume)
                ];
            }

            // Hitung Moving Average 7 hari
            $chartData['ma7'] = $this->calculateMovingAverage($chartData['prices']['close'], 7);

            echo json_encode([
                'success' => true,
                'data' => $chartData,
                'info' => [
                    'kode_saham' => $kode_saham,
                    'periode' => $periode . ' hari',
                    'tanggal_mulai' => $tanggal_mulai,
                    'tanggal_akhir' => $tanggal_akhir,
                    'total_data' => count($data)
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * API Endpoint: Get list of available stocks
     * Route: /saham/getStockList
     */
    public function getStockList()
    {
        header('Content-Type: application/json');

        try {
            $stocks = $this->sahamModel->getAvailableStocks();
            
            echo json_encode([
                'success' => true,
                'data' => $stocks
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Download stock data as CSV
     * Route: /saham/downloadCSV
     */
    public function downloadCSV()
    {
        $kode_saham = $_GET['kode_saham'] ?? 'BBCA';
        $periode = $_GET['periode'] ?? '30';

        $tanggal_akhir = date('Y-m-d');
        $tanggal_mulai = date('Y-m-d', strtotime("-$periode days"));

        try {
            $data = $this->sahamModel->getStockHistorical($kode_saham, $tanggal_mulai, $tanggal_akhir);

            // Set headers untuk download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $kode_saham . '_' . date('Ymd') . '.csv"');

            // Buka output stream
            $output = fopen('php://output', 'w');

            // Tulis header CSV
            fputcsv($output, ['Tanggal', 'Kode Saham', 'Harga Buka', 'Harga Tertinggi', 'Harga Terendah', 'Harga Tutup', 'Volume', 'Kinerja (%)']);

            // Tulis data
            foreach ($data as $row) {
                $kinerja = 0;
                if ($row->harga_buka > 0) {
                    $kinerja = (($row->harga_tutup - $row->harga_buka) / $row->harga_buka) * 100;
                }

                fputcsv($output, [
                    $row->tanggal,
                    $row->kode_saham,
                    $row->harga_buka,
                    $row->harga_tertinggi,
                    $row->harga_terendah,
                    $row->harga_tutup,
                    $row->volume,
                    number_format($kinerja, 2)
                ]);
            }

            fclose($output);
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Helper: Calculate Moving Average
     */
    private function calculateMovingAverage($data, $period)
    {
        $ma = [];
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            if ($i < $period - 1) {
                $ma[] = null; // Tidak cukup data untuk MA
            } else {
                $sum = 0;
                for ($j = 0; $j < $period; $j++) {
                    $sum += $data[$i - $j];
                }
                $ma[] = $sum / $period;
            }
        }

        return $ma;
    }
}
