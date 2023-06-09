<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/xampp/uni/lib/connection.php');
    function insert_segreteria($nome, $cognome, $email, $password, $cellulare){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_segreteria($1, $2, $3, $4, $5)';
        $res = pg_prepare($conn, "insert_segreteria", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_segreteria", array($nome, $cognome, $email, $password, $cellulare))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
    function insert_docente($nome, $cognome, $email, $password, $cellulare, $inizioRapporto, $insegnamento){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_docente($1, $2, $3, $4, $5, $6, $7)';
        $res = pg_prepare($conn, "insert_docente", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_docente", array($nome, $cognome, $email, $password, $cellulare, $inizioRapporto, $insegnamento))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
    function insert_studente($nome, $cognome, $email, $password, $cellulare, $codiceFiscale, $corso, $dataImmatricolazione){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'CALL uni.insert_studente($1, $2, $3, $4, $5, $6, $7, $8)';
        $res = pg_prepare($conn, "insert_studente", $sql);
        error_reporting(E_ERROR | E_PARSE);
        if(pg_execute($conn, "insert_studente", array($nome, $cognome, $email, $password, $cellulare, $codiceFiscale, $corso, $dataImmatricolazione))){
            $err= null;
        }else{
            $err= explode('CONTEXT',(pg_last_error($conn)))[0];            
        }    
        pg_close($conn);
        return $err;
    }
?>