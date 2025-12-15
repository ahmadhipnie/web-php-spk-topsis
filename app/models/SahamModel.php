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
}

