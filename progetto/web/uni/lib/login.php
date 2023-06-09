<?php
    include_once('connection.php');
    function login(){
        $conn = connect();
        if (!$conn) {
            die;
        }
        $sql = 'SELECT * FROM uni.get_id_ruolo($1,$2)';
        $res = pg_prepare($conn, "get_id_ruolo", $sql);
        $res = pg_execute($conn, "get_id_ruolo", array($_POST['email'], $_POST['password']));
        if (pg_num_rows($res)==1){
            $row = pg_fetch_assoc($res);
            session_start();
            $_SESSION['utente']=$row['idutente'];
            $_SESSION['ruolo']=$row['ruolo'];
        }
        pg_close($conn);
    }
?>