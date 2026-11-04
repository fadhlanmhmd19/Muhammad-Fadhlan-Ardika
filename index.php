<?php
declare(strict_types=1);
require_once __DIR__ . '/DataMahasiswa.php';
session_start();

if (!isset($_SESSION['repository']) || !($_SESSION['repository'] instanceof DataMahasiswa)) {
    $_SESSION['repository'] = new DataMahasiswa();
}

/** @var DataMahasiswa $repository */
$repository = $_SESSION['repository'];
$error = '';
$success = '';
$terpilih = null;

function aman(string $nilai): string { return htmlspecialchars($nilai, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function rupiah(float $nilai): string { return 'Rp ' . number_format($nilai, 0, ',', '.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $aksi = (string) ($_POST['aksi'] ?? '');
        if ($aksi === 'hapus') {
            $nim = trim((string) ($_POST['nim'] ?? ''));
            if (!$repository->hapus($nim)) throw new InvalidArgumentException('Mahasiswa tidak ditemukan.');
            $success = 'Data mahasiswa berhasil dihapus.';
        } elseif ($aksi === 'simpan') {
            $nim = trim((string) ($_POST['nim'] ?? ''));
            $nama = (string) ($_POST['nama'] ?? '');
            $prodi = (string) ($_POST['prodi'] ?? '');
            $semester = filter_var($_POST['semester'] ?? null, FILTER_VALIDATE_INT);
            $ukt = filter_var($_POST['ukt'] ?? null, FILTER_VALIDATE_FLOAT);
            $potongan = filter_var($_POST['potongan'] ?? '0', FILTER_VALIDATE_FLOAT);

            if ($semester === false || $ukt === false || $potongan === false) {
                throw new InvalidArgumentException('Semester, UKT, dan potongan harus berupa angka yang valid.');
            }

            $jenis = (string) ($_POST['jenis'] ?? '');
            if ($jenis === 'reguler') {
                $m = new MahasiswaReguler($nim, $nama, $prodi, $semester, $ukt);
            } elseif ($jenis === 'beasiswa') {
                $m = new MahasiswaBeasiswa($nim, $nama, $prodi, $semester, $ukt, $potongan);
            } else {
                throw new InvalidArgumentException('Pilih jenis mahasiswa yang valid.');
            }

            $mode = (string) ($_POST['mode'] ?? 'tambah');
            if ($mode === 'edit') {
                if (!$repository->ubah($nim, $m)) throw new InvalidArgumentException('Mahasiswa tidak ditemukan.');
                $success = 'Data mahasiswa berhasil diperbarui.';
            } elseif ($mode === 'tambah') {
                if (!$repository->tambah($m)) throw new InvalidArgumentException('NIM sudah terdaftar.');
                $success = 'Data mahasiswa berhasil ditambahkan.';
            } else {
                throw new InvalidArgumentException('Mode form tidak valid.');
            }
        } else {
            throw new InvalidArgumentException('Aksi tidak dikenal.');
        }

        $_SESSION['repository'] = $repository;
        $_SESSION['flash'] = $success;
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

if (isset($_SESSION['flash'])) {
    $success = (string) $_SESSION['flash'];
    unset($_SESSION['flash']);
}

if (isset($_GET['edit'])) $terpilih = $repository->cari((string) $_GET['edit']);
$cari = trim((string) ($_GET['q'] ?? ''));
$barisData = array_filter($repository->semua(), static function (Mahasiswa $m) use ($cari): bool {
    return $cari === '' || stripos($m->getNim() . ' ' . $m->getNama() . ' ' . $m->getProdi(), $cari) !== false;
});
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>CRUD Mahasiswa — Inheritance</title>
    <style>
        :root { font-family: system-ui, Arial, sans-serif; color: #172c4c; background: #f3f7fc; }
        body { max-width: 1120px; margin: 35px auto; padding: 0 16px; }
        h1 { margin-bottom: 4px; }
        p { color: #5b6980; }
        .panel { background: #fff; border-radius: 14px; padding: 22px; margin: 20px 0; box-shadow: 0 6px 24px #102b5010; }
        .fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 12px; }
        label { display: block; font-size: 13px; font-weight: 650; margin-bottom: 5px; }
        input, select { box-sizing: border-box; width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 7px; }
        button, .btn { background: #1764b5; color: white; padding: 10px 15px; border: 0; border-radius: 7px; cursor: pointer; text-decoration: none; display: inline-block; }
        .muted { background: #e8eff7; color: #183a61; }
        .danger { background: #c33849; }
        .actions { display: flex; gap: 7px; align-items: center; }
        .alert { padding: 12px; border-radius: 8px; background: #e4f8e9; margin-bottom: 10px; }
        .error { background: #ffe7e7; color: #c33849; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #e4eaf2; }
        th { background: #e9f2fd; }
        small { color: #65758b; }
        .scroll { overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🎓 CRUD Data Mahasiswa — Inheritance</h1>
    <p>Praktikum PBO Pertemuan 06 · Parent Class, Child Class, Overriding, dan Polimorfisme</p>

    <?php if ($success !== ''): ?><div class="alert"><?=aman($success)?></div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="alert error"><?=aman($error)?></div><?php endif; ?>

    <section class="panel">
        <h2><?= $terpilih ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa Baru' ?></h2>
        <form method="post">
            <input type="hidden" name="aksi" value="simpan">
            <input type="hidden" name="mode" value="<?= $terpilih ? 'edit' : 'tambah' ?>">
            
            <div class="fields">
                <div>
                    <label>NIM (Tidak dapat diubah)</label>
                    <input name="nim" required value="<?=aman($terpilih?->getNim() ?? '')?>" <?= $terpilih ? 'readonly' : '' ?>>
                </div>
                <div>
                    <label>Nama Lengkap</label>
                    <input name="nama" required value="<?=aman($terpilih?->getNama() ?? '')?>">
                </div>
                <div>
                    <label>Program Studi</label>
                    <input name="prodi" required value="<?=aman($terpilih?->getProdi() ?? '')?>">
                </div>
                <div>
                    <label>Semester (1–14)</label>
                    <input name="semester" type="number" min="1" max="14" required value="<?=aman((string)($terpilih?->getSemester() ?? 1))?>">
                </div>
                <div>
                    <label>Jenis Mahasiswa</label>
                    <select name="jenis" id="jenis">
                        <option value="reguler" <?=!($terpilih instanceof MahasiswaBeasiswa) ? 'selected' : ''?>>Mahasiswa Reguler</option>
                        <option value="beasiswa" <?=$terpilih instanceof MahasiswaBeasiswa ? 'selected' : ''?>>Mahasiswa Beasiswa</option>
                    </select>
                </div>
                <div>
                    <label>UKT Pokok (Rp)</label>
                    <input name="ukt" type="number" min="0" step="1000" required value="<?=aman((string)($terpilih?->getUkt() ?? 5000000))?>">
                </div>
                <div id="kolom-potongan">
                    <label>Potongan Beasiswa (%)</label>
                    <input name="potongan" type="number" min="0" max="100" step="0.1" value="<?=aman((string)($terpilih?->getPotonganPersen() ?? 50))?>">
                </div>
            </div>
            
            <p>
                <button type="submit"><?= $terpilih ? 'Simpan Perubahan' : 'Tambah Mahasiswa' ?></button> 
                <a class="btn muted" href="index.php">Reset Form</a>
            </p>
        </form>
    </section>

    <section class="panel">
        <h2>Daftar Mahasiswa (<?=count($barisData)?>)</h2>
        <form method="get" class="actions" style="margin-bottom:16px">
            <input name="q" placeholder="Cari NIM, Nama, atau Prodi" value="<?=aman($cari)?>">
            <button>Cari</button>
            <a class="btn muted" href="index.php">Semua Data</a>
        </form>

        <div class="scroll">
            <table>
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Semester</th>
                        <th>Jenis</th>
                        <th>UKT Pokok</th>
                        <th>Potongan</th>
                        <th>Biaya Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($barisData as $m): ?>
                    <tr>
                        <td><?=aman($m->getNim())?></td>
                        <td><?=aman($m->getNama())?></td>
                        <td><?=aman($m->getProdi())?></td>
                        <td><?=$m->getSemester()?></td>
                        <td><?=aman($m->getJenis())?></td>
                        <td><?=rupiah($m->getUkt())?></td>
                        <td><?=number_format($m->getPotonganPersen(), 1)?>%</td>
                        <td><strong><?=rupiah($m->hitungBiaya())?></strong></td>
                        <td>
                            <div class="actions">
                                <a class="btn muted" href="?edit=<?=urlencode($m->getNim())?>">Edit</a>
                                <form method="post" onsubmit="return confirm('Hapus data mahasiswa ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="nim" value="<?=aman($m->getNim())?>">
                                    <button class="danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$barisData): ?>
                        <tr><td colspan="9">Data mahasiswa tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <p><small>Data disimpan pada Session PHP lokal untuk simulasi praktikum.</small></p>
    </section>

    <script>
        const jenisSelect = document.getElementById('jenis');
        const kolomPotongan = document.getElementById('kolom-potongan');
        
        function aturTampilanForm() {
            kolomPotongan.style.display = (jenisSelect.value === 'beasiswa') ? 'block' : 'none';
        }
        
        jenisSelect.addEventListener('change', aturTampilanForm);
        aturTampilanForm();
    </script>
</body>
</html>