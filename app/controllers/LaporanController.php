<?php

class LaporanController extends Controller
{
    private $sahamModel;

    public function __construct()
    {
        $this->sahamModel = $this->model('SahamModel');
    }

    /**
     * API Endpoint: Get report data for preview
     * Route: /laporan/getReportData
     * Parameters: periode (7, 30, 90, 365)
     */
    public function getReportData()
    {
        header('Content-Type: application/json');

        try {
            // Ensure periode is integer
            $periode = isset($_GET['periode']) ? (int)$_GET['periode'] : 30;

            // Validate periode (must be numeric and positive)
            if ($periode <= 0) {
                $periode = 30;
            }

            // Calculate date ranges
            $tanggal_akhir = date('Y-m-d');
            $tanggal_mulai = date('Y-m-d', strtotime("-$periode days"));

            // For weekly comparison
            $week_ago_start = date('Y-m-d', strtotime("-" . ($periode * 2) . " days"));
            $week_ago_end = date('Y-m-d', strtotime("-$periode days"));

            // 1. Get Top 10 Stocks by Return
            $topStocks = $this->sahamModel->getTopStocksByReturn($tanggal_mulai, $tanggal_akhir, 10);

            // 2. Get Weekly Ranking Comparison (for top 10 stocks)
            $stockCodes = array_map(function ($stock) {
                return $stock->kode_saham;
            }, $topStocks);
            $previousRanking = $this->sahamModel->getStockRanking($week_ago_start, $week_ago_end, $stockCodes);

            // Merge with ranking changes
            $topStocksWithChanges = [];
            foreach ($topStocks as $index => $stock) {
                $currentRank = $index + 1;
                $previousRank = $this->findPreviousRank($stock->kode_saham, $previousRanking);

                $rankChange = 0;
                $rankStatus = 'new';

                if ($previousRank !== null) {
                    $rankChange = $previousRank - $currentRank; // Positive = naik, Negative = turun
                    if ($rankChange > 0) {
                        $rankStatus = 'up';
                    } elseif ($rankChange < 0) {
                        $rankStatus = 'down';
                    } else {
                        $rankStatus = 'same';
                    }
                }

                $topStocksWithChanges[] = [
                    'rank' => $currentRank,
                    'kode_saham' => $stock->kode_saham,
                    'nama_saham' => $stock->nama_saham,
                    'sektor' => $stock->sektor,
                    'return' => round($stock->return_pct, 2),
                    'harga_awal' => floatval($stock->harga_awal),
                    'harga_akhir' => floatval($stock->harga_akhir),
                    'eps' => floatval($stock->EPS),
                    'per' => floatval($stock->PER),
                    'roe' => floatval($stock->ROE),
                    'rank_change' => $rankChange,
                    'rank_status' => $rankStatus,
                    'previous_rank' => $previousRank
                ];
            }

            // 3. Get Sector Performance
            $sectorPerformance = $this->sahamModel->getSectorPerformance($tanggal_mulai, $tanggal_akhir);

            // Calculate average return per sector
            $sectors = [];
            foreach ($sectorPerformance as $row) {
                $sektor = $row->sektor;
                $harga_awal = floatval($row->harga_awal);
                $harga_akhir = floatval($row->harga_akhir);

                if ($harga_awal > 0) {
                    $return = (($harga_akhir - $harga_awal) / $harga_awal) * 100;
                } else {
                    $return = 0;
                }

                if (!isset($sectors[$sektor])) {
                    $sectors[$sektor] = [
                        'sektor' => $sektor,
                        'returns' => [],
                        'count' => 0
                    ];
                }

                $sectors[$sektor]['returns'][] = $return;
                $sectors[$sektor]['count']++;
            }

            $sectorData = [];
            foreach ($sectors as $sektor => $data) {
                $avgReturn = array_sum($data['returns']) / $data['count'];
                $sectorData[] = [
                    'sektor' => $sektor,
                    'avg_return' => round($avgReturn, 2),
                    'stock_count' => $data['count']
                ];
            }

            // Sort by avg_return descending
            usort($sectorData, function ($a, $b) {
                return $b['avg_return'] <=> $a['avg_return'];
            });

            // Get top 3 dominating sectors
            $dominatingSectors = array_slice($sectorData, 0, 3);

            // Summary statistics
            $summary = [
                'total_stocks' => count($topStocksWithChanges),
                'best_stock' => $topStocksWithChanges[0] ?? null,
                'best_sector' => $dominatingSectors[0] ?? null,
                'average_return' => round(array_sum(array_column($topStocksWithChanges, 'return')) / count($topStocksWithChanges), 2),
                'periode' => $periode . ' hari',
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_akhir' => $tanggal_akhir,
                'generated_at' => date('Y-m-d H:i:s')
            ];

            echo json_encode([
                'success' => true,
                'data' => [
                    'top_stocks' => $topStocksWithChanges,
                    'sectors' => $dominatingSectors,
                    'all_sectors' => $sectorData,
                    'summary' => $summary
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
     * Generate and download PDF report
     * Route: /laporan/downloadPDF
     */
    public function downloadPDF()
    {
        try {
            // Ensure periode is integer
            $periode = isset($_GET['periode']) ? (int)$_GET['periode'] : 30;

            // Validate periode
            if ($periode <= 0) {
                $periode = 30;
            }

            // Get report data
            $_GET['periode'] = $periode;
            ob_start();
            $this->getReportData();
            $jsonOutput = ob_get_clean();
            $reportData = json_decode($jsonOutput, true);

            if (!$reportData['success']) {
                die('Error generating report data');
            }

            $data = $reportData['data'];
            $summary = $data['summary'];

            // Generate HTML content for PDF
            $html = $this->generateReportHTML($data, $summary);

            // Output as printable HTML (user can save as PDF from browser)
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: inline; filename="Laporan_Saham_' . date('Ymd_His') . '.html"');

            echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Saham</title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none; }
            @page { margin: 1cm; }
        }
        @media screen {
            body { max-width: 900px; margin: 20px auto; padding: 20px; }
            .no-print { 
                position: fixed; 
                top: 20px; 
                right: 20px; 
                z-index: 1000;
                background: #3b82f6;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                border: none;
                font-size: 14px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            }
            .no-print:hover { background: #2563eb; }
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left; 
        }
        th { 
            background-color: #3b82f6; 
            color: white; 
            font-weight: bold;
        }
        tr:nth-child(even) { background-color: #f9fafb; }
        h1 { color: #1e40af; margin-top: 0; font-size: 24px; }
        h2 { 
            color: #3b82f6; 
            border-bottom: 2px solid #3b82f6; 
            padding-bottom: 8px;
            margin-top: 30px;
            font-size: 18px;
        }
        .text-success { color: #10b981; font-weight: bold; }
        .text-danger { color: #ef4444; font-weight: bold; }
        .text-primary { color: #3b82f6; font-weight: bold; }
    </style>
    <script>
        function printPDF() {
            window.print();
        }
    </script>
</head>
<body>
    <button class="no-print" onclick="printPDF()">🖨️ Print / Save as PDF</button>
    ' . $html . '
</body>
</html>';
            exit;
        } catch (Exception $e) {
            die('Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate and download CSV report
     * Route: /laporan/downloadCSV
     */
    public function downloadCSV()
    {
        try {
            // Ensure periode is integer
            $periode = isset($_GET['periode']) ? (int)$_GET['periode'] : 30;

            // Validate periode
            if ($periode <= 0) {
                $periode = 30;
            }

            // Get report data
            $_GET['periode'] = $periode;
            ob_start();
            $this->getReportData();
            $jsonOutput = ob_get_clean();
            $reportData = json_decode($jsonOutput, true);

            if (!$reportData['success']) {
                die('Error generating report data');
            }

            $data = $reportData['data'];

            // Set CSV headers
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="Laporan_Saham_' . date('Ymd_His') . '.csv"');

            // Open output stream
            $output = fopen('php://output', 'w');

            // Write BOM for Excel UTF-8 compatibility
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Section 1: Summary
            fputcsv($output, ['=== RINGKASAN LAPORAN ===']);
            fputcsv($output, ['Periode', $data['summary']['periode']]);
            fputcsv($output, ['Tanggal Mulai', $data['summary']['tanggal_mulai']]);
            fputcsv($output, ['Tanggal Akhir', $data['summary']['tanggal_akhir']]);
            fputcsv($output, ['Rata-rata Return', $data['summary']['average_return'] . '%']);
            fputcsv($output, ['Sektor Terbaik', $data['summary']['best_sector']['sektor'] ?? '-']);
            fputcsv($output, ['Saham Terbaik', ($data['summary']['best_stock']['kode_saham'] ?? '-') . ' (' . ($data['summary']['best_stock']['return'] ?? 0) . '%)']);
            fputcsv($output, []);

            // Section 2: Top Stocks
            fputcsv($output, ['=== TOP 10 SAHAM TERBAIK ===']);
            fputcsv($output, ['Rank', 'Kode', 'Nama Saham', 'Sektor', 'Return (%)', 'Harga Awal', 'Harga Akhir', 'EPS', 'PER', 'ROE', 'Perubahan Rank', 'Status']);

            foreach ($data['top_stocks'] as $stock) {
                $rankChangeText = '';
                if ($stock['rank_status'] === 'up') {
                    $rankChangeText = '+' . $stock['rank_change'];
                } elseif ($stock['rank_status'] === 'down') {
                    $rankChangeText = $stock['rank_change'];
                } elseif ($stock['rank_status'] === 'same') {
                    $rankChangeText = '0';
                } else {
                    $rankChangeText = 'New';
                }

                fputcsv($output, [
                    $stock['rank'],
                    $stock['kode_saham'],
                    $stock['nama_saham'],
                    $stock['sektor'],
                    $stock['return'],
                    $stock['harga_awal'],
                    $stock['harga_akhir'],
                    $stock['eps'],
                    $stock['per'],
                    $stock['roe'],
                    $rankChangeText,
                    ucfirst($stock['rank_status'])
                ]);
            }

            fputcsv($output, []);

            // Section 3: Sector Performance
            fputcsv($output, ['=== PERFORMA SEKTOR ===']);
            fputcsv($output, ['Sektor', 'Rata-rata Return (%)', 'Jumlah Saham']);

            foreach ($data['all_sectors'] as $sector) {
                fputcsv($output, [
                    $sector['sektor'],
                    $sector['avg_return'],
                    $sector['stock_count']
                ]);
            }

            fclose($output);
            exit;
        } catch (Exception $e) {
            die('Error generating CSV: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Find previous rank of a stock
     */
    private function findPreviousRank($kode_saham, $previousRanking)
    {
        foreach ($previousRanking as $index => $stock) {
            if ($stock->kode_saham === $kode_saham) {
                return $index + 1;
            }
        }
        return null;
    }

    /**
     * Helper: Generate HTML content for PDF
     */
    private function generateReportHTML($data, $summary)
    {
        $html = '
<h1 style="text-align: center; color: #1e40af; border-bottom: 3px solid #3b82f6; padding-bottom: 10px;">
    📊 Laporan Analisis Saham
</h1>
<p style="text-align: center; color: #6b7280; margin-bottom: 30px;">
    Periode: ' . htmlspecialchars($summary['tanggal_mulai']) . ' s/d ' . htmlspecialchars($summary['tanggal_akhir']) . '<br>
    Dibuat: ' . htmlspecialchars($summary['generated_at']) . '
</p>

<h2>📈 Ringkasan</h2>
<table>
    <tr>
        <th style="width: 200px;">Metrik</th>
        <th>Nilai</th>
    </tr>
    <tr>
        <td>Periode Analisis</td>
        <td>' . htmlspecialchars($summary['periode']) . '</td>
    </tr>
    <tr>
        <td>Rata-rata Return</td>
        <td class="' . ($summary['average_return'] >= 0 ? 'text-success' : 'text-danger') . '">
            ' . $summary['average_return'] . '%
        </td>
    </tr>
    <tr>
        <td>Sektor Terbaik</td>
        <td><strong>' . htmlspecialchars($summary['best_sector']['sektor'] ?? '-') . '</strong> 
            (' . ($summary['best_sector']['avg_return'] ?? 0) . '%)
        </td>
    </tr>
    <tr>
        <td>Saham Terbaik</td>
        <td><strong>' . htmlspecialchars($summary['best_stock']['kode_saham'] ?? '-') . '</strong> 
            (' . ($summary['best_stock']['return'] ?? 0) . '%)
        </td>
    </tr>
</table>

<h2>🏆 Top 10 Saham Terbaik</h2>
<table>
    <tr>
        <th style="width: 40px;">#</th>
        <th>Kode</th>
        <th>Nama Saham</th>
        <th>Sektor</th>
        <th>Return</th>
        <th>Perubahan Rank</th>
    </tr>';

        foreach ($data['top_stocks'] as $stock) {
            $rankIcon = '';
            $rankClass = '';

            if ($stock['rank_status'] === 'up') {
                $rankIcon = '↑ +' . $stock['rank_change'];
                $rankClass = 'text-success';
            } elseif ($stock['rank_status'] === 'down') {
                $rankIcon = '↓ ' . $stock['rank_change'];
                $rankClass = 'text-danger';
            } elseif ($stock['rank_status'] === 'same') {
                $rankIcon = '→ Tetap';
                $rankClass = '';
            } else {
                $rankIcon = '★ Baru';
                $rankClass = 'text-primary';
            }

            $returnClass = $stock['return'] >= 0 ? 'text-success' : 'text-danger';

            $html .= '
    <tr>
        <td style="text-align: center; font-weight: bold;">' . $stock['rank'] . '</td>
        <td><strong>' . htmlspecialchars($stock['kode_saham']) . '</strong></td>
        <td>' . htmlspecialchars($stock['nama_saham']) . '</td>
        <td>' . htmlspecialchars($stock['sektor']) . '</td>
        <td class="' . $returnClass . '" style="font-weight: bold;">' . $stock['return'] . '%</td>
        <td class="' . $rankClass . '">' . $rankIcon . '</td>
    </tr>';
        }

        $html .= '
</table>

<h2>📊 Sektor yang Mendominasi (Top 3)</h2>
<table>
    <tr>
        <th style="width: 40px;">#</th>
        <th>Sektor</th>
        <th>Rata-rata Return</th>
        <th>Jumlah Saham</th>
    </tr>';

        foreach ($data['sectors'] as $index => $sector) {
            $returnClass = $sector['avg_return'] >= 0 ? 'text-success' : 'text-danger';
            $html .= '
    <tr>
        <td style="text-align: center; font-weight: bold;">' . ($index + 1) . '</td>
        <td><strong>' . htmlspecialchars($sector['sektor']) . '</strong></td>
        <td class="' . $returnClass . '" style="font-weight: bold;">' . $sector['avg_return'] . '%</td>
        <td>' . $sector['stock_count'] . ' saham</td>
    </tr>';
        }

        $html .= '
</table>

<p style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; color: #6b7280; font-size: 10px; text-align: center;">
    <em>Laporan ini dibuat otomatis oleh SahamPintar berdasarkan data historis saham.<br>
    Informasi ini bersifat edukatif dan bukan rekomendasi investasi.</em>
</p>';

        return $html;
    }
}
