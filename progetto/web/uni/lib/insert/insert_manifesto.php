<?php
    include_once('lib/connection.php');
    function insert_manifesto($idinsegnamento, $idcorso, $anno){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_manifesto($1, $2, $3)';
        $res = pg_prepare($conn, "insert_manifesto", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_manifesto", array($idinsegnamento, $idcorso, $anno))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>