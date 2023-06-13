<?php
    include_once('lib/connection.php');
    function get_carriera_studente($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_carriera_studente($1)';
        $res = pg_prepare($conn, "get_carriera_studente", $sql);
        $res = pg_execute($conn, "get_carriera_studente", array($matricola));
        pg_close($conn);
        return $res;
    }
    function get_carriera_studente_past($matricola, $corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_carriera_studente($1, $2)';
        $res = pg_prepare($conn, "get_carriera_studente", $sql);
        $res = pg_execute($conn, "get_carriera_studente", array($matricola, $corso));
        pg_close($conn);
        return $res;
    }
?>
