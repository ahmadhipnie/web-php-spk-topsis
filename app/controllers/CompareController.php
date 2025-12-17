<?php

class CompareController extends Controller
{
    private $sahamModel;

    public function __construct()
    {
        $this->sahamModel = $this->model('SahamModel');
    }

    public function index()
    {
        $data['judul'] = 'Bandingkan Saham';
        
        try {
            $data['stocks'] = $this->sahamModel->getStockList();
            
            // Debug: Log stock count
            error_log("Stock list count: " . count($data['stocks']));
            
            if (empty($data['stocks'])) {
                $data['stocks'] = [];
                // You could add a flash message here
            }
        } catch (Exception $e) {
            error_log("Error in CompareController::index: " . $e->getMessage());
            $data['stocks'] = [];
        }
        
        $this->view('templates/header', $data);
        $this->view('compare/index', $data);
        $this->view('templates/footer');
    }

    public function getData()
    {
        header('Content-Type: application/json');
        
        try {
            // Get POST data
            $stock1 = $_POST['stock1'] ?? '';
            $stock2 = $_POST['stock2'] ?? '';
            $stock3 = $_POST['stock3'] ?? '';
            $days = intval($_POST['days'] ?? 30);
            
            // Log received data
            error_log("getData called with: stock1=$stock1, stock2=$stock2, stock3=$stock3, days=$days");
            
            // Validate inputs
            if (empty($stock1) || empty($stock2)) {
                echo json_encode(['success' => false, 'message' => 'Minimal pilih 2 saham']);
                return;
            }
            
            // Collect selected stocks
            $stocks = array_filter([$stock1, $stock2, $stock3]);
            
            if (count($stocks) < 2 || count($stocks) > 3) {
                echo json_encode(['success' => false, 'message' => 'Pilih 2-3 saham']);
                return;
            }
            
            // Get historical data for chart
            $chartData = $this->sahamModel->getHistoricalData($stocks, $days);
            
            // Get latest data for comparison table
            $comparisonData = $this->sahamModel->getComparisonMetrics($stocks, $days);
            
            if (empty($chartData)) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan untuk periode ini']);
                return;
            }
            
            // Format response
            $response = [
                'success' => true,
                'chartData' => $chartData,
                'comparisonData' => $comparisonData,
                'stocks' => $stocks,
                'days' => $days
            ];
            
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log("Error in CompareController::getData: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
