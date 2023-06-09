<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_id_studente($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_id_studente($1)';
        $res = pg_prepare($conn, "get_id_studente", $sql);
        $res = pg_execute($conn, "get_id_studente", array($matricola));
        $row = pg_fetch_row($res, NULL, PGSQL_NUM);
        pg_close($conn);
        return $row;
    }
?>