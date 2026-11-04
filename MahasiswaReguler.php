<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

final class MahasiswaReguler extends Mahasiswa
{
    public function __construct(
        string $nim, string $nama, string $prodi, int $semester,
        private float $ukt
    ) {
        self::validasiUkt($ukt);
        parent::__construct($nim, $nama, $prodi, $semester);
    }

    private static function validasiUkt(float $nominal): void
    {
        if (!is_finite($nominal) || $nominal < 0) {
            throw new InvalidArgumentException('UKT tidak boleh bernilai negatif.');
        }
    }

    public function ubahUkt(float $ukt): void
    {
        self::validasiUkt($ukt);
        $this->ukt = $ukt;
    }

    public function getJenis(): string { return 'Reguler'; }
    public function getUkt(): float { return $this->ukt; }
    public function getPotonganPersen(): float { return 0.0; }
    public function hitungBiaya(): float { return $this->ukt; }
}