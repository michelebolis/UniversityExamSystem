<?php
    include_once('lib/connection.php');
    function get_next_exam_bydoc($docente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_next_exam_bydoc($1)';
        $res = pg_prepare($conn, "get_next_exam_bydoc", $sql);
        $res = pg_execute($conn, "get_next_exam_bydoc", array($docente));
        pg_close($conn);
        return $res;
    }
?>