<?php
    include_once('lib/connection.php');
    function get_all_esito_attesa_acc($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_all_esito_attesa_acc($1)';
        $res = pg_prepare($conn, "get_all_esito_attesa_acc", $sql);
        $res = pg_execute($conn, "get_all_esito_attesa_acc", array($matricola));
        pg_close($conn);
        return $res;
    }
?>