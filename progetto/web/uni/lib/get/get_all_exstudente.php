<?php
    include_once('lib/connection.php');
    function get_all_exstudente(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_exstudente()';
        $res = pg_prepare($conn, "get_all_exstudente", $sql);
        $res = pg_execute($conn, "get_all_exstudente", array());
        pg_close($conn);
        return $res;
    }
?>