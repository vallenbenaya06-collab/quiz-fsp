<?php
session_start();

require_once("soal.php");
require_once("jawaban.php");

if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    unset($_SESSION['jawaban_user']);
    unset($_SESSION['apakah_benar']);
    header("Location: index.php");
    exit();
}

$soalObj = new soal();
$jawabanObj = new jawaban();

$daftar_halaman = $soalObj->getAllPages();
$total_halaman = count($daftar_halaman);
if ($total_halaman < 1) {
    $total_halaman = 1;
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $halaman_saat_ini = (isset($_POST['halaman_saat_ini']) && is_numeric($_POST['halaman_saat_ini'])) ? (int)$_POST['halaman_saat_ini'] : 1;
    $tombol = isset($_POST['tombol']) ? $_POST['tombol'] : 'Next';

    if (isset($_POST['jawaban']) && is_array($_POST['jawaban'])) {
        foreach ($_POST['jawaban'] as $idsoal => $idjawaban) {
            $idsoal = (int)$idsoal;
            $idjawaban = (int)$idjawaban;

            $is_benar = $jawabanObj->checkJawaban($idjawaban) ? 1 : 0;

            $_SESSION['jawaban_user'][$idsoal] = $idjawaban;
            $_SESSION['apakah_benar'][$idsoal] = $is_benar;
        }
    }

    if ($tombol === 'Next') {
        if ($halaman_saat_ini >= $total_halaman) {
            header("Location: kesimpulan.php");
            exit();
        } else {
            $halaman_berikutnya = $halaman_saat_ini + 1;
            header("Location: index.php?page=" . $halaman_berikutnya);
            exit();
        }
    } elseif ($tombol === 'Previous') {
        $halaman_sebelumnya = ($halaman_saat_ini > 1) ? ($halaman_saat_ini - 1) : 1;
        header("Location: index.php?page=" . $halaman_sebelumnya);
        exit();
    }
}

$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
if ($page > $total_halaman) {
    $page = $total_halaman;
}

$is_first_page = ($page <= 1);
$is_last_page = ($page >= $total_halaman);

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
                Halaman <?php echo $page; ?> dari <?php echo $total_halaman; ?>
            </div>

            <form method="POST" action="index.php">
                <input type="hidden" name="halaman_saat_ini" value="<?php echo $page; ?>">

                <?php foreach ($list_soal as $s): ?>
                    <div class="soal-card">
                        <div class="soal-judul">
                            <?php echo $s['nomor'] . ". " . htmlentities((string)$s['pertanyaan']); ?>
                        </div>

                        <?php 
                        
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
                                        <input type="radio" name="jawaban[<?php echo $s['idsoal']; ?>]" value="<?php echo $j['idjawaban']; ?>" <?php echo $checked; ?>>
                                        <?php echo htmlentities((string)$j['isi_jawaban']); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>

                <div class="nav-buttons">
                    <?php if (!$is_first_page): ?>
                        <input type="submit" name="tombol" value="Previous" class="btn">
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
