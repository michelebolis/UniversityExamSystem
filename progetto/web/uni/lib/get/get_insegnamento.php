<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_insegnamento($insegnamento){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_insegnamento($1)';
        $res = pg_prepare($conn, "get_insegnamento", $sql);
        $res = pg_execute($conn, "get_insegnamento", array($insegnamento));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row;
    }
?>