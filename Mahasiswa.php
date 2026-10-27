<?php
declare(strict_types=1);

class Mahasiswa {
    private array $atributTambahan = [];

    public function __construct(
        public string $nim,
        public string $nama,
        public string $fakultas = "Belum diisi",
        public string $prodi = "",
        public string $asalSekolah = "",
        public int $semester = 1,
        public string $hobi = "",
        public string $agama = ""
    ) {
        if ($nim === '' || $nama === '' || $semester < 1 || $semester > 14) {
            throw new InvalidArgumentException('NIM, nama, dan semester harus valid.');
        }
    }

    public function ringkasan(): string {
        return $this->nim . ' - ' . $this->nama;
    }

    public function __set(string $nama, mixed $nilai): void {
        $this->atributTambahan[$nama] = $nilai;
    }
    public function __get(string $nama): mixed {
        return $this->atributTambahan[$nama] ?? null;
    }
    public function __isset(string $nama): bool {
        return isset($this->atributTambahan[$nama]);
    }
    public function __unset(string $nama): void {
        unset($this->atributTambahan[$nama]);
    }

    public function __call(string $nama, array $argumen): mixed {
        if ($nama === 'profilSingkat' && count($argumen) === 0) {
            return $this->ringkasan();
        }
        throw new BadMethodCallException('Method tidak tersedia: ' . $nama);
    }

    public function __destruct() {
        if (defined('DEMO_LIFECYCLE') && DEMO_LIFECYCLE) {
            error_log('Destructor Mahasiswa: ' . $this->nim);
        }
    }
}