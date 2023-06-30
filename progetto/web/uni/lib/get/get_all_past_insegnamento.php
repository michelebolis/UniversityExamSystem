<?php
    include_once('lib/connection.php');
    function get_all_past_insegnamento(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_past_insegnamento()';
        $res = pg_prepare($conn, "get_all_past_insegnamento", $sql);
        $res = pg_execute($conn, "get_all_past_insegnamento", array());
        pg_close($conn);
        return $res;
    }
?>