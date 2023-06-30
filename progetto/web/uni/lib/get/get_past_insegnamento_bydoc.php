<?php
    include_once('lib/connection.php');
    function get_past_insegnamento_bydoc($docente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_past_insegnamento_bydoc($1)';
        $res = pg_prepare($conn, "get_past_insegnamento_bydoc", $sql);
        $res = pg_execute($conn, "get_past_insegnamento_bydoc", array($docente));
        pg_close($conn);
        return $res;
    }
?>