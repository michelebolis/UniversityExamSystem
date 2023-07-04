<?php
    include_once('lib/connection.php');
    function cambia_stato_corso($corso, $attivo){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.cambia_stato_corso($1, $2)';
        $res = pg_prepare($conn, "cambia_stato_corso", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "cambia_stato_corso", array($corso, $attivo))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>