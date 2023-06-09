<?php
    function connect(){
        include_once('config.php');
        return pg_connect('host='. host . ' port='. port . ' dbname='. dbname . ' user='. user . ' password='. password);
    }
?>
