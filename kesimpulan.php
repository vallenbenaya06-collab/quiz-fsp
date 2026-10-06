<?php
session_start();

// Aturan 5: Menggunakan class soal dan jawaban buatan sendiri, bebas dari perintah SQL di halaman ini
require_once("soal.php");
require_once("jawaban.php");

$soalObj = new soal();
$jawabanObj = new jawaban();

// Ambil semua soal urut berdasarkan kolom nomor
$semua_soal = $soalObj->getAllSoal();

// Hitung skor akhir (1 nomor benar bernilai 10 sesuai instruksi tugas)
$skor_akhir = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesimpulan Quiz - Full Stack Programming</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Halaman Kesimpulan</h1>
        <p>Berikut adalah hasil evaluasi dari pengerjaan kuis Anda:</p>

        <?php if (empty($semua_soal)): ?>
            <p>Tidak ada data soal yang dapat ditampilkan.</p>
        <?php else: ?>
            <div class="kesimpulan-list">
                <?php foreach ($semua_soal as $s): ?>
                    <?php
                    $idsoal = (int)$s['idsoal'];
                    $jawaban_user_id = isset($_SESSION['jawaban_user'][$idsoal]) ? (int)$_SESSION['jawaban_user'][$idsoal] : null;

                    $isi_user = "Tidak dijawab";
                    $is_benar = false;

                    if ($jawaban_user_id !== null) {
                        $data_user = $jawabanObj->getJawabanById($jawaban_user_id);
                        if ($data_user) {
                            $isi_user = $data_user['isi_jawaban'];
                            $is_benar = ($data_user['benarkah'] == 1);
                        }
                    }

                    if ($is_benar) {
                        $skor_akhir += 10;
                    } else {
                        // Ambil kunci jawaban yang benar
                        $data_benar = $jawabanObj->getJawabanBenar($idsoal);
                        $isi_benar = $data_benar ? $data_benar['isi_jawaban'] : "-";
                    }
                    ?>

                    <div class="kesimpulan-item">
                        <div class="kesimpulan-pertanyaan">
                            <?php echo $s['nomor'] . ". " . htmlentities((string)$s['pertanyaan']); ?>
                        </div>

                        <?php if ($is_benar): ?>
                            <div class="jawaban-user">
                                Jawaban user : <?php echo htmlentities((string)$isi_user); ?> <span class="teks-benar">(benar)</span>
                            </div>
                        <?php else: ?>
                            <div class="jawaban-user">
                                Jawaban user : <?php echo htmlentities((string)$isi_user); ?> <span class="teks-salah">(salah)</span>
                            </div>
                            <div class="jawaban-benar">
                                Jawaban benar : <?php echo htmlentities((string)$isi_benar); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="skor-box">
                Skor Akhir : <?php echo $skor_akhir; ?>
            </div>

            <div style="margin-top: 25px; text-align: center;">
                <a href="index.php?action=reset" class="btn btn-primary" style="padding: 10px 25px; font-size: 15px;">PLAY AGAIN</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
