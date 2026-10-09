<?php

require_once("parent.php");

class jawaban extends orangtua {
    public function __construct() {
        parent::__construct();
    }

    public function getJawabanBySoal($idsoal) {
        $sql = "SELECT * FROM jawaban WHERE idsoal = ? ORDER BY RAND()";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        $list = array();
        while ($row = $res->fetch_assoc()) {
            $list[] = $row;
        }
        return $list;
    }

    public function getJawabanById($idjawaban) {
        $sql = "SELECT * FROM jawaban WHERE idjawaban = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idjawaban);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function getJawabanBenar($idsoal) {
        $sql = "SELECT * FROM jawaban WHERE idsoal = ? AND benarkah = 1 LIMIT 1";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function checkJawaban($idjawaban) {
        $data = $this->getJawabanById($idjawaban);
        if ($data && $data['benarkah'] == 1) {
            return true;
        }
        return false;
    }
}
?>
