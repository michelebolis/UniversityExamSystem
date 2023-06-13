<?php
    include_once('lib/connection.php');
    function get_laurea($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_laurea($1)';
        $res = pg_prepare($conn, "get_laurea", $sql);
        $res = pg_execute($conn, "get_laurea", array($matricola));
        pg_close($conn);
        return $res;
    }
?>