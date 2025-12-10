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
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
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
                  WHERE id = :id";

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
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
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
}
