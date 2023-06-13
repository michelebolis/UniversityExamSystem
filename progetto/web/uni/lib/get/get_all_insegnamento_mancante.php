<?php
    include_once('lib/connection.php');
    function get_all_insegnamento_mancante($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_insegnamento_mancante($1)';
        $res = pg_prepare($conn, "get_all_insegnamento_mancante", $sql);
        $res = pg_execute($conn, "get_all_insegnamento_mancante", array($matricola));
        pg_close($conn);
        return $res;
    }
?>