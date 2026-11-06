<?php
declare(strict_types=1);

class Buku {
    private string $kodeBuku;
    private string $judul;
    private int $stok;

    public function __construct(string $kodeBuku, string $judul, int $stok) {
        $kodeBuku = trim($kodeBuku);
        if ($kodeBuku === '') {
            throw new InvalidArgumentException("Kode buku wajib diisi.");
        }
        $this->kodeBuku = $kodeBuku;
        $this->setJudul($judul);
        $this->setStokAwal($stok);
    }

    public function getKodeBuku(): string { return $this->kodeBuku; }
    public function getJudul(): string { return $this->judul; }
    public function getStok(): int { return $this->stok; }

    public function setJudul(string $judul): void {
        $judul = trim($judul);
        if ($judul === '') {
            throw new InvalidArgumentException("Judul buku wajib diisi.");
        }
        $this->judul = $judul;
    }

    public function tambahStok(int $jumlah): void {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah penambahan stok harus positif.");
        }
        $this->stok += $jumlah;
    }

    public function kurangiStok(int $jumlah): void {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah pengurangan stok harus positif.");
        }
        if ($jumlah > $this->stok) {
            throw new InvalidArgumentException("Stok tidak mencukupi untuk dikurangi.");
        }
        $this->stok -= $jumlah;
    }

    private function setStokAwal(int $stok): void {
        if ($stok < 0) {
            throw new InvalidArgumentException("Stok awal tidak boleh negatif.");
        }
        $this->stok = $stok;
    }
}

// --- PENGUJIAN SKENARIO VALID DAN TIDAK VALID ---
try {
    echo "<h3>Pengujian Buku:</h3>";
    
    // 1. Input Valid
    $buku1 = new Buku("BK-001", "Pemrograman PHP", 10);
    $buku1->tambahStok(5); // Valid
    echo "Buku berhasil: " . $buku1->getJudul() . " | Stok: " . $buku1->getStok() . "<br>";

    // 2. Input Tidak Valid (Contoh: Pengurangan melebihi stok - memicu exception)
    $buku1->kurangiStok(25); 

} catch (InvalidArgumentException $e) {
    echo "<b style='color:red;'>Error ditangkap: " . $e->getMessage() . "</b>";
}