<?php
    include_once('lib/connection.php');
    function get_past_exam($iddocente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_past_esame($1)';
        $res = pg_prepare($conn, "get_past_esame", $sql);
        $res = pg_execute($conn, "get_past_esame", array($iddocente));
        pg_close($conn);
        return $res;
    }
?>