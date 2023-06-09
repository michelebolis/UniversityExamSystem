<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function iscrizione_laurea($matricola, $data, $idcorso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.iscrizione_laurea($1, $2, $3)';
        $res = pg_prepare($conn, "iscrizione_laurea", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "iscrizione_laurea", array($matricola, $data, $idcorso))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>