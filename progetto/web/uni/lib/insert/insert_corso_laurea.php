<?php
    include_once('lib/connection.php');
    function insert_corso_laurea($idcorso, $nome, $anniTotali, $valoreLode){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_corso_laurea($1, $2, $3, $4)';
        $res = pg_prepare($conn, "insert_corso_laurea", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_corso_laurea", array($idcorso, $nome, $anniTotali, $valoreLode))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>