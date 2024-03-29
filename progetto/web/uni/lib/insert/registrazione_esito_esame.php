<?php
    include_once('lib/connection.php');
    function registrazione_esito_esame($esame, $matricola, $voto, $lode){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.registrazione_esito_esame($1, $2, $3, $4)';
        $res = pg_prepare($conn, "registrazione_esito_esame", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "registrazione_esito_esame", array($matricola, $esame, $voto, $lode))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>