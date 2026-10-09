<?php

require_once("data.php");

class orangtua {
    protected $mysqli;

    public function __construct() {
        mysqli_report(MYSQLI_REPORT_OFF);
        $this->mysqli = new mysqli(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
        if ($this->mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $this->mysqli->connect_error);
        }
    }

    public function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }
}
?>
