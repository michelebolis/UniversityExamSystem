<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function cambia_credenziali($utente, $email, $password, $cellulare){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.modifica_utente($1, $2, $3, $4)';
        $res = pg_prepare($conn, "modifica_utente", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "modifica_utente", array($utente, $email, $password, $cellulare))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>
