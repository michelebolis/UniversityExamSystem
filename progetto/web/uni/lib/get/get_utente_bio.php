<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_utente_bio($idutente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_utente_bio($1)';
        $res = pg_prepare($conn, "get_utente_bio", $sql);
        $res = pg_execute($conn, "get_utente_bio", array($idutente));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row;
    }
?>