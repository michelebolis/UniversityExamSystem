<!doctype html>
<html lang="en">
  <head>
    <title>University Exam System: laurea</title>
    <?php include_once('template/head.html'); ?>
  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<?php
    session_start();
    if ($_SESSION['ruolo']=='Segreteria'){
        include_once('template/navbar_segreteria.php');
?>
    <div class="container">
    <div class="row">
        <div class="list-group col-3">
            <a aria-current="true" class="list-group-item list-group-item-action 
            <?php 
                if (!isset($_GET['esito']) && !isset($_GET['iscritti'])){
                    echo "active";
                }
            ?>
            " href="laurea.php">
                Inserisci una sessione di laurea
            </a>

            <a class="list-group-item list-group-item-action 
            <?php 
                if (isset($_GET['esito']) && $_GET['esito']=='True'){
                    echo "active";
                }
            ?>
            " href="laurea.php?esito=True">
                Registra l'esito della laurea
            </a>
            <a class="list-group-item list-group-item-action 
            <?php 
                if (isset($_GET['iscritti']) && $_GET['iscritti']=='True'){
                    echo "active";
                }
            ?>
            " href="laurea.php?iscritti=True">
                Visualizza gli iscritti a una sessione di laurea
            </a>
        </div>
        
            <?php 
            if (isset($_GET['esito']) && $_GET['esito']=='True'){
                include_once('template/form_newlaurea.php'); 
            }else if(isset($_GET['iscritti'])){
                include_once('template/form_viewiscrittilaurea.php');
            }else{
                include_once('template/form_newsessione.php');
            }
            ?>
    </div>
    </div>
<?php
    }else if ($_SESSION['ruolo']=='Studente'){
        include_once('template/navbar_studente.php');
?>
    <div class="container">
    <div class="row">
        <div class="list-group col-3">
            <a aria-current="true" class="list-group-item list-group-item-action active" href="laurea.php">
                Iscriviti ad una sessione di laurea
            </a>
        </div>
        
        <?php 
            include_once('template/form_iscrizione_laurea.php'); 
        ?>
    </div>
    </div>
    <?php
    }
    ?>
  </body>
</html>

