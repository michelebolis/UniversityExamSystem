<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_iscritti_laurea($idcorso, $data){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_iscritti_laurea($1, $2)';
        $res = pg_prepare($conn, "get_iscritti_laurea", $sql);
        $res = pg_execute($conn, "get_iscritti_laurea", array($idcorso, $data));
        pg_close($conn);
        return $res;
    }
?>