<?php
    include_once('lib/connection.php');
    function get_exstudente_bio($idutente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_exstudente_bio($1)';
        $res = pg_prepare($conn, "get_exstudente_bio", $sql);
        $res = pg_execute($conn, "get_exstudente_bio", array($idutente));
        pg_close($conn);
        return pg_fetch_assoc($res);
    }    
?>