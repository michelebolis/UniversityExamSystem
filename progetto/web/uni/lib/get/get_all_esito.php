<?php
    include_once('lib/connection.php');
    function get_all_esito($idesame){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_esito($1)';
        $res = pg_prepare($conn, "get_all_esito", $sql);
        $res = pg_execute($conn, "get_all_esito", array($idesame));
        pg_close($conn);
        return $res;
    }
?>