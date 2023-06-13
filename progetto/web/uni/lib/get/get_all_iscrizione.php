<?php
    include_once('lib/connection.php');
    function get_all_iscrizione($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_iscrizione($1)';
        $res = pg_prepare($conn, "get_all_iscrizione", $sql);
        $res = pg_execute($conn, "get_all_iscrizione", array($matricola));
        pg_close($conn);
        return $res;
    }
    function get_all_nextiscrizione($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_nextiscrizione($1)';
        $res = pg_prepare($conn, "get_all_nextiscrizione", $sql);
        $res = pg_execute($conn, "get_all_nextiscrizione", array($matricola));
        pg_close($conn);
        return $res;
    }
?>