<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

final class MahasiswaBeasiswa extends Mahasiswa
{
    public function __construct(
        string $nim, string $nama, string $prodi, int $semester,
        private float $ukt, private float $potonganPersen
    ) {
        self::validasiBiaya($ukt, $potonganPersen);
        parent::__construct($nim, $nama, $prodi, $semester);
    }

    private static function validasiBiaya(float $ukt, float $potongan): void
    {
        if (!is_finite($ukt) || $ukt < 0 || !is_finite($potongan) || $potongan < 0 || $potongan > 100) {
            throw new InvalidArgumentException('UKT tidak boleh negatif dan potongan harus 0–100%.');
        }
    }

    public function ubahBiaya(float $ukt, float $potongan): void
    {
        self::validasiBiaya($ukt, $potongan);
        $this->ukt = $ukt;
        $this->potonganPersen = $potongan;
    }

    public function getJenis(): string { return 'Beasiswa'; }
    public function getUkt(): float { return $this->ukt; }
    public function getPotonganPersen(): float { return $this->potonganPersen; }

    // Method Overriding
    public function hitungBiaya(): float
    {
        return $this->ukt * (1 - $this->potonganPersen / 100);
    }
}