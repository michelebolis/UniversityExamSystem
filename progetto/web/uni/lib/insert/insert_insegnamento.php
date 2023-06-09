<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function insert_insegnamento($iddocente, $nome, $descrizione, $crediti, $annoAttivazione){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_insegnamento($1, $2, $3, $4, $5)';
        $res = pg_prepare($conn, "insert_insegnamento", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_insegnamento", array($iddocente, $nome, $descrizione, $crediti, $annoAttivazione))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>