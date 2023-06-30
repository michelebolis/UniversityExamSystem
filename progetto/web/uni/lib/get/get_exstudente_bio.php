<?php
    include_once('lib/connection.php');
    function get_exstudente_bio($idutente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_exstudente_bio($1)';
        $res = pg_prepare($conn, "get_exstudente_bio", $sql);
        $res = pg_execute($conn, "get_exstudente_bio", array($idutente));
        pg_close($conn);
        return pg_fetch_assoc($res);
    }
    function get_exstudente_info($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_exstudente_info($1)';
        $res = pg_prepare($conn, "get_exstudente_info", $sql);
        $res = pg_execute($conn, "get_exstudente_info", array($matricola));
        pg_close($conn);
        return pg_fetch_assoc($res);
    }
    function get_exstudente_bioinfo($matricola, $idcorso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_exstudente_bio($1, $2)';
        $res = pg_prepare($conn, "get_exstudente_bio", $sql);
        $res = pg_execute($conn, "get_exstudente_bio", array($matricola, $idcorso));
        pg_close($conn);
        return pg_fetch_assoc($res);
    }        
?>