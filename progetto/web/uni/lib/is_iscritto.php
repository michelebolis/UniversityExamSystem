<?php
    include_once('connection.php');
    function is_iscritto($matricola, $idesame){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.is_iscritto($1, $2)';
        $res = pg_prepare($conn, "is_iscritto", $sql);
        $res = pg_execute($conn, "is_iscritto", array($matricola, $idesame));
        $row = pg_fetch_assoc($res);
        pg_close($conn);
        return $row;
    }
?>