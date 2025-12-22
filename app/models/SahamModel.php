<?php

/**
 * SahamModel
 * Model untuk mengelola data saham
 */
class SahamModel
{
    private $db;
    private $table = 'saham';

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Get semua data saham
     * @return array
     */
    public function getAllSaham()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    /**
     * Get saham berdasarkan ID
     * @param int $id
     * @return object
     */
    public function getSahamById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id_saham = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Tambah data saham
     * @param array $data
     * @return bool
     */
    public function tambahSaham($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (kode_saham, nama_saham, harga, volume, market_cap) 
                  VALUES 
                  (:kode_saham, :nama_saham, :harga, :volume, :market_cap)";

        $this->db->query($query);
        $this->db->bind(':kode_saham', $data['kode_saham']);
        $this->db->bind(':nama_saham', $data['nama_saham']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':volume', $data['volume']);
        $this->db->bind(':market_cap', $data['market_cap']);

        return $this->db->execute();
    }

    /**
     * Update data saham
     * @param array $data
     * @return bool
     */
    public function updateSaham($data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET kode_saham = :kode_saham, 
                      nama_saham = :nama_saham, 
                      harga = :harga, 
                      volume = :volume, 
                      market_cap = :market_cap 
                  WHERE id_saham = :id";

        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':kode_saham', $data['kode_saham']);
        $this->db->bind(':nama_saham', $data['nama_saham']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':volume', $data['volume']);
        $this->db->bind(':market_cap', $data['market_cap']);

