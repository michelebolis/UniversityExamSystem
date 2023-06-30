<?php
    include_once('lib/connection.php');
    function get_propedeuticita($idinsegnamento){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_propedeuticita($1)';
        $res = pg_prepare($conn, "get_propedeuticita", $sql);
        $res = pg_execute($conn, "get_propedeuticita", array($idinsegnamento));
        pg_close($conn);
        return $res;
    }
?>