<?php
// Class soal turunan dari orangtua (Slide Week 04b)
require_once("parent.php");

class soal extends orangtua {
    public function __construct() {
        parent::__construct();
    }

    // Mengambil daftar nomor halaman yang tersedia secara dinamis
    public function getAllPages() {
        $sql = "SELECT DISTINCT halaman_ke FROM soal ORDER BY halaman_ke ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $pages = array();
        while ($row = $res->fetch_assoc()) {
            $pages[] = (int)$row['halaman_ke'];
        }
        return $pages;
    }

    // Mengambil semua soal pada halaman tertentu berdasarkan kolom halaman_ke
    public function getSoalByHalaman($halaman_ke) {
        $sql = "SELECT * FROM soal WHERE halaman_ke = ? ORDER BY nomor ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $halaman_ke);
        $stmt->execute();
        $res = $stmt->get_result();
        $list = array();
        while ($row = $res->fetch_assoc()) {
            $list[] = $row;
        }
        return $list;
    }

    // Mengambil seluruh data soal untuk halaman kesimpulan
    public function getAllSoal() {
        $sql = "SELECT * FROM soal ORDER BY nomor ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $list = array();
        while ($row = $res->fetch_assoc()) {
            $list[] = $row;
        }
        return $list;
    }

    // Mengambil data satu soal berdasarkan idsoal
    public function getSoalById($idsoal) {
        $sql = "SELECT * FROM soal WHERE idsoal = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }
}
?>
