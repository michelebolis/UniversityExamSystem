<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function accetta_esito($matricola, $idesame, $accettato){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.accetta_esito($1, $2, $3)';
        $res = pg_prepare($conn, "accetta_esito", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "accetta_esito", array($matricola, $idesame, $accettato))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>