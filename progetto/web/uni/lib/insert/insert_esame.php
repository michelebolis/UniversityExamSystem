<?php
    include_once('lib/connection.php');
    function insert_esame($docente, $insegnamento, $data, $orario){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_esame($1, $2, $3, $4)';
        $res = pg_prepare($conn, "insert_esame", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_esame", array($docente, $insegnamento, $data, $orario))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>