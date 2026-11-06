<?php
declare(strict_types=1);

class Produk {
    public string $nama = "Laptop Gaming";
    protected string $kategori = "Elektronik";
    private int $harga = 15000000;

    public function tampilkanInfo(): void {
        echo "Nama (Public): " . $this->nama . "<br>";
        echo "Kategori (Protected): " . $this->kategori . "<br>";
        echo "Harga (Private): " . $this->harga . "<br>";
    }
}

class ProdukDigital extends Produk {
    public function aksesDariTurunan(): void {
        echo "Akses Kategori dari subclass (Protected): " . $this->kategori . "<br>";
        // echo $this->harga; // ERROR: private tidak bisa diakses dari subclass
    }
}

// --- PENGUJIAN DARI LUAR CLASS ---
$p = new Produk();
echo "<h3>Uji Akses dari Luar Class:</h3>";
echo "Public: " . $p->nama . "<br>"; // Berhasil

// Uji protected & private dari luar (akan error jika di-uncomment):
// echo $p->kategori; 
// echo $p->harga; 

echo "<br><h3>Uji dari Subclass:</h3>";
$pd = new ProdukDigital();
$pd->aksesDariTurunan();