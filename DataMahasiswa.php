<?php
declare(strict_types=1);
require_once __DIR__ . '/MahasiswaReguler.php';
require_once __DIR__ . '/MahasiswaBeasiswa.php';

final class DataMahasiswa
{
    /** @var array<string, Mahasiswa> */
    private array $data = [];

    public function tambah(Mahasiswa $m): bool
    {
        $nim = $m->getNim();
        if (isset($this->data[$nim])) return false;
        $this->data[$nim] = $m;
        return true;
    }

    public function cari(string $nim): ?Mahasiswa
    {
        return $this->data[$nim] ?? null;
    }

    /** @return array<string, Mahasiswa> */
    public function semua(): array
    {
        return $this->data;
    }

    public function ubah(string $nim, Mahasiswa $pengganti): bool
    {
        if (!isset($this->data[$nim])) return false;
        if ($pengganti->getNim() !== $nim) {
            throw new InvalidArgumentException('NIM tidak boleh diubah.');
        }
        $this->data[$nim] = $pengganti;
        return true;
    }

    public function hapus(string $nim): bool
    {
        if (!isset($this->data[$nim])) return false;
        unset($this->data[$nim]);
        return true;
    }
}