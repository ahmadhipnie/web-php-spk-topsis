<?php
class TopsisHelper {
public static function getBobotByProfilRisiko($profilRisiko) {
switch (strtolower($profilRisiko)) {
case 'konservatif': return ['EPS' => 0.20, 'PER' => 0.50, 'ROE' => 0.30];
case 'moderat': return ['EPS' => 0.35, 'PER' => 0.35, 'ROE' => 0.30];
case 'agresif': return ['EPS' => 0.50, 'PER' => 0.15, 'ROE' => 0.35];
default: return ['EPS' => 0.35, 'PER' => 0.35, 'ROE' => 0.30];
}
}
public static function hitungTopsis($sahamData, $bobot) {
if (empty($sahamData)) return [];
$matriks = []; $sahamInfo = [];
foreach ($sahamData as $index => $saham) {
$matriks[$index] = ['EPS' => (float) $saham->EPS, 'PER' => (float) $saham->PER, 'ROE' => (float) $saham->ROE];
$sahamInfo[$index] = $saham;
}
$matriksNormalisasi = self::normalisasi($matriks);
$matriksTerbobot = self::normalisasiTerbobot($matriksNormalisasi, $bobot);
$solusiIdeal = self::solusiIdeal($matriksTerbobot);
$jarak = self::hitungJarak($matriksTerbobot, $solusiIdeal);
$hasil = [];
foreach ($jarak as $index => $d) {
$skor = $d['negatif'] / ($d['positif'] + $d['negatif']);
$hasil[] = ['saham' => $sahamInfo[$index], 'skor' => $skor, 'normalisasi' => $matriksNormalisasi[$index], 'terbobot' => $matriksTerbobot[$index], 'jarak_positif' => $d['positif'], 'jarak_negatif' => $d['negatif']];
}
usort($hasil, function($a, $b) { return $b['skor'] <=> $a['skor']; });
foreach ($hasil as $index => &$item) { $item['ranking'] = $index + 1; }
return $hasil;
}
private static function normalisasi($matriks) {
$hasil = []; $kriteria = ['EPS', 'PER', 'ROE']; $pembagi = [];
foreach ($kriteria as $k) {
$sumKuadrat = 0;
foreach ($matriks as $row) { $sumKuadrat += pow($row[$k], 2); }
$pembagi[$k] = sqrt($sumKuadrat);
}
foreach ($matriks as $index => $row) {
$hasil[$index] = [];
foreach ($kriteria as $k) { $hasil[$index][$k] = $pembagi[$k] > 0 ? $row[$k] / $pembagi[$k] : 0; }
}
return $hasil;
}
private static function normalisasiTerbobot($matriksNormalisasi, $bobot) {
$hasil = []; $kriteria = ['EPS', 'PER', 'ROE'];
foreach ($matriksNormalisasi as $index => $row) {
$hasil[$index] = [];
foreach ($kriteria as $k) { $hasil[$index][$k] = $row[$k] * $bobot[$k]; }
}
return $hasil;
}
private static function solusiIdeal($matriksTerbobot) {
$ideal = ['positif' => [], 'negatif' => []];
$jenis = ['EPS' => 'benefit', 'PER' => 'cost', 'ROE' => 'benefit'];
foreach ($jenis as $kriteria => $tipe) {
$nilai = array_column($matriksTerbobot, $kriteria);
if ($tipe === 'benefit') {
$ideal['positif'][$kriteria] = max($nilai);
$ideal['negatif'][$kriteria] = min($nilai);
} else {
$ideal['positif'][$kriteria] = min($nilai);
$ideal['negatif'][$kriteria] = max($nilai);
}
}
return $ideal;
}
private static function hitungJarak($matriksTerbobot, $solusiIdeal) {
$hasil = []; $kriteria = ['EPS', 'PER', 'ROE'];
foreach ($matriksTerbobot as $index => $row) {
$jarakPositif = 0; $jarakNegatif = 0;
foreach ($kriteria as $k) {
$jarakPositif += pow($row[$k] - $solusiIdeal['positif'][$k], 2);
$jarakNegatif += pow($row[$k] - $solusiIdeal['negatif'][$k], 2);
}
$hasil[$index] = ['positif' => sqrt($jarakPositif), 'negatif' => sqrt($jarakNegatif)];
}
return $hasil;
}
public static function formatBobotToJson($bobot) { return json_encode($bobot); }
public static function formatNormalisasiToJson($normalisasi) { return json_encode($normalisasi); }
public static function formatJarakToJson($jarakPositif, $jarakNegatif, $skor) {
return json_encode(['jarak_positif' => $jarakPositif, 'jarak_negatif' => $jarakNegatif, 'skor_preferensi' => $skor]);
}
}