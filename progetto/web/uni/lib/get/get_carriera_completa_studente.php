<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function get_carriera_completa_studente($matricola){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_carriera_completa_studente($1)';
        $res = pg_prepare($conn, "get_carriera_completa_studente", $sql);
        $res = pg_execute($conn, "get_carriera_completa_studente", array($matricola));
        pg_close($conn);
        return $res;
    }
?>
