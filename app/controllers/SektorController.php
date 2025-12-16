<?php

class SektorController extends Controller
{
    private $sahamModel;

    public function __construct()
    {
        $this->sahamModel = $this->model('SahamModel');
    }

    /**
     * API Endpoint: Get sector performance (avg return per sector)
     * Route: /sektor/getSectorPerformance
     * Parameters: periode (7, 30, 90, 365)
     */
    public function getSectorPerformance()
    {
        header('Content-Type: application/json');

        try {
            $periode = $_GET['periode'] ?? '30';
            
            // Calculate date range
            $tanggal_akhir = date('Y-m-d');
            $tanggal_mulai = date('Y-m-d', strtotime("-$periode days"));

            // Get sector performance from model
            $sectorData = $this->sahamModel->getSectorPerformance($tanggal_mulai, $tanggal_akhir);

            // Calculate average return for each sector
            $sectorPerformance = [];
            
            foreach ($sectorData as $row) {
                $sektor = $row->sektor;
                $kode_saham = $row->kode_saham;
                $harga_awal = floatval($row->harga_awal);
                $harga_akhir = floatval($row->harga_akhir);
                
                // Calculate return percentage
                if ($harga_awal > 0) {
                    $return = (($harga_akhir - $harga_awal) / $harga_awal) * 100;
                } else {
                    $return = 0;
                }

                // Group by sector
                if (!isset($sectorPerformance[$sektor])) {
                    $sectorPerformance[$sektor] = [
                        'sektor' => $sektor,
                        'returns' => [],
                        'count' => 0
                    ];
                }

                $sectorPerformance[$sektor]['returns'][] = $return;
                $sectorPerformance[$sektor]['count']++;
            }

            // Calculate average return per sector
            $result = [];
            foreach ($sectorPerformance as $sektor => $data) {
                $avgReturn = array_sum($data['returns']) / $data['count'];
                $result[] = [
                    'sektor' => $sektor,
                    'avg_return' => round($avgReturn, 2),
                    'stock_count' => $data['count']
                ];
            }

            // Sort by avg_return descending
            usort($result, function($a, $b) {
                return $b['avg_return'] <=> $a['avg_return'];
            });

            echo json_encode([
                'success' => true,
                'data' => $result,
                'info' => [
                    'periode' => $periode . ' hari',
                    'tanggal_mulai' => $tanggal_mulai,
                    'tanggal_akhir' => $tanggal_akhir,
                    'total_sectors' => count($result)
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
     * API Endpoint: Get top stocks by sector
     * Route: /sektor/getTopStocks
     * Parameters: periode (7, 30, 90, 365)
     */
    public function getTopStocks()
    {
        header('Content-Type: application/json');

        try {
            $periode = $_GET['periode'] ?? '30';
            
            // Calculate date range
            $tanggal_akhir = date('Y-m-d');
            $tanggal_mulai = date('Y-m-d', strtotime("-$periode days"));

            // Get all sectors
            $sectors = $this->sahamModel->getAllSectors();

            $result = [];

            foreach ($sectors as $sector) {
                $sektor = $sector->sektor;
                
                // Get top 3 stocks in this sector
                $topStocks = $this->sahamModel->getTopStocksBySektor($sektor, $tanggal_mulai, $tanggal_akhir, 3);

                if (!empty($topStocks)) {
                    $stocks = [];
                    foreach ($topStocks as $stock) {
                        $harga_awal = floatval($stock->harga_awal);
                        $harga_akhir = floatval($stock->harga_akhir);
                        
                        // Calculate return percentage
                        if ($harga_awal > 0) {
                            $return = (($harga_akhir - $harga_awal) / $harga_awal) * 100;
                        } else {
                            $return = 0;
                        }

                        $stocks[] = [
                            'kode_saham' => $stock->kode_saham,
                            'nama_saham' => $stock->nama_saham,
                            'return' => round($return, 2),
                            'harga_awal' => $harga_awal,
                            'harga_akhir' => $harga_akhir,
                            'eps' => floatval($stock->EPS),
                            'per' => floatval($stock->PER),
                            'roe' => floatval($stock->ROE)
                        ];
                    }

                    $result[] = [
                        'sektor' => $sektor,
                        'top_stocks' => $stocks
                    ];
                }
            }

            echo json_encode([
                'success' => true,
                'data' => $result,
                'info' => [
                    'periode' => $periode . ' hari',
                    'tanggal_mulai' => $tanggal_mulai,
                    'tanggal_akhir' => $tanggal_akhir,
                    'total_sectors' => count($result)
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
