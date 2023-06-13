<?php
    include_once('lib/connection.php');
    function get_past_corso($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_past_corso($1)';
        $res = pg_prepare($conn, "get_past_corso", $sql);
        $res = pg_execute($conn, "get_past_corso", array($matricola));
        pg_close($conn);
        return $res;
    }
?>