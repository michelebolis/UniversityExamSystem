<?php
    include_once('lib/connection.php');
    function insert_sessione_laurea($data, $corso){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_sessione_laurea($1, $2)';
        $res = pg_prepare($conn, "insert_sessione_laurea", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_sessione_laurea", array($data, $corso))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>