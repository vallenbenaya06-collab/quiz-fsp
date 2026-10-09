<?php

require_once("parent.php");

class soal extends orangtua {
    public function __construct() {
        parent::__construct();
    }

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
