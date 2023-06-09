<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_all_sessione_bycorso($corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_sessione_bycorso($1)';
        $res = pg_prepare($conn, "get_all_sessione_bycorso", $sql);
        $res = pg_execute($conn, "get_all_sessione_bycorso", array($corso));
        pg_close($conn);
        return $res;
    }
?>