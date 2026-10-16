<?php
declare(strict_types=1);
require_once __DIR__ . '/DataMahasiswa.php';
session_start();

$repo = new DataMahasiswa($_SESSION['mahasiswa'] ?? []);
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);
$error = '';
$edit = null;

function aman(mixed $teks): string {
    return htmlspecialchars((string) $teks, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = (string) ($_POST['aksi'] ?? '');
    $nimLama = trim((string) ($_POST['nim_lama'] ?? ''));
    $nim = trim((string) ($_POST['nim'] ?? ''));
    try {
        if ($aksi === 'hapus') {
            $berhasil = $repo->hapus($nim);
            $pesan    = $berhasil ? 'Data berhasil dihapus.' : 'NIM tidak ditemukan.';

        } elseif (in_array($aksi, ['tambah', 'ubah'], true)) {

            $nama          = trim((string) ($_POST['nama'] ?? ''));
            $fakultas      = trim((string) ($_POST['fakultas'] ?? ''));
            $prodi         = trim((string) ($_POST['prodi'] ?? ''));
            $asalSekolah   = trim((string) ($_POST['asal_sekolah'] ?? ''));
            $hobi          = trim((string) ($_POST['hobi'] ?? ''));
            $agama         = trim((string) ($_POST['agama'] ?? ''));
            $jalurMasuk    = trim((string) ($_POST['jalur_masuk'] ?? ''));
            $tahunAngkatan = trim((string) ($_POST['tahun_angkatan'] ?? ''));
            $alamat        = trim((string) ($_POST['alamat'] ?? ''));
            $programKelas  = trim((string) ($_POST['program_kelas'] ?? ''));
            
            $semester      = filter_var($_POST['semester'] ?? null, FILTER_VALIDATE_INT);
            
            if ($nim === '' || $nama === '' || $fakultas === '' || $prodi === '' || $asalSekolah === '' || $jalurMasuk === '' || $tahunAngkatan === '' || $alamat === '' || $programKelas === '' || $semester === false || $semester < 1 || $semester > 14) {
                throw new InvalidArgumentException('Lengkapi semua data wajib dan isi semester 1–14.');
            }
            
            $mhs = new Mahasiswa($nim, $nama, $fakultas, $prodi, $asalSekolah, $semester, $hobi, $agama, $jalurMasuk, $tahunAngkatan, $alamat, $programKelas);
            
            if ($aksi === 'tambah') {
                $berhasil = $repo->tambah($mhs);
            } else {
                $berhasil = $repo->ubah($nimLama, $mhs);
            }

            if (!$berhasil) {
                throw new InvalidArgumentException('Operasi gagal: NIM sudah digunakan atau data lama tidak ditemukan.');
            }
            
            $pesan = $aksi === 'tambah' ? 'Data berhasil ditambahkan.' : 'Data berhasil diubah.';
        } else {
            throw new InvalidArgumentException('Aksi tidak dikenal.');
        }
        $_SESSION['mahasiswa'] = $repo->semua();
        $_SESSION['pesan'] = $pesan;
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

if (isset($_GET['edit'])) $edit = $repo->cari((string) $_GET['edit']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Praktikum PBO - CRUD Mahasiswa</title>
<style>
*{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;background:#f2f6fb;color:#17324d}.wrap{max-width:1200px;margin:30px auto;padding:0 18px}h1{margin-bottom:6px}.sub{color:#61778c}.panel{background:white;border-radius:12px;padding:24px;margin:22px 0;box-shadow:0 4px 18px #1233}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}label{font-weight:600;font-size:14px}input,select{display:block;width:100%;padding:11px;margin-top:7px;border:1px solid #cbd7e2;border-radius:7px}button,.btn{display:inline-block;border:0;border-radius:7px;padding:10px 15px;background:#195d96;color:white;text-decoration:none;cursor:pointer;font-size:14px}.secondary{background:#6b7886}.danger{background:#b73f45}.alert{padding:12px;border-radius:7px;background:#e5f5e9;margin:15px 0}.error{background:#ffe6e7}.tablewrap{overflow:auto}table{width:100%;border-collapse:collapse;font-size:13px}th,td{text-align:left;border-bottom:1px solid #e3eaf0;padding:11px}th{background:#eaf3fb}td form{display:inline}td .btn,td button{margin:2px}small{color:#61778c}@media(max-width:680px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body><main class="wrap">
    <h1>Data Mahasiswa</h1><p class="sub">Praktikum PBO: CRUD Mahasiswa dengan rekapitulasi angkatan dinamis, jalur masuk, dan program kelas.</p>
    <?php if ($pesan): ?><div class="alert"><?= aman($pesan) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= aman($error) ?></div><?php endif; ?>
<section class="panel"><h2><?= $edit ? 'Ubah Data Mahasiswa' : 'Tambah Data Mahasiswa' ?></h2>

<form method="post">
<input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">
<input type="hidden" name="nim_lama" value="<?= aman($edit?->nim ?? '') ?>">
<div class="grid">

<?php
 $fields = [
 ['nim','NIM', $edit?->nim ?? ''],
 ['nama','Nama', $edit?->nama ?? ''],
 ['fakultas','Fakultas', $edit?->fakultas ?? ''],
 ['prodi','Program Studi', $edit?->prodi ?? ''],
 ['asal_sekolah','Asal Sekolah', $edit?->asalSekolah ?? ''],
 ['semester','Semester', (string)($edit?->semester ?? '')],
 ['hobi','Hobi', $edit?->hobi ?? ''],
 ['tahun_angkatan','Tahun Angkatan', $edit?->tahunAngkatan ?? ''],
 ['jalur_masuk','Jalur Masuk', $edit?->jalurMasuk ?? ''],
 ['program_kelas','Program Kelas', $edit?->programKelas ?? ''],
 ['alamat','Alamat', $edit?->alamat ?? ''],
 ['agama','Agama', $edit?->agama ?? ''],
];
foreach ($fields as [$name,$label,$value]): ?>

<label>
    <?= aman($label) ?>

    <?php if ($name === 'agama'): ?>

        <select name="agama" required>
            <option value="">-- Pilih Agama --</option>
            <?php
            $daftarAgama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
            foreach ($daftarAgama as $pilihan):
            ?>
                <option value="<?= aman($pilihan) ?>" <?= $value === $pilihan ? 'selected' : '' ?>><?= aman($pilihan) ?></option>
            <?php endforeach; ?>
        </select>

    <?php elseif ($name === 'jalur_masuk'): ?>

        <select name="jalur_masuk" required>
            <option value="">-- Pilih Jalur Masuk --</option>
            <?php
            $daftarJalur = [
                'Seleksi Jalur PMDK/Undangan (SNBP, SNBT, dsb)',
                'Mandiri',
                'Seleksi One Day Service (ODS)',
                'Beasiswa Hafidz/Qori',
                'Beasiswa KIP-K',
                'Beasiswa Prestasi',
                'Beasiswa Adik',
                'Beasiswa Pegawai/Putra/i UMMI/Kader Muhammadiyah'
            ];
            foreach ($daftarJalur as $pilihan):
            ?>
                <option value="<?= aman($pilihan) ?>" <?= $value === $pilihan ? 'selected' : '' ?>><?= aman($pilihan) ?></option>
            <?php endforeach; ?>
        </select>

    <?php elseif ($name === 'program_kelas'): ?>

        <select name="program_kelas" required>
            <option value="">-- Pilih Program Kelas --</option>
            <?php
            $daftarKelas = [
                'Reguler',
                'Karyawan'
            ];
            foreach ($daftarKelas as $pilihan):
            ?>
                <option value="<?= aman($pilihan) ?>" <?= $value === $pilihan ? 'selected' : '' ?>><?= aman($pilihan) ?></option>
            <?php endforeach; ?>
        </select>

    <?php else: ?>

        <input
            name="<?= aman($name) ?>"
            value="<?= aman($value) ?>"
            <?php if ($name === 'semester'): ?>
                type="number" min="1" max="14"
            <?php elseif ($name === 'tahun_angkatan'): ?>
                type="number" min="2003" max="2026"
            <?php else: ?>
                type="text"
            <?php endif; ?>
            <?= $name !== 'hobi' ? 'required' : '' ?>
        >

    <?php endif; ?>

</label>

<?php endforeach; ?>

</div><p><button type="submit"><?= $edit ? 'Simpan Perubahan' : 'Tambah Mahasiswa' ?></button> <?php if ($edit): ?><a class="btn secondary" href="index.php">Batal</a><?php endif; ?></p>
</form></section>

<section class="panel">
    <h2>Daftar Mahasiswa (Total: <?= $repo->jumlahMahasiswa() ?>)</h2>
    <?php
    $daftarTahun = [];
    foreach ($repo->semua() as $mhs) {
        if (!empty($mhs->tahunAngkatan)) {
            $daftarTahun[$mhs->tahunAngkatan] = true;
        }
    }
    $daftarTahun = array_keys($daftarTahun);
    sort($daftarTahun);
    ?>
    <?php if (!empty($daftarTahun)): ?>
        <p style="margin-bottom: 6px; font-weight: 600;">Rekap Berdasarkan Angkatan:</p>
        <ul style="margin-top: 0; margin-bottom: 15px; padding-left: 20px;">
            <?php foreach ($daftarTahun as $thn): ?>
                <li>Angkatan <?= aman($thn) ?>: <strong><?= $repo->jumlahBerdasarkanAngkatan($thn) ?></strong> mahasiswa</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<div class="tablewrap">
    <table>
       <thead>
       <tr>
       <th>NIM</th>
       <th>Nama</th>
       <th>Fakultas</th>
       <th>Prodi</th>
       <th>Asal Sekolah</th>
       <th>Semester</th>
       <th>Hobi</th>
       <th>Angkatan</th>
       <th>Jalur</th>
       <th>Kelas</th>
       <th>Alamat</th>
       <th>Agama</th>
       <th>Aksi</th>
       </tr>
    </thead>
  <tbody>
<?php foreach ($repo->semua() as $m): ?>
<tr>
    <td><?= aman($m->nim) ?>           </td>
    <td><?= aman($m->nama) ?>          </td>
    <td><?= aman($m->fakultas) ?>      </td>
    <td><?= aman($m->prodi) ?>         </td>
    <td><?= aman($m->asalSekolah) ?>   </td>
    <td><?= $m->semester ?>            </td>
    <td><?= aman($m->hobi) ?>          </td>
    <td><?= aman($m->tahunAngkatan) ?> </td>
    <td><?= aman($m->jalurMasuk) ?>    </td>
    <td><?= aman($m->programKelas) ?>  </td>
    <td><?= aman($m->alamat) ?>        </td>
    <td><?= aman($m->agama) ?>         </td>
    
    <td><a class="btn" href="?edit=<?= urlencode($m->nim) ?>">Edit</a>
     <form method="post" onsubmit="return confirm('Hapus data mahasiswa ini?')">
        <input type="hidden" name="aksi" value="hapus">
        <input type="hidden" name="nim" value="<?= aman($m->nim) ?>">
        <button class="danger" type="submit">Hapus</button>
    </form>
</td>
</tr>
<?php endforeach; ?>
    <?php if (!$repo->semua()): ?>
        <tr>
            <td colspan="13">Belum ada data. Tambahkan mahasiswa melalui form di atas.</td>
        </tr>
    <?php endif; ?>
       
     </tbody>
        </table>
        </div>
        <p><small>Catatan: Data dikelola menggunakan OOP PHP lengkap dengan entitas, array repositori, modifier privat/publik, instansiasi objek, serta method tambahan.</small></p>
        </section>
        </main>
</body>
</html>