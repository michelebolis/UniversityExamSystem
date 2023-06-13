<?php
    include_once('lib/connection.php');
    function registrazione_esito_laurea($matricola, $corso, $data, $incremento, $lode){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.registrazione_esito_laurea($1, $2, $3, $4)';
        $res = pg_prepare($conn, "registrazione_esito_laurea", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "registrazione_esito_laurea", array($matricola, $corso, $data, $incremento, $lode))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>