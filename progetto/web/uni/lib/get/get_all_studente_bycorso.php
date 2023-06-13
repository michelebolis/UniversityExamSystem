<?php
    include_once('lib/connection.php');
    function get_all_studente_bycorso($corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_studente_bycorso($1)';
        $res = pg_prepare($conn, "get_all_studente_bycorso", $sql);
        $res = pg_execute($conn, "get_all_studente_bycorso", array($corso));
        pg_close($conn);
        return $res;
    }
?>