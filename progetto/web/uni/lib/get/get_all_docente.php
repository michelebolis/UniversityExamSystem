<?php
    include_once('lib/connection.php');
    function get_all_docente(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_docente()';
        $res = pg_prepare($conn, "get_all_docente", $sql);
        $res = pg_execute($conn, "get_all_docente", array());
        pg_close($conn);
        return $res;
    }
?>