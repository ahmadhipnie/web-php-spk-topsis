<?php

/**
 * Class Flasher
 * Helper untuk menampilkan flash message (pesan notifikasi)
 */
class Flasher
{
    /**
     * Set flash message
     * @param string $pesan pesan yang akan ditampilkan
     * @param string $aksi aksi yang dilakukan (tambah, ubah, hapus, dll)
     * @param string $tipe tipe pesan (success, danger, warning, info)
     */
    public static function setFlash($pesan, $aksi, $tipe)
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi' => $aksi,
            'tipe' => $tipe
        ];
    }

    /**
     * Tampilkan flash message
     * @return string HTML flash message
     */
    public static function flash()
    {
        if (isset($_SESSION['flash'])) {
            echo '<div class="alert alert-' . $_SESSION['flash']['tipe'] . ' alert-dismissible fade show" role="alert">
                    Data <strong>' . $_SESSION['flash']['pesan'] . '</strong> ' . $_SESSION['flash']['aksi'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['flash']);
        }
    }
}
