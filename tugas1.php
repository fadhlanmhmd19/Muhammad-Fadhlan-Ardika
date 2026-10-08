<?php
$nim = "";
$nama = "";
$kelas = "";
$fakultas = "";
$prodi = "";
$semester = "";
$alamat = "";
$dosen_pengampu = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nim = trim($_POST["nim"] ?? "");
    $nama = trim($_POST["nama"] ?? "");
    $kelas = trim($_POST["kelas"] ?? "");
    $fakultas = trim($_POST["fakultas"] ?? "");
    $prodi = trim($_POST["prodi"] ?? "");
    $semester = trim($_POST["semester"] ?? "");
    $alamat = trim($_POST["alamat"] ?? "");
    $dosen_pengampu = trim($_POST["dosen_pengampu"] ?? "");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Identitas Data <br> Mahasiswa Universitas Muhammadiyah Sukabumi</title>

    <style>
        body {
            font-family: Century, sans-serif;
            background-color: #1A1A1A;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 600px;
            margin: auto;
            background-color: #7E8F9A;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #F9F8F6;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        input,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #234f7d;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #163652;
        }

        .hasil {
            margin-top: 30px;
            padding: 20px;
            background-color: #eef5fa;
            border-radius: 8px;
        }
    </style>

</head>

<body>

<div class="container">

    <h1>Identitas Data Mahasiswa <br> Universitas Muhammadiyah Sukabumi</h1>

    <form method="POST">

        <label>NIM</label>
        <input type="text" name="nim" value="<?= htmlspecialchars($nim) ?>" required>

        <label>Nama Mahasiswa</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required>

        <label>Kelas</label>
        <input type="text" name="kelas" value="<?= htmlspecialchars($kelas) ?>" required>

        <label>Fakultas</label>
        <input type="text" name="fakultas" value="<?= htmlspecialchars($fakultas) ?>" required>

        <label>Program Studi</label>
        <input type="text" name="prodi" value="<?= htmlspecialchars($nim) ?>" required>

        <label>Semester</label>
        <select name="semester" required>
            <option value="">-- Pilih Semester --</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
            <option value="3">Semester 3</option>
            <option value="4">Semester 4</option>
            <option value="5">Semester 5</option>
            <option value="6">Semester 6</option>
            <option value="7">Semester 7</option>
            <option value="8">Semester 8</option>
        </select>

        <label>Alamat</label>
        <input type="text" name="alamat" value="<?= htmlspecialchars($alamat) ?>" required>

        <label>Dosen Pengampu</label>
        <input type="text" name="dosen_pengampu" value="<?= htmlspecialchars($dosen_pengampu) ?>" required>

        <button type="submit">Klik Untuk Simpan Data</button>

    </form>


    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>

        <div class="hasil">

            <h2>Identitas Data Mahasiswa <br> Universitas Muhammadiyah Sukabumi</h2>

            <p><strong>NIM:</strong> <?= htmlspecialchars($nim) ?></p>
            <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
            <p><strong>Kelas:</strong> <?= htmlspecialchars($kelas) ?></p>
            <p><strong>Fakultas:</strong> <?= htmlspecialchars($fakultas) ?></p>
            <p><strong>Program Studi:</strong> <?= htmlspecialchars($prodi) ?></p>
            <p><strong>Semester:</strong> <?= htmlspecialchars($semester) ?></p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($alamat) ?></p>
            <p><strong>Dosen Pengampu:</strong> <?= htmlspecialchars($dosen_pengampu) ?></p>

        </div>

    <?php endif; ?>

</div>

</body>
</html>