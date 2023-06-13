<?php
    include_once('lib/connection.php');
    function get_studente_stats($matricola, $corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_studente_stats($1, $2)';
        $res = pg_prepare($conn, "get_studente_stats", $sql);
        $res = pg_execute($conn, "get_studente_stats", array($matricola, $corso));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row;
    }
    function get_num_esami_passati($matricola, $corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_num_esami_passati($1, $2)';
        $res = pg_prepare($conn, "get_num_esami_passati", $sql);
        $res = pg_execute($conn, "get_num_esami_passati", array($matricola, $corso));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row['get_num_esami_passati'];
    }
?>