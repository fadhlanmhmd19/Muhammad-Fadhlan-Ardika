<?php
declare(strict_types=1);

class Buku {
    public function __construct(
        public string $judul,
        public string $penulis,
        public int $tahun
    ) {
        echo "<small><i>[Log] Objek buku '{$this->judul}' dibuat.</i></small><br>";
    }

    public function tampilInfo(): string {
        return "Buku: <b>{$this->judul}</b> | Penulis: {$this->penulis} | Tahun: {$this->tahun}";
    }

    public function __destruct() {
        echo "<small style='color:gray;'><i>[Destructor] Objek buku '{$this->judul}' dihapus dari memori.</i></small><br>";
    }
}

echo "<h3>Pengujian Kelas Buku</h3>";

$buku1 = new Buku("Pemrograman PHP", "Andi Prasetyo", 2023);
$buku2 = new Buku("Algoritma & Struktur Data", "Rina Marlina", 2022);
$buku3 = new Buku("Basis Data Relasional", "Ahmad Fauzi", 2024);

echo "<br>" . $buku1->tampilInfo() . "<br>";
echo $buku2->tampilInfo() . "<br>";
echo $buku3->tampilInfo() . "<br><hr>";

echo "<b>Uji Destructor & Alias Objek:</b><br>";
$bukuUji = new Buku("Sistem Operasi", "Budi Raharjo", 2021);
$aliasBuku = $bukuUji;

echo "Melakukan unset(\$bukuUji)... (Destructor BELUM jalan karena masih ada \$aliasBuku)<br>";
unset($bukuUji);

echo "<br>Melakukan unset(\$aliasBuku)... (Destructor LANGSUNG tereksekusi karena referensi habis)<br>";
unset($aliasBuku);