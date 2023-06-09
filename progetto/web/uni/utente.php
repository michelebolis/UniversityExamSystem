<!doctype html>
<html lang="en">
  <head>
    <title>University Exam System: utente</title>
    <?php include_once('template/head.html'); ?>
  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <?php
    if (!isset($_GET['change'])){
        session_start();
        include_once('template/navbar_segreteria.php');
    ?>
    <div class="container">
        <div class="row">
            <div class="list-group col-3">
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php 
                    if (!isset($_GET['delete'])){
                        echo "active";
                    }
                ?>
                " href="utente.php">
                    Inserisci un utente
                </a>
                
                <a class="list-group-item list-group-item-action 
                <?php 
                    if (isset($_GET['delete']) && $_GET['delete']=='True'){
                        echo "active";
                    }
                ?>
                " href="utente.php?delete=True">
                    Cancella studente: rinuncia agli studi
                </a>
            </div>
        
            <?php 
            if (isset($_GET['delete']) && $_GET['delete']=='True'){
                include_once('template/form_deletestudente.php'); 
            }else{
                include_once('template/form_newutente.php');
            }
            ?>
        </div>
    </div>
    <?php
    }else{
        session_start();
        switch($_SESSION['ruolo']){
            case 'Segreteria': include_once('template/navbar_segreteria.php'); break;
            case 'Docente': include_once('template/navbar_docente.php'); break;
            case 'Studente': include_once('template/navbar_studente.php'); break;
        };
    ?>
    <div class="container">
        <div class="row justify-content-center">
        <?php include('template/form_cambiacredenziali.php'); ?>
        </div>
    </div>
    <?php
    }
    ?>
  </body>
</html>

