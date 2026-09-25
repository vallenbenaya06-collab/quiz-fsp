-- Database: fullstack
-- File: quiz.sql

CREATE DATABASE IF NOT EXISTS `fullstack`;
USE `fullstack`;

-- Struktur tabel `soal`
DROP TABLE IF EXISTS `jawaban`;
DROP TABLE IF EXISTS `soal`;

CREATE TABLE `soal` (
  `idsoal` INT NOT NULL AUTO_INCREMENT,
  `nomor` INT NOT NULL,
  `pertanyaan` TEXT NOT NULL,
  `halaman_ke` INT NOT NULL,
  PRIMARY KEY (`idsoal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Struktur tabel `jawaban`
CREATE TABLE `jawaban` (
  `idjawaban` INT NOT NULL AUTO_INCREMENT,
  `idsoal` INT NOT NULL,
  `isi_jawaban` TEXT NOT NULL,
  `benarkah` TINYINT(1) NOT NULL,
  PRIMARY KEY (`idjawaban`),
  KEY `fk_jawaban_soal` (`idsoal`),
  CONSTRAINT `fk_jawaban_soal` FOREIGN KEY (`idsoal`) REFERENCES `soal` (`idsoal`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data untuk tabel `soal` (10 nomor soal trivia)
INSERT INTO `soal` (`idsoal`, `nomor`, `pertanyaan`, `halaman_ke`) VALUES
(1, 1, 'Siapakah nama saudara kembar dari Upin?', 1),
(2, 2, 'Planet apakah yang posisinya paling dekat dengan Matahari di tata surya kita?', 1),
(3, 3, 'Berikut ini adalah nama teman-teman Upin dan Ipin, KECUALI...', 2),
(4, 4, 'Ibu kota dari negara Jepang adalah...', 2),
(5, 5, 'Mamalia terbesar di dunia yang masih hidup di bumi saat ini adalah...', 2),
(6, 6, 'Gas apakah yang paling banyak terkandung di atmosfer Bumi?', 3),
(7, 7, 'Siapakah tokoh yang terkenal sebagai penemu bola lampu pijar praktis?', 3),
(8, 8, 'Mata uang resmi yang digunakan di negara Korea Selatan adalah...', 4),
(9, 9, 'Berapakah jumlah warna primer dalam teori dasar lingkaran warna (RYB)?', 4),
(10, 10, 'Lukisan legendaris Mona Lisa dibuat oleh seniman ternama dunia bernama...', 4);

-- Data untuk tabel `jawaban` (4 opsi tiap soal, 1 benar dan 3 salah)
-- Soal 1
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(1, 'Ipin', 1),
(1, 'Mail', 0),
(1, 'Ehsan', 0),
(1, 'Fizi', 0);

-- Soal 2
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(2, 'Merkurius', 1),
(2, 'Venus', 0),
(2, 'Mars', 0),
(2, 'Jupiter', 0);

-- Soal 3
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(3, 'Nobita', 1),
(3, 'Jarjit', 0),
(3, 'Mei Mei', 0),
(3, 'Susanti', 0);

-- Soal 4
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(4, 'Tokyo', 1),
(4, 'Kyoto', 0),
(4, 'Osaka', 0),
(4, 'Nagoya', 0);

-- Soal 5
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(5, 'Paus Biru', 1),
(5, 'Gajah Afrika', 0),
(5, 'Hiu Paus', 0),
(5, 'Jerapah', 0);

-- Soal 6
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(6, 'Nitrogen', 1),
(6, 'Oksigen', 0),
(6, 'Karbon Dioksida', 0),
(6, 'Argon', 0);

-- Soal 7
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(7, 'Thomas Alva Edison', 1),
(7, 'Alexander Graham Bell', 0),
(7, 'Nikola Tesla', 0),
(7, 'Albert Einstein', 0);

-- Soal 8
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(8, 'Won', 1),
(8, 'Yen', 0),
(8, 'Yuan', 0),
(8, 'Ringgit', 0);

-- Soal 9
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(9, '3 (Merah, Kuning, Biru)', 1),
(9, '2 (Hitam dan Putih)', 0),
(9, '4 (Merah, Hijau, Biru, Kuning)', 0),
(9, '7 (Warna Pelangi)', 0);

-- Soal 10
INSERT INTO `jawaban` (`idsoal`, `isi_jawaban`, `benarkah`) VALUES
(10, 'Leonardo da Vinci', 1),
(10, 'Vincent van Gogh', 0),
(10, 'Pablo Picasso', 0),
(10, 'Michelangelo', 0);
