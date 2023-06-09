<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_iscritti_esame($idesame){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_iscritti_esame($1)';
        $res = pg_prepare($conn, "get_iscritti_esame", $sql);
        $res = pg_execute($conn, "get_iscritti_esame", array($idesame));
        pg_close($conn);
        return $res;
    }
?>