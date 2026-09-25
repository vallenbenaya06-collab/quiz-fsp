<?php
session_start();

// Aturan 5: Menggunakan class soal dan jawaban buatan sendiri, bebas dari perintah SQL di halaman ini
require_once("soal.php");
require_once("jawaban.php");

// Tangani aksi reset / play again jika diarahkan ke index.php?action=reset
if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    unset($_SESSION['jawaban_user']);
    unset($_SESSION['apakah_benar']);
    header("Location: index.php");
    exit();
}

$soalObj = new soal();
$jawabanObj = new jawaban();

// Mengambil seluruh nomor halaman yang tersedia secara dinamis
$daftar_halaman = $soalObj->getAllPages();
$total_halaman = count($daftar_halaman);

// Proses form submission (Next / Previous)
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $halaman_saat_ini = isset($_POST['halaman_saat_ini']) ? (int)$_POST['halaman_saat_ini'] : 1;
    $tombol = isset($_POST['tombol']) ? $_POST['tombol'] : 'Next';

    // Simpan jawaban user pada halaman saat ini ke dalam session sambil diperiksa benar/salah
    if (isset($_POST['jawaban']) && is_array($_POST['jawaban'])) {
        foreach ($_POST['jawaban'] as $idsoal => $idjawaban) {
            $idsoal = (int)$idsoal;
            $idjawaban = (int)$idjawaban;
            
            // Cek apakah jawaban benar melalui class jawaban
            $is_benar = $jawabanObj->checkJawaban($idjawaban) ? 1 : 0;

            $_SESSION['jawaban_user'][$idsoal] = $idjawaban;
            $_SESSION['apakah_benar'][$idsoal] = $is_benar;
        }
    }

    // Cari posisi index halaman saat ini dalam daftar halaman
    $posisi = array_search($halaman_saat_ini, $daftar_halaman);
    if ($posisi === false) {
        $posisi = 0;
    }

    if ($tombol === 'Next') {
        // Jika sudah di halaman terakhir, lanjut ke halaman kesimpulan
        if ($posisi >= $total_halaman - 1) {
            header("Location: kesimpulan.php");
            exit();
        } else {
            $halaman_berikutnya = $daftar_halaman[$posisi + 1];
            header("Location: index.php?page=" . $halaman_berikutnya);
            exit();
        }
    } elseif ($tombol === 'Previous') {
        // Jika klik Previous, kembali ke halaman sebelumnya
        if ($posisi > 0) {
            $halaman_sebelumnya = $daftar_halaman[$posisi - 1];
            header("Location: index.php?page=" . $halaman_sebelumnya);
            exit();
        } else {
            header("Location: index.php?page=" . $daftar_halaman[0]);
            exit();
        }
    }
}

// Menentukan halaman yang sedang ditampilkan
$page = isset($_GET['page']) ? (int)$_GET['page'] : (isset($daftar_halaman[0]) ? $daftar_halaman[0] : 1);
if (!in_array($page, $daftar_halaman) && !empty($daftar_halaman)) {
    $page = $daftar_halaman[0];
}

$current_idx = array_search($page, $daftar_halaman);
$is_first_page = ($current_idx === 0 || $current_idx === false);
$is_last_page = ($current_idx !== false && $current_idx === $total_halaman - 1);

// Mengambil soal untuk halaman ini
$list_soal = $soalObj->getSoalByHalaman($page);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Online - Full Stack Programming</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Kuis Online Trivia</h1>

        <?php if (empty($daftar_halaman) || empty($list_soal)): ?>
            <div class="soal-card">
                <p>Data soal belum tersedia di database. Pastikan database <code>fullstack</code> sudah dibuat dan file <code>quiz.sql</code> telah di-import.</p>
            </div>
        <?php else: ?>
            <div class="info-halaman">
                Halaman <?php echo ($current_idx + 1); ?> dari <?php echo $total_halaman; ?> (Nomor Halaman Kolom DB: <?php echo $page; ?>)
            </div>

            <form method="POST" action="index.php">
                <input type="hidden" name="halaman_saat_ini" value="<?php echo $page; ?>">

                <?php foreach ($list_soal as $s): ?>
                    <div class="soal-card">
                        <div class="soal-judul">
                            <?php echo $s['nomor'] . ". " . htmlentities($s['pertanyaan']); ?>
                        </div>

                        <?php 
                        // Ambil opsi jawaban (posisi diacak secara random sesuai ketentuan tugas)
                        $list_jawaban = $jawabanObj->getJawabanBySoal($s['idsoal']);
                        ?>
                        <ul class="opsi-list">
                            <?php foreach ($list_jawaban as $j): ?>
                                <?php
                                $checked = "";
                                if (isset($_SESSION['jawaban_user'][$s['idsoal']]) && $_SESSION['jawaban_user'][$s['idsoal']] == $j['idjawaban']) {
                                    $checked = "checked";
                                }
                                ?>
                                <li class="opsi-item">
                                    <label>
                                        <input type="radio" name="jawaban[<?php echo $s['idsoal']; ?>]" value="<?php echo $j['idjawaban']; ?>" <?php echo $checked; ?> required>
                                        <?php echo htmlentities($j['isi_jawaban']); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>

                <div class="nav-buttons">
                    <?php if (!$is_first_page): ?>
                        <input type="submit" name="tombol" value="Previous" class="btn" formnovalidate>
                    <?php endif; ?>

                    <div class="nav-buttons-right">
                        <input type="submit" name="tombol" value="Next" class="btn btn-primary">
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
