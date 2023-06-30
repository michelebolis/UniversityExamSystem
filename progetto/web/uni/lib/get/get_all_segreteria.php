<?php
    include_once('lib/connection.php');
    function get_all_segreteria(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_segreteria()';
        $res = pg_prepare($conn, "get_all_segreteria", $sql);
        $res = pg_execute($conn, "get_all_segreteria", array());
        pg_close($conn);
        return $res;
    }
?>