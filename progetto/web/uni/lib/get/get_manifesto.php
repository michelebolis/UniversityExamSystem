<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_manifesto($corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_manifesto($1)';
        $res = pg_prepare($conn, "get_manifesto", $sql);
        $res = pg_execute($conn, "get_manifesto", array($corso));
        pg_close($conn);
        return $res;
    }
?>