        return $this->db->execute();
    }

    /**
     * Hapus data saham
     * @param int $id
     * @return bool
     */
    public function hapusSaham($id)
    {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id_saham = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Cari saham berdasarkan keyword
     * @param string $keyword
     * @return array
     */
    public function cariSaham($keyword)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE kode_saham LIKE :keyword 
                  OR nama_saham LIKE :keyword";

        $this->db->query($query);
        $this->db->bind(':keyword', "%$keyword%");

        return $this->db->resultSet();
    }

    /**
     * Get saham berdasarkan kriteria TOPSIS (dengan filter)
     * @param string $sektor
     * @param float $budget
     * @return array
     */
    public function getSahamForTopsis($sektor = 'semua', $budget = 0)
    {
        // Query dengan LEFT JOIN untuk memastikan hanya ambil record terbaru per kode_saham
        // Tidak ada duplikasi karena LEFT JOIN + WHERE b.id_saham IS NULL
        if ($sektor === 'semua') {
            $query = "SELECT a.* FROM " . $this->table . " a
                      LEFT JOIN " . $this->table . " b 
                        ON a.kode_saham = b.kode_saham 
                        AND (a.tanggal < b.tanggal OR (a.tanggal = b.tanggal AND a.id_saham < b.id_saham))
                      WHERE b.id_saham IS NULL
                      AND a.harga_tutup <= :budget 
                      AND a.EPS IS NOT NULL 
                      AND a.PER IS NOT NULL 
                      AND a.ROE IS NOT NULL
                      ORDER BY a.harga_tutup ASC";
        } else {
            $query = "SELECT a.* FROM " . $this->table . " a
                      LEFT JOIN " . $this->table . " b 
                        ON a.kode_saham = b.kode_saham 
                        AND (a.tanggal < b.tanggal OR (a.tanggal = b.tanggal AND a.id_saham < b.id_saham))
                      WHERE b.id_saham IS NULL
                      AND a.sektor = :sektor
                      AND a.harga_tutup <= :budget 
                      AND a.EPS IS NOT NULL 
                      AND a.PER IS NOT NULL 
                      AND a.ROE IS NOT NULL
                      ORDER BY a.harga_tutup ASC";
        }

        $this->db->query($query);
        $this->db->bind(':budget', $budget);

        if ($sektor !== 'semua') {
            $this->db->bind(':sektor', $sektor);
        }

        return $this->db->resultSet();
    }

    /**
     * Insert data investor
     * @param array $data
     * @return int Last inserted ID
     */
    public function insertInvestor($data)
    {
        $query = "INSERT INTO investor (nama, preferensi, kriteria) 
                  VALUES (:nama, :preferensi, :kriteria)";

        $this->db->query($query);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':preferensi', $data['preferensi']);
        $this->db->bind(':kriteria', $data['kriteria']);

        $this->db->execute();

        // Return last inserted ID
        return $this->db->lastInsertId();
    }

    /**
     * Insert data rekomendasi
     * @param array $data
     * @return bool
     */
    public function insertRekomendasi($data)
    {
        $query = "INSERT INTO rekomendasi 
                  (id_investor, id_saham, peringkat, tanggal_rekomendasi) 
                  VALUES 
                  (:id_investor, :id_saham, :peringkat, :tanggal_rekomendasi)";

        $this->db->query($query);
        $this->db->bind(':id_investor', $data['id_investor']);
        $this->db->bind(':id_saham', $data['id_saham']);
        $this->db->bind(':peringkat', $data['peringkat']);
        $this->db->bind(':tanggal_rekomendasi', $data['tanggal_rekomendasi']);

        return $this->db->execute();
    }

    /**
     * Insert hasil perhitungan TOPSIS
     * @param array $data
     * @return bool
     */
    public function insertTopsis($data)
    {
        $query = "INSERT INTO topsis 
                  (id_saham, normalisasi_data, jarak_solusi_ideal, bobot_kriteria) 
                  VALUES 
                  (:id_saham, :normalisasi_data, :jarak_solusi_ideal, :bobot_kriteria)";

        $this->db->query($query);
        $this->db->bind(':id_saham', $data['id_saham']);
        $this->db->bind(':normalisasi_data', $data['normalisasi_data']);
        $this->db->bind(':jarak_solusi_ideal', $data['jarak_solusi_ideal']);
        $this->db->bind(':bobot_kriteria', $data['bobot_kriteria']);

        return $this->db->execute();
    }

    /**
     * Get rekomendasi berdasarkan ID investor
     * @param int $id_investor
     * @return array
     */
    public function getRekomendasiByInvestor($id_investor)
    {
        $query = "SELECT r.*, s.*, r.peringkat, r.tanggal_rekomendasi
                  FROM rekomendasi r
                  JOIN saham s ON r.id_saham = s.id_saham
                  WHERE r.id_investor = :id_investor
                  ORDER BY r.peringkat ASC";

        $this->db->query($query);
        $this->db->bind(':id_investor', $id_investor);

        return $this->db->resultSet();
    }

    /**
     * Get data investor berdasarkan ID
     * @param int $id_investor
     * @return object
     */
    public function getInvestorById($id_investor)
    {
        $query = "SELECT * FROM investor WHERE id_investor = :id_investor";

        $this->db->query($query);
        $this->db->bind(':id_investor', $id_investor);

        return $this->db->single();
    }

    /**
     * Get stock historical data untuk chart
     * @param string $kode_saham
     * @param string $tanggal_mulai
     * @param string $tanggal_akhir
     * @return array
     */
    public function getStockHistorical($kode_saham, $tanggal_mulai, $tanggal_akhir)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE kode_saham = :kode_saham 
                  AND tanggal >= :tanggal_mulai 
                  AND tanggal <= :tanggal_akhir 
                  ORDER BY tanggal ASC";

        $this->db->query($query);
        $this->db->bind(':kode_saham', $kode_saham);
        $this->db->bind(':tanggal_mulai', $tanggal_mulai);
        $this->db->bind(':tanggal_akhir', $tanggal_akhir);

        return $this->db->resultSet();
    }

    /**
     * Get list of available stock codes (unique)
     * @return array
     */
    public function getAvailableStocks()
    {
        $query = "SELECT DISTINCT kode_saham, nama_saham, sektor 
                  FROM " . $this->table . " 
                  ORDER BY kode_saham ASC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Get sector performance data for calculating average return
     * Returns first and last price for each stock in date range
     * @param string $tanggal_mulai
     * @param string $tanggal_akhir
     * @return array
     */
    public function getSectorPerformance($tanggal_mulai, $tanggal_akhir)
    {
        $query = "SELECT 
                    s.kode_saham,
                    s.nama_saham,
                    s.sektor,
                    (SELECT harga_tutup FROM " . $this->table . " 
                     WHERE kode_saham = s.kode_saham 
                     AND tanggal >= :tanggal_mulai 
                     ORDER BY tanggal ASC LIMIT 1) as harga_awal,
                    (SELECT harga_tutup FROM " . $this->table . " 
                     WHERE kode_saham = s.kode_saham 
                     AND tanggal <= :tanggal_akhir 
                     ORDER BY tanggal DESC LIMIT 1) as harga_akhir
                  FROM (SELECT DISTINCT kode_saham, nama_saham, sektor 
                        FROM " . $this->table . ") s
                  ORDER BY s.sektor, s.kode_saham";

        $this->db->query($query);
        $this->db->bind(':tanggal_mulai', $tanggal_mulai);
        $this->db->bind(':tanggal_akhir', $tanggal_akhir);

        return $this->db->resultSet();
    }

    /**
     * Get top performing stocks in a specific sector
     * @param string $sektor
     * @param string $tanggal_mulai
     * @param string $tanggal_akhir
     * @param int $limit
     * @return array
     */
    public function getTopStocksBySektor($sektor, $tanggal_mulai, $tanggal_akhir, $limit = 3)
    {
        $query = "SELECT 
                    s.kode_saham,
                    s.nama_saham,
                    s.sektor,
                    s.EPS,
                    s.PER,
                    s.ROE,
                    (SELECT harga_tutup FROM " . $this->table . " 
                     WHERE kode_saham = s.kode_saham 
                     AND tanggal >= :tanggal_mulai 
                     ORDER BY tanggal ASC LIMIT 1) as harga_awal,
                    (SELECT harga_tutup FROM " . $this->table . " 
                     WHERE kode_saham = s.kode_saham 
                     AND tanggal <= :tanggal_akhir 
                     ORDER BY tanggal DESC LIMIT 1) as harga_akhir
                  FROM (SELECT DISTINCT kode_saham, nama_saham, sektor, EPS, PER, ROE 
                        FROM " . $this->table . " 
                        WHERE sektor = :sektor) s
                  HAVING harga_awal IS NOT NULL AND harga_akhir IS NOT NULL
                  ORDER BY ((harga_akhir - harga_awal) / harga_awal) DESC
                  LIMIT :limit";

        $this->db->query($query);
        $this->db->bind(':sektor', $sektor);
        $this->db->bind(':tanggal_mulai', $tanggal_mulai);
        $this->db->bind(':tanggal_akhir', $tanggal_akhir);
        $this->db->bind(':limit', $limit);

        return $this->db->resultSet();
    }

    /**
     * Get all unique sectors
     * @return array
     */
    public function getAllSectors()
    {
        $query = "SELECT DISTINCT sektor 
                  FROM " . $this->table . " 
                  WHERE sektor IS NOT NULL
                  ORDER BY sektor ASC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Get top stocks by return percentage
     * @param string $tanggal_mulai
     * @param string $tanggal_akhir
     * @param int $limit
     * @return array
     */
    public function getTopStocksByReturn($tanggal_mulai, $tanggal_akhir, $limit = 10)
    {
        $query = "SELECT 
                    s.kode_saham,
                    s.nama_saham,
                    s.sektor,
                    s.EPS,
                    s.PER,
                    s.ROE,
                    (SELECT harga_tutup FROM " . $this->table . " 
                     WHERE kode_saham = s.kode_saham 
                     AND tanggal >= :tanggal_mulai 
                     ORDER BY tanggal ASC LIMIT 1) as harga_awal,
                    (SELECT harga_tutup FROM " . $this->table . " 
                     WHERE kode_saham = s.kode_saham 
                     AND tanggal <= :tanggal_akhir 
                     ORDER BY tanggal DESC LIMIT 1) as harga_akhir,
                    ((SELECT harga_tutup FROM " . $this->table . " 
                      WHERE kode_saham = s.kode_saham 
                      AND tanggal <= :tanggal_akhir2 
                      ORDER BY tanggal DESC LIMIT 1) - 
                     (SELECT harga_tutup FROM " . $this->table . " 
                      WHERE kode_saham = s.kode_saham 
                      AND tanggal >= :tanggal_mulai2 
                      ORDER BY tanggal ASC LIMIT 1)) / 
                     (SELECT harga_tutup FROM " . $this->table . " 
                      WHERE kode_saham = s.kode_saham 
                      AND tanggal >= :tanggal_mulai3 
                      ORDER BY tanggal ASC LIMIT 1) * 100 as return_pct
                  FROM (SELECT DISTINCT kode_saham, nama_saham, sektor, EPS, PER, ROE 
                        FROM " . $this->table . ") s
                  HAVING harga_awal IS NOT NULL 
                    AND harga_akhir IS NOT NULL 
                    AND return_pct IS NOT NULL
                  ORDER BY return_pct DESC
                  LIMIT :limit";

        $this->db->query($query);
        $this->db->bind(':tanggal_mulai', $tanggal_mulai);
        $this->db->bind(':tanggal_akhir', $tanggal_akhir);
        $this->db->bind(':tanggal_akhir2', $tanggal_akhir);
        $this->db->bind(':tanggal_mulai2', $tanggal_mulai);
        $this->db->bind(':tanggal_mulai3', $tanggal_mulai);
        $this->db->bind(':limit', $limit);

        return $this->db->resultSet();
    }

    /**
     * Get stock ranking for a specific period (for comparison)
     * @param string $tanggal_mulai
     * @param string $tanggal_akhir
     * @param array $stockCodes (optional - filter specific stocks)
     * @return array
     */
    public function getStockRanking($tanggal_mulai, $tanggal_akhir, $stockCodes = [])
    {
        // Build WHERE clause for stock codes filter
        $whereClause = '';
        if (!empty($stockCodes)) {
            $placeholders = implode(',', array_fill(0, count($stockCodes), '?'));
            $whereClause = " AND kode_saham IN ($placeholders)";
        }

        $query = "SELECT 
                    kode_saham,
                    nama_saham,
                    harga_awal,
                    harga_akhir,
                    ((harga_akhir - harga_awal) / harga_awal) * 100 as return_pct
                  FROM (
                    SELECT 
                        t1.kode_saham,
                        MAX(t1.nama_saham) as nama_saham,
                        (SELECT harga_tutup FROM " . $this->table . " t2 
                         WHERE t2.kode_saham = t1.kode_saham 
                         AND t2.tanggal >= ? 
                         ORDER BY t2.tanggal ASC LIMIT 1) as harga_awal,
                        (SELECT harga_tutup FROM " . $this->table . " t3 
                         WHERE t3.kode_saham = t1.kode_saham 
                         AND t3.tanggal <= ? 
                         ORDER BY t3.tanggal DESC LIMIT 1) as harga_akhir
                    FROM " . $this->table . " t1
                    WHERE 1=1 $whereClause
                    GROUP BY t1.kode_saham
                  ) subquery
                  WHERE harga_awal IS NOT NULL 
                    AND harga_akhir IS NOT NULL
                    AND harga_awal > 0
                  ORDER BY return_pct DESC";

        $this->db->query($query);

        // Bind date parameters
        $this->db->bind(1, $tanggal_mulai);
        $this->db->bind(2, $tanggal_akhir);

        // Bind stock codes if provided
        if (!empty($stockCodes)) {
            $paramIndex = 3;
            foreach ($stockCodes as $code) {
                $this->db->bind($paramIndex++, $code);
            }
        }

        return $this->db->resultSet();
    }

    /**
     * Get market overview data for hero section
     * Returns: last update date, stock count, top gainer, top loser, average return (7 days)
     * NEW: Reads from saham_topsis table with last_updated field
     */
    public function getMarketOverview()
    {
        try {
            // Default return jika tidak ada data
            $defaultReturn = [
                'last_update' => null,
                'last_update_formatted' => 'Belum Ada Data',
                'stock_count' => 0,
                'top_gainer' => null,
                'top_loser' => null,
                'average_return' => 0,
                'market_sentiment' => 'Netral',
                'sentiment_class' => 'text-secondary',
                'period' => 'Belum ada data'
            ];

            // 1. Get last update date from saham table (most reliable)
            $this->db->query("SELECT MAX(tanggal) as last_update FROM saham WHERE tanggal IS NOT NULL");
            $lastUpdate = $this->db->single();
            $lastUpdateValue = $lastUpdate ? $lastUpdate->last_update : null;

            // Jika tidak ada data sama sekali
            if (!$lastUpdateValue) {
                return $defaultReturn;
            }

            // 2. Get total monitored stocks from saham
            $this->db->query("SELECT COUNT(DISTINCT kode_saham) as stock_count FROM saham WHERE tanggal IS NOT NULL");
            $stockCount = $this->db->single();

            // 3. Get latest date's data for performance calculation
            // Get top performers from latest date
            $this->db->query("
                SELECT 
                    kode_saham, 
                    ((harga_tutup - harga_buka) / harga_buka * 100) as daily_return
                FROM saham 
                WHERE tanggal = :latest_date 
                AND harga_buka > 0
                ORDER BY daily_return DESC
                LIMIT 1
            ");
            $this->db->bind(':latest_date', $lastUpdateValue);
            $topGainer = $this->db->single();

            $this->db->query("
                SELECT 
                    kode_saham, 
                    ((harga_tutup - harga_buka) / harga_buka * 100) as daily_return
                FROM saham 
                WHERE tanggal = :latest_date 
                AND harga_buka > 0
                ORDER BY daily_return ASC
                LIMIT 1
            ");
            $this->db->bind(':latest_date', $lastUpdateValue);
            $topLoser = $this->db->single();

            // Calculate average return
            $this->db->query("
                SELECT AVG((harga_tutup - harga_buka) / harga_buka * 100) as avg_return 
                FROM saham 
                WHERE tanggal = :latest_date 
                AND harga_buka > 0
            ");
            $this->db->bind(':latest_date', $lastUpdateValue);
            $avgReturnData = $this->db->single();
            $avgReturn = $avgReturnData && $avgReturnData->avg_return ? round($avgReturnData->avg_return, 2) : 0;

            // 4. Determine market sentiment
            $marketSentiment = 'Netral';
            $sentimentClass = 'text-secondary';
            if ($avgReturn > 1) {
                $marketSentiment = 'Bullish';
                $sentimentClass = 'text-success';
            } elseif ($avgReturn < -1) {
                $marketSentiment = 'Bearish';
                $sentimentClass = 'text-danger';
            }

            // Format top gainer/loser data
            $topGainerFormatted = null;
            if ($topGainer && $topGainer->kode_saham) {
                $topGainerFormatted = [
                    'kode_saham' => $topGainer->kode_saham,
                    'return' => round($topGainer->daily_return, 2)
                ];
            }

            $topLoserFormatted = null;
            if ($topLoser && $topLoser->kode_saham) {
                $topLoserFormatted = [
                    'kode_saham' => $topLoser->kode_saham,
                    'return' => round($topLoser->daily_return, 2)
                ];
            }

            // Format last update
            $lastUpdateFormatted = 'N/A';
            if ($lastUpdateValue) {
                // Check if datetime or date
                if (strpos($lastUpdateValue, ' ') !== false) {
                    // DateTime format
                    $lastUpdateFormatted = date('d M Y, H:i', strtotime($lastUpdateValue)) . ' WIB';
                } else {
                    // Date only
                    $lastUpdateFormatted = date('d M Y', strtotime($lastUpdateValue));
                }
            }

            return [
                'last_update' => $lastUpdateValue,
                'last_update_formatted' => $lastUpdateFormatted,
                'stock_count' => $stockCount ? $stockCount->stock_count : 0,
                'top_gainer' => $topGainerFormatted,
                'top_loser' => $topLoserFormatted,
                'average_return' => $avgReturn,
                'market_sentiment' => $marketSentiment,
                'sentiment_class' => $sentimentClass,
                'period' => 'Hari terakhir'
            ];
        } catch (Exception $e) {
            error_log("Error in getMarketOverview: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get list of stocks for comparison dropdown
     * @return array
     */
    public function getStockList()
    {
        // First, try to get stocks with the latest date
        $query = "SELECT DISTINCT kode_saham, nama_saham, sektor 
                  FROM " . $this->table . " 
                  WHERE tanggal = (SELECT MAX(tanggal) FROM " . $this->table . ")
                  ORDER BY kode_saham ASC";

        $this->db->query($query);
        $results = $this->db->resultSet();

        // If no results (no tanggal column or no data), fallback to simple query
        if (empty($results)) {
            $query = "SELECT DISTINCT kode_saham, 
                      COALESCE(nama_saham, kode_saham) as nama_saham,
                      COALESCE(sektor, 'N/A') as sektor
                      FROM " . $this->table . " 
                      ORDER BY kode_saham ASC 
                      LIMIT 50";

            $this->db->query($query);
            $results = $this->db->resultSet();
        }

        return $results;
    }

    /**
     * Get historical price data for chart
     * @param array $stockCodes Array of stock codes
     * @param int $days Number of days to retrieve
     * @return array
     */
    public function getHistoricalData($stockCodes, $days = 30)
    {
        $placeholders = implode(',', array_fill(0, count($stockCodes), '?'));

        $query = "SELECT kode_saham, nama_saham, tanggal, harga_tutup 
                  FROM " . $this->table . " 
                  WHERE kode_saham IN ($placeholders) 
                  AND tanggal >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                  ORDER BY tanggal ASC, kode_saham ASC";

        $this->db->query($query);

        // Bind stock codes
        foreach ($stockCodes as $index => $code) {
            $this->db->bind($index + 1, $code);
        }
        // Bind days
        $this->db->bind(count($stockCodes) + 1, $days);

        $results = $this->db->resultSet();

        // Group by stock code for easier processing
        $grouped = [];
        foreach ($results as $row) {
            $code = $row->kode_saham;
            if (!isset($grouped[$code])) {
                $grouped[$code] = [
                    'name' => $row->nama_saham,
                    'data' => []
                ];
            }
            $grouped[$code]['data'][] = [
                'date' => $row->tanggal,
                'price' => floatval($row->harga_tutup)
            ];
        }

        return $grouped;
    }

    /**
     * Get comparison metrics for selected stocks
     * @param array $stockCodes Array of stock codes
     * @param int $days Number of days for calculations
     * @return array
     */
    public function getComparisonMetrics($stockCodes, $days = 30)
    {
        $metrics = [];

        foreach ($stockCodes as $code) {
            // Get latest data
            $queryLatest = "SELECT * FROM " . $this->table . " 
                           WHERE kode_saham = ? 
                           ORDER BY tanggal DESC 
                           LIMIT 1";

            $this->db->query($queryLatest);
            $this->db->bind(1, $code);
            $latest = $this->db->single();

            if (!$latest) continue;

            // Get historical data for period
            $queryHistory = "SELECT harga_tutup, tanggal 
                            FROM " . $this->table . " 
                            WHERE kode_saham = ? 
                            AND tanggal >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                            ORDER BY tanggal ASC";

            $this->db->query($queryHistory);
            $this->db->bind(1, $code);
            $this->db->bind(2, $days);
            $history = $this->db->resultSet();

            if (empty($history)) continue;

            // Calculate metrics
            $prices = array_map(function ($row) {
                return floatval($row->harga_tutup);
            }, $history);
            $firstPrice = $prices[0];
            $lastPrice = end($prices);

            // Return percentage
            $returnPct = (($lastPrice - $firstPrice) / $firstPrice) * 100;

            // Calculate daily returns for volatility
            $dailyReturns = [];
            for ($i = 1; $i < count($prices); $i++) {
                $dailyReturns[] = (($prices[$i] - $prices[$i - 1]) / $prices[$i - 1]) * 100;
            }

            // Volatility (standard deviation of daily returns)
            $volatility = $this->calculateStdDev($dailyReturns);

            // Risk-adjusted return (Sharpe-like ratio)
            $avgReturn = array_sum($dailyReturns) / count($dailyReturns);
            $riskAdjustedReturn = $volatility > 0 ? ($avgReturn / $volatility) : 0;

            // Price change
            $priceChange = $lastPrice - $firstPrice;
            $priceChangePct = $returnPct;

            // Highest and lowest in period
            $highestPrice = max($prices);
            $lowestPrice = min($prices);

            $metrics[$code] = [
                'kode_saham' => $code,
                'nama_saham' => $latest->nama_saham,
                'sektor' => $latest->sektor,
                'harga_terakhir' => floatval($latest->harga_tutup),
                'volume' => intval($latest->volume),
                'eps' => floatval($latest->EPS),
                'per' => floatval($latest->PER),
                'roe' => floatval($latest->ROE),
                'return_pct' => round($returnPct, 2),
                'volatility' => round($volatility, 2),
                'risk_adjusted_return' => round($riskAdjustedReturn, 2),
                'price_change' => round($priceChange, 2),
                'price_change_pct' => round($priceChangePct, 2),
                'highest_price' => round($highestPrice, 2),
                'lowest_price' => round($lowestPrice, 2),
                'tanggal' => $latest->tanggal
            ];
        }

        return $metrics;
    }

    /**
     * Calculate standard deviation
     * @param array $values
     * @return float
     */
    private function calculateStdDev($values)
    {
        if (empty($values)) return 0;

        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(function ($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values)) / count($values);

        return sqrt($variance);
    }

    /**
     * Get last update info for scraper management
     */
    public function getLastUpdateInfo()
    {
        try {
            // Try saham_topsis first (if exists)
            try {
                $this->db->query("SELECT MAX(last_updated) as last_update FROM saham_topsis");
                $topsisUpdate = $this->db->single();

                if ($topsisUpdate && $topsisUpdate->last_update) {
                    $this->db->query("SELECT COUNT(*) as stock_count FROM saham_topsis");
                    $stockCount = $this->db->single();

                    if (strpos($topsisUpdate->last_update, ' ') !== false) {
                        $formatted = date('d M Y, H:i', strtotime($topsisUpdate->last_update)) . ' WIB';
                    } else {
                        $formatted = date('d M Y', strtotime($topsisUpdate->last_update));
                    }

                    return [
                        'last_update' => $topsisUpdate->last_update,
                        'formatted' => $formatted,
                        'stock_count' => $stockCount->stock_count ?? 0,
                        'source' => 'saham_topsis'
                    ];
                }
            } catch (Exception $e) {
                // Table doesn't exist, fallback to saham
            }

            // Fallback to 'saham' table (existing structure)
            $this->db->query("SELECT MAX(tanggal) as last_update FROM saham");
            $sahamUpdate = $this->db->single();

            $this->db->query("SELECT COUNT(DISTINCT kode_saham) as stock_count FROM saham");
            $stockCount = $this->db->single();

            if ($sahamUpdate && $sahamUpdate->last_update) {
                return [
                    'last_update' => $sahamUpdate->last_update,
                    'formatted' => date('d M Y', strtotime($sahamUpdate->last_update)),
                    'stock_count' => $stockCount->stock_count ?? 0,
                    'source' => 'saham table'
                ];
            }

            return null;
        } catch (Exception $e) {
            error_log("Error getting last update info: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get scraper logs
     */
    public function getScraperLogs($limit = 10)
    {
        try {
            // Try to get from scraper_log table
            $this->db->query("
                SELECT * FROM scraper_log 
                ORDER BY id_log DESC 
                LIMIT :limit
            ");
            $this->db->bind(':limit', $limit);

            return $this->db->resultSet();
        } catch (Exception $e) {
            // Table doesn't exist yet, return empty
            return [];
        }
    }

    /**
     * Check if data for today has been scraped
     * @return array|false Returns scrape info if scraped today, false otherwise
     */
    public function checkScrapedToday()
    {
        try {
            $today = date('Y-m-d');

            // Check from saham table if data for today exists
            $this->db->query("
                SELECT COUNT(*) as count, MAX(tanggal) as latest_date
                FROM saham
                WHERE DATE(tanggal) = :today
            ");
            $this->db->bind(':today', $today);
            $result = $this->db->single();

            if ($result && $result->count > 0) {
                return [
                    'date' => $today,
                    'count' => $result->count,
                    'formatted' => date('d M Y', strtotime($today))
                ];
            }

            return false;
        } catch (Exception $e) {
            error_log("Error checking scraped today: " . $e->getMessage());
            return false;
        }
    }
}
