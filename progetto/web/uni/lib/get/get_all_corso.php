<?php
    include_once('lib/connection.php');
    function get_all_corso(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_corso()';
        $res = pg_prepare($conn, "get_all_corso", $sql);
        $res = pg_execute($conn, "get_all_corso", array());
        pg_close($conn);
        return $res;
    }
?>