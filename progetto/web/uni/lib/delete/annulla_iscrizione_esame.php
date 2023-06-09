<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function annulla_iscrizione_esame($matricola, $idesame){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.annulla_iscrizione_esame($1, $2)';
        $res = pg_prepare($conn, "annulla_iscrizione_esame", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "annulla_iscrizione_esame", array($matricola, $idesame))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>