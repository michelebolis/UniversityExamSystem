<?php
    include_once('lib/connection.php');
    function delete_studente($matricola, $idcorso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.delete_studente($1, $2)';
        $res = pg_prepare($conn, "delete_studente", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "delete_studente", array($matricola, $idcorso))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>