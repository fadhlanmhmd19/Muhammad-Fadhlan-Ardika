<?php
function hitungNilaiAkhir($absensi, $tugas, $uts, $uas) {
    return ($absensi * 0.10) + ($tugas * 0.25) + ($uts * 0.30) + ($uas * 0.35);
}

function getBobotIP($nilaiHuruf) {
    switch ($nilaiHuruf) {
        case "A": return 4.0;
        case "B": return 3.0;
        case "C": return 2.0;
        case "D": return 1.0;
        default:  return 0.0;
    }
}

$namaMahasiswa = "";
$nim           = "";
$kelas         = "";
$semester      = "";
$hasilMatkul   = [];
$ipSemester    = 0.0;

$daftarMataKuliah = [
    "matakuliah1" => "Kemuhammadiyahan",
    "matakuliah2" => "Kewirausahaan",
    "matakuliah3" => "Basis Data",
    "matakuliah4" => "Interaksi Manusia Dan Komputer",
    "matakuliah5" => "Pemrograman Berorientasi Objek",
    "matakuliah6" => "Rekayasa Perangkat Lunak",
    "matakuliah7" => "Statiska Informatika",
    "matakuliah8" => "Struktur Data"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaMahasiswa = trim($_POST["nama"] ?? "");
    $nim           = trim($_POST["nim"] ?? "");
    $kelas         = trim($_POST["kelas"] ?? "");
    $semester = trim($_POST["semester"] ?? "");

    $totalBobotIP = 0;

    foreach ($daftarMataKuliah as $key => $namaMK) {
        $absensi = (float)($_POST["absensi_" . $key] ?? 0);
        $tugas   = (float)($_POST["tugas_" . $key] ?? 0);
        $uts     = (float)($_POST["uts_" . $key] ?? 0);
        $uas     = (float)($_POST["uas_" . $key] ?? 0);

        $nilaiAkhir = hitungNilaiAkhir($absensi, $tugas, $uts, $uas);

        if ($nilaiAkhir >= 85) $huruf = "A";
        elseif ($nilaiAkhir >= 75) $huruf = "B";
        elseif ($nilaiAkhir >= 60) $huruf = "C";
        elseif ($nilaiAkhir >= 50) $huruf = "D";
        else $huruf = "E";

        $bobot = getBobotIP($huruf);
        $totalBobotIP += $bobot;

        $hasilMatkul[] = [
            "nama_mk"     => $namaMK,
            "nilai_akhir" => $nilaiAkhir,
            "huruf"       => $huruf,
            "is_lulus"    => ($nilaiAkhir >= 60)
        ];
    }

    $ipSemester = $totalBobotIP / count($daftarMataKuliah);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perhitungan Nilai IP Semester <br> Universitas Muhammadiyah Sukabumi</title>
    <style>
        body { 
            font-family: Century, sans-serif; 
            background-color: #1E293B; 
            margin: 0; 
            padding: 30px; 
            color: #0F172A; 
        }

        .container { 
            width: 650px; 
            margin: auto; 
            background-color: #334155; 
            padding: 25px; 
            border-radius: 10px; 
        }

        h1 { 
            text-align: center; 
            color: #F8FAFC; 
            margin-bottom: 5px; 
        }

        .sub-title { 
            text-align: center; 
            color: #5EEAD4; 
            font-size: 16px; 
            margin-top: 0; 
            margin-bottom: 20px; 
            font-weight: bold; 
        }

        h3 { 
            text-align: center; 
            color: #F8FAFC; 
            margin-bottom: 15px; 
        }

        label { 
            display: block; 
            margin-top: 10px; 
            color: #E2E8F0; 
            font-weight: bold; 
        }

        input { 
            width: 100%; 
            padding: 8px; 
            box-sizing: 
            border-box; 
            border: 1px solid #94A3B8; 
            border-radius: 4px; 
        }

        .mk-box { 
            background-color: #475569; 
            padding: 15px; 
            margin-bottom: 15px; 
            border-radius: 6px; 
        }

        .mk-title { 
            color: #5EEAD4; 
            margin-top: 0; 
            font-size: 18px; 
        }

        .grid-inputs { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 10px; 
        }

        button { 
            margin-top: 20px; 
            width: 100%; 
            padding: 12px; 
            background-color: #0D9488; 
            color: #FFFFFF; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer; 
            font-weight: bold; 
        }

        button:hover { 
            background-color: #0F766E; 
        }

        .hasil { 
            margin-top: 30px; 
            padding: 20px; 
            background-color: #F1F5F9; 
            border-radius: 8px; 
        }

        .status-lulus { 
            color: #16A34A; 
            font-weight: bold; 
        }

        .status-gagal { 
            color: #DC2626; 
            font-weight: bold; 
        }
        .ip-box { 
            background-color: #0D9488; 
            color: white; 
            padding: 10px; 
            text-align: center; 
            border-radius: 5px; 
            font-size: 20px; 
            margin-top: 15px; 
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Sistem Perhitungan Nilai IP Semester<br>Universitas Muhammadiyah Sukabumi</h1>
    <p class="sub-title">Program Studi Teknik Informatika</p>

    <form method="POST">
        <label>NIM</label>
        <input type="text" name="nim" value="<?= htmlspecialchars($nim) ?>" placeholder="Nomor Induk Mahasiswa" required>

        <label>Nama Mahasiswa</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($namaMahasiswa) ?>" placeholder="Nama Mahasiswa" required>

        <label>Kelas</label>
        <input type="text" name="kelas" value="<?= htmlspecialchars($kelas) ?>" placeholder="Contoh: A/B/C" required>

        <label>Semester</label>
        <select name="semester" required>
            <option value="">-- Pilih Semester --</option>
            <?php for ($i = 1; $i <= 14; $i++): ?>
                <option value="<?= $i ?>" <?= $semester == $i ? 'selected' : '' ?>>Semester <?= $i ?></option>
            <?php endfor; ?>
        </select>

        <h3 style="margin-top: 25px;">Input Nilai Mata Kuliah</h3>

        <?php foreach ($daftarMataKuliah as $key => $namaMK): ?>
            <div class="mk-box">
                <div class="mk-title"><?= htmlspecialchars($namaMK) ?></div>
                <div class="grid-inputs">
                    <div>
                        <label>Absensi (10%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="absensi_<?= $key ?>" required>
                    </div>
                    <div>
                        <label>Tugas (25%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="tugas_<?= $key ?>" required>
                    </div>
                    <div>
                        <label>UTS (30%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="uts_<?= $key ?>" required>
                    </div>
                    <div>
                        <label>UAS (35%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="uas_<?= $key ?>" required>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit">Hitung Nilai IP Semester</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="hasil">
            <h2>Hasil Rekapan Nilai Semester Mahasiswa</h2>
            <p><strong>NIM:</strong> <?= htmlspecialchars($nim) ?></p>
            <p><strong>Nama:</strong> <?= htmlspecialchars($namaMahasiswa) ?></p>
            <p><strong>Kelas:</strong> <?= htmlspecialchars($kelas) ?></p>
            <p><strong>Semester:</strong> <?= htmlspecialchars($semester) ?></p>
            <hr>
            
            <h3>Detail Nilai per Mata Kuliah</h3>
            <ul>
                <?php foreach ($hasilMatkul as $hm): ?>
                    <li>
                        <strong><?= htmlspecialchars($hm['nama_mk']) ?>:</strong> 
                        <?= number_format($hm['nilai_akhir'], 2) ?> (<?= $hm['huruf'] ?>) - 
                        <span class="<?= $hm['is_lulus'] ? 'status-lulus' : 'status-gagal' ?>">
                            <?= $hm['is_lulus'] ? 'LULUS' : 'MENGULANG' ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="ip-box">
                <strong>Indeks Prestasi (IP) Semester Saat Ini: <?= number_format($ipSemester, 2) ?></strong>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>