<?php
    include_once('lib/connection.php');
    function get_esame($idesame){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_esame($1)';
        $res = pg_prepare($conn, "get_esame", $sql);
        $res = pg_execute($conn, "get_esame", array($idesame));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row;
    }
?>