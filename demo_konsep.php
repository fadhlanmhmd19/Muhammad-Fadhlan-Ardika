<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

define('DEMO_LIFECYCLE', true);
header('Content-Type: text/plain; charset=utf-8');

echo "=== 1. UJI KELAS & PEMBANDINGAN DUA OBJEK ===\n";
$m1 = new Mahasiswa('123', 'Alya', 'Teknik', 'Informatika', 'SMA 1', 3, 'Membaca', 'Islam');
$m2 = new Mahasiswa('124', 'Budi', '', 'Sistem Informasi', 'SMA 2', 2, 'Olahraga', 'Islam');

echo "Output M1 (Fakultas diisi): " . $m1->fakultas . "\n";
echo "Output M2 (Fakultas default): " . $m2->fakultas . "\n";

echo "\n=== 2. MENJALANKAN OVERLOADING ATRIBUT ===\n";
$m1->ukuranJaket = 'M';
echo "Atribut dinamis ukuranJaket: " . $m1->ukuranJaket . "\n";
echo "Cek isset: " . (isset($m1->ukuranJaket) ? "Ada\n" : "Tidak ada\n");
unset($m1->ukuranJaket);

echo "\n=== 3. MENJALANKAN OVERLOADING METHOD & EXCEPTION ===\n";
echo "Pemanggilan method diizinkan (profilSingkat):\n";
echo $m1->profilSingkat() . "\n";

echo "\nPengujian pemanggilan method ilegal (memicu Exception):\n";
try {
    $m1->methodTidakTerdaftar();
} catch (Throwable $e) {
    echo "Tertangkap Exception: " . $e->getMessage() . "\n";
}

echo "\n=== 4. MENJALANKAN DESTRUCTOR ===\n";
unset($m1, $m2);