<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_all_insegnamento(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_insegnamento()';
        $res = pg_prepare($conn, "get_all_insegnamento", $sql);
        $res = pg_execute($conn, "get_all_insegnamento", array());
        pg_close($conn);
        return $res;
    }
?>