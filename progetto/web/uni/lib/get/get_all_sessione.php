<?php
    include_once('lib/connection.php');
    function get_all_sessione(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_sessione()';
        $res = pg_prepare($conn, "get_all_sessione", $sql);
        $res = pg_execute($conn, "get_all_sessione", array());
        pg_close($conn);
        return $res;
    }
?>