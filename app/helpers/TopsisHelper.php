<?php

/**
 * Class TopsisHelper
 * Helper untuk perhitungan metode TOPSIS
 */
class TopsisHelper
{
    /**
     * Normalisasi matriks keputusan
     * @param array $data data alternatif
     * @param array $kriteria kriteria yang digunakan
     * @return array matriks ternormalisasi
     */
    public static function normalisasi($data, $kriteria)
    {
        // TODO: Implementasi normalisasi matriks
        // r_ij = x_ij / sqrt(sum(x_ij^2))

        return [];
    }

    /**
     * Normalisasi terbobot
     * @param array $matriksNormalisasi matriks hasil normalisasi
     * @param array $bobot bobot setiap kriteria
     * @return array matriks ternormalisasi terbobot
     */
    public static function normalisasiTerbobot($matriksNormalisasi, $bobot)
    {
        // TODO: Implementasi normalisasi terbobot
        // y_ij = r_ij * w_j

        return [];
    }

    /**
     * Menentukan solusi ideal positif dan negatif
     * @param array $matriksTerbobot matriks ternormalisasi terbobot
     * @param array $kriteria kriteria (benefit/cost)
     * @return array [ideal_positif, ideal_negatif]
     */
    public static function solusiIdeal($matriksTerbobot, $kriteria)
    {
        // TODO: Implementasi solusi ideal
        // A+ = (y1+, y2+, ..., yn+) - max untuk benefit, min untuk cost
        // A- = (y1-, y2-, ..., yn-) - min untuk benefit, max untuk cost

        return [
            'positif' => [],
            'negatif' => []
        ];
    }

    /**
     * Hitung jarak ke solusi ideal positif dan negatif
     * @param array $matriksTerbobot matriks ternormalisasi terbobot
     * @param array $idealPositif solusi ideal positif
     * @param array $idealNegatif solusi ideal negatif
     * @return array [jarak_positif, jarak_negatif]
     */
    public static function hitungJarak($matriksTerbobot, $idealPositif, $idealNegatif)
    {
        // TODO: Implementasi perhitungan jarak
        // D+ = sqrt(sum((yij - yj+)^2))
        // D- = sqrt(sum((yij - yj-)^2))

        return [
            'positif' => [],
            'negatif' => []
        ];
    }

    /**
     * Hitung nilai preferensi (kedekatan relatif)
     * @param array $jarakPositif jarak ke solusi ideal positif
     * @param array $jarakNegatif jarak ke solusi ideal negatif
     * @return array nilai preferensi setiap alternatif
     */
    public static function nilaiPreferensi($jarakPositif, $jarakNegatif)
    {
        // TODO: Implementasi nilai preferensi
        // V = D- / (D+ + D-)

        return [];
    }

    /**
     * Proses lengkap perhitungan TOPSIS
     * @param array $data data alternatif
     * @param array $kriteria kriteria yang digunakan
     * @param array $bobot bobot setiap kriteria
     * @return array hasil perhitungan TOPSIS
     */
    public static function hitung($data, $kriteria, $bobot)
    {
        // TODO: Implementasi proses lengkap TOPSIS
        // 1. Normalisasi
        // 2. Normalisasi terbobot
        // 3. Solusi ideal
        // 4. Hitung jarak
        // 5. Nilai preferensi
        // 6. Ranking

        return [];
    }
}
