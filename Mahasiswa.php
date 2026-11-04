<?php
declare(strict_types=1);

abstract class Mahasiswa
{
    public function __construct(
        private string $nim,
        private string $nama,
        private string $prodi,
        private int $semester
    ) {
        self::validasiProfil($nim, $nama, $prodi, $semester);
        $this->nim = trim($nim);
        $this->nama = trim($nama);
        $this->prodi = trim($prodi);
    }

    private static function validasiProfil(string $nim, string $nama, string $prodi, int $semester): void
    {
        if (trim($nim) === '' || trim($nama) === '' || trim($prodi) === '') {
            throw new InvalidArgumentException('NIM, nama, dan prodi wajib diisi.');
        }
        if ($semester < 1 || $semester > 14) {
            throw new InvalidArgumentException('Semester harus berada di antara 1 hingga 14.');
        }
    }

    public function getNim(): string { return $this->nim; }
    public function getNama(): string { return $this->nama; }
    public function getProdi(): string { return $this->prodi; }
    public function getSemester(): int { return $this->semester; }

    public function ubahProfil(string $nama, string $prodi, int $semester): void
    {
        self::validasiProfil($this->nim, $nama, $prodi, $semester);
        $this->nama = trim($nama);
        $this->prodi = trim($prodi);
        $this->semester = $semester;
    }

    abstract public function getJenis(): string;
    abstract public function hitungBiaya(): float;
    abstract public function getUkt(): float;
    abstract public function getPotonganPersen(): float;
}