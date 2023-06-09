<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_all_studente(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_studente()';
        $res = pg_prepare($conn, "get_all_studente", $sql);
        $res = pg_execute($conn, "get_all_studente", array());
        pg_close($conn);
        return $res;
    }
?>