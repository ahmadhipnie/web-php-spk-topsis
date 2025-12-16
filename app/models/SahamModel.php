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
        // Query untuk ambil data terbaru per kode saham (berdasarkan tanggal)
        if ($sektor === 'semua') {
            $query = "SELECT s1.* FROM " . $this->table . " s1
                      INNER JOIN (
                          SELECT kode_saham, MAX(tanggal) as max_tanggal
                          FROM " . $this->table . "
                          WHERE harga_tutup <= :budget 
                          AND EPS IS NOT NULL 
                          AND PER IS NOT NULL 
                          AND ROE IS NOT NULL
                          GROUP BY kode_saham
                      ) s2 ON s1.kode_saham = s2.kode_saham AND s1.tanggal = s2.max_tanggal
                      WHERE s1.harga_tutup <= :budget
                      ORDER BY s1.harga_tutup ASC";
        } else {
            $query = "SELECT s1.* FROM " . $this->table . " s1
                      INNER JOIN (
                          SELECT kode_saham, MAX(tanggal) as max_tanggal
                          FROM " . $this->table . "
                          WHERE sektor = :sektor 
                          AND harga_tutup <= :budget 
                          AND EPS IS NOT NULL 
                          AND PER IS NOT NULL 
                          AND ROE IS NOT NULL
                          GROUP BY kode_saham
                      ) s2 ON s1.kode_saham = s2.kode_saham AND s1.tanggal = s2.max_tanggal
                      WHERE s1.sektor = :sektor 
                      AND s1.harga_tutup <= :budget
                      ORDER BY s1.harga_tutup ASC";
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
     */
    public function getMarketOverview()
    {
        try {
            // 1. Get last update date
            $this->db->query("SELECT MAX(tanggal) as last_update FROM saham");
            $lastUpdate = $this->db->single();
            
            // 2. Get total monitored stocks
            $this->db->query("SELECT COUNT(DISTINCT kode_saham) as stock_count FROM saham");
            $stockCount = $this->db->single();
            
            // 3. Calculate date range (7 days ago)
            $tanggal_akhir = $lastUpdate->last_update;
            $tanggal_mulai = date('Y-m-d', strtotime($tanggal_akhir . ' -7 days'));
            
            // 4. Get all stocks with their 7-day return
            $this->db->query("
                SELECT 
                    t1.kode_saham,
                    MAX(t1.nama_saham) as nama_saham,
                    (SELECT harga_tutup 
                     FROM saham t2 
                     WHERE t2.kode_saham = t1.kode_saham 
                     AND t2.tanggal >= ?
                     ORDER BY t2.tanggal ASC 
                     LIMIT 1) as harga_awal,
                    (SELECT harga_tutup 
                     FROM saham t3 
                     WHERE t3.kode_saham = t1.kode_saham 
                     AND t3.tanggal <= ?
                     ORDER BY t3.tanggal DESC 
                     LIMIT 1) as harga_akhir
                FROM saham t1
                GROUP BY t1.kode_saham
            ");
            
            $this->db->bind(1, $tanggal_mulai);
            $this->db->bind(2, $tanggal_akhir);
            $stocks = $this->db->resultSet();
            
            // Calculate returns and filter valid data
            $validReturns = [];
            foreach ($stocks as $stock) {
                if ($stock->harga_awal && $stock->harga_akhir && $stock->harga_awal > 0) {
                    $return = (($stock->harga_akhir - $stock->harga_awal) / $stock->harga_awal) * 100;
                    $validReturns[] = [
                        'kode_saham' => $stock->kode_saham,
                        'nama_saham' => $stock->nama_saham,
                        'return' => round($return, 2),
                        'harga_awal' => $stock->harga_awal,
                        'harga_akhir' => $stock->harga_akhir
                    ];
                }
            }
            
            // Sort by return
            usort($validReturns, function($a, $b) {
                return $b['return'] <=> $a['return'];
            });
            
            // 5. Get top gainer (highest positive return)
            $topGainer = $validReturns[0] ?? null;
            
            // 6. Get top loser (lowest/most negative return)
            $topLoser = end($validReturns) ?: null;
            
            // 7. Calculate average market return
            $totalReturn = array_sum(array_column($validReturns, 'return'));
            $avgReturn = count($validReturns) > 0 ? round($totalReturn / count($validReturns), 2) : 0;
            
            // 8. Determine market sentiment
            $marketSentiment = 'Netral';
            $sentimentClass = 'text-secondary';
            if ($avgReturn > 2) {
                $marketSentiment = 'Bullish';
                $sentimentClass = 'text-success';
            } elseif ($avgReturn < -2) {
                $marketSentiment = 'Bearish';
                $sentimentClass = 'text-danger';
            }
            
            return [
                'last_update' => $lastUpdate->last_update,
                'last_update_formatted' => date('d M Y', strtotime($lastUpdate->last_update)),
                'stock_count' => $stockCount->stock_count,
                'top_gainer' => $topGainer,
                'top_loser' => $topLoser,
                'average_return' => $avgReturn,
                'market_sentiment' => $marketSentiment,
                'sentiment_class' => $sentimentClass,
                'period' => '7 hari terakhir'
            ];
            
        } catch (Exception $e) {
            error_log("Error in getMarketOverview: " . $e->getMessage());
            return null;
        }
    }
}








