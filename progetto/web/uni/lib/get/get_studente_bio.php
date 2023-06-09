<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_studente_bio($matricola, $corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_studente_bio($1, $2)';
        $res = pg_prepare($conn, "get_studente_bio", $sql);
        $res = pg_execute($conn, "get_studente_bio", array($matricola, $corso));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row;
    }
    function get_studente_biobyid($idutente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_studente_bio($1)';
        $res = pg_prepare($conn, "get_studente_bio", $sql);
        $res = pg_execute($conn, "get_studente_bio", array($idutente));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row;
    }
?>