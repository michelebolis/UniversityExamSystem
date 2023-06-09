<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_sessione_esame($insegnamento){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_sessione_esame($1)';
        $res = pg_prepare($conn, "get_sessione_esame", $sql);
        $res = pg_execute($conn, "get_sessione_esame", array($insegnamento));
        pg_close($conn);
        return $res;
    }
?>