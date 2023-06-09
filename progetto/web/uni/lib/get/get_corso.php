<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_corso($corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_corso($1)';
        $res = pg_prepare($conn, "get_corso", $sql);
        $res = pg_execute($conn, "get_corso", array($corso));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row;
    }
?>