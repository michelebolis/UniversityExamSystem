<!doctype html>
<html lang="en">
  <head>
    <title>University Exam System: corso di laurea</title>
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
                    if (!isset($_GET['manifesto'])){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php">
                    Inserisci un nuovo corso di laurea
                </a>
                <a class="list-group-item list-group-item-action 
                <?php 
                    if (isset($_GET['manifesto']) && $_GET['manifesto']=='True'){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php?manifesto=True">
                    Inserisci insegnamenti nel manifesto
                </a>
            </div>
            <?php 
            if (!isset($_GET['manifesto'])){
                include_once('template/form_newcorso.php');
            }else if($_GET['manifesto']=='True'){
                include_once('template/form_insert_in_manifesto.php');
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
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php 
                    if (!isset($_GET['manifesto'])){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php">
                    Visualizza gli insegnamenti di un corso di laurea
                </a>
            </div>
            <?php 
            include_once('template/form_viewmanifesto.php');
            ?>
        </div>
    </div>
    <?php
        }
    ?>
  </body>
</html>

