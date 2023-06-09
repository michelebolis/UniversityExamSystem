<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_insegnamenti($docente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_insegnamenti($1)';
        $res = pg_prepare($conn, "get_insegnamenti", $sql);
        $res = pg_execute($conn, "get_insegnamenti", array($docente));
        pg_close($conn);
        return $res;
    }
?>