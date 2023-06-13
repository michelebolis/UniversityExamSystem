<?php
    include_once('lib/connection.php');
    function insert_propedeuticita($idinsegnamento, $idinsegnamentoRichiesto){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_propedeuticita($1, $2)';
        $res = pg_prepare($conn, "insert_propedeuticita", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_propedeuticita", array($idinsegnamento, $idinsegnamentoRichiesto))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>