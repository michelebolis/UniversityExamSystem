<?php
    function logout(){
        session_start();
        unset($_SESSION['utente']);
        unset($_SESSION['ruolo']);
    }
?>