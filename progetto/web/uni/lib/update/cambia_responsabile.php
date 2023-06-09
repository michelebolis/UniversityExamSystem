<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function cambia_responsabile($insegnamento, $docente){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.cambia_responsabile($1, $2)';
        $res = pg_prepare($conn, "cambia_responsabile", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "cambia_responsabile", array($insegnamento, $docente))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>