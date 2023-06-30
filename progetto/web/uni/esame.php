<!doctype html>
<html lang="en">
  <head>
    <title>University Exam System: esame</title>
    <?php include_once('template/head.html'); ?>
  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<?php
    session_start();
    if ($_SESSION['ruolo']=='Docente'){
        include_once('template/navbar_docente.php');
?>
    <div class="container ">
        <div class="row home-element">
            <div class="list-group col-3 ">
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php
                if(!isset($_GET['next']) && !isset($_GET['insegnamenti']) && !isset($_GET['past'])){
                    echo 'active';
                }
                ?>" href="esame.php">
                Inserisci una sessione d'esame
                </a>
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php
                if(isset($_GET['next'])){
                    echo 'active';
                }
                ?>" href="esame.php?next=True">
                Visualizza gli iscritti dei prossimi esami 
                </a>
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php
                if(isset($_GET['insegnamenti'])){
                    echo 'active';
                }
                ?>" href="esame.php?insegnamenti=True">
                Visualizza gli insegnamenti di cui è responsabile
                </a>
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php
                if(isset($_GET['past'])){
                    echo 'active';
                }
                ?>" href="esame.php?past=True">
                Visualizza gli insegnamenti passati di cui eri responsabile
                </a>
            </div>
            <?php 
                if(isset($_GET['next'])){
                    include_once('template/form_viewiscritti.php');
                }else if(isset($_GET['insegnamenti'])){
                    include_once('template/form_viewinsegnamenti.php');
                }else if(isset($_GET['past'])){
                    include_once('template/form_pastInsegnamento_bydoc.php');
                }else{
                    include_once('template/form_newexam.php');
                }
            ?>
        </div>
    </div>
    <?php
    }else if($_SESSION['ruolo']=='Studente'){
        include_once('template/navbar_studente.php');
    ?>
    <div class="container ">
        <div class="row home-element">
            <div class="list-group col-3 ">
                <a aria-current="true" class="list-group-item list-group-item-action 
                    <?php 
                    if (!isset($_GET['modify'])){
                        echo 'active';
                    }
                    ?>" href="esame.php">
                    Iscriviti ad un esame
                </a>
        
                <a class="list-group-item list-group-item-action            
                    <?php 
                    if (isset($_GET['modify']) && $_GET['modify']=='True'){
                        echo 'active';
                    }
                    ?>" href="esame.php?modify=True">Annulla iscrizione ad un esame
                </a>
            </div>
        <?php 
        if (!isset($_GET['modify'])){
            include_once('template/form_iscrizione_esame.php');
        }else if($_GET['modify']=='True'){
            include_once('template/form_annulla_iscrizione.php');
        }
        ?>
        </div>
    </div>
    <?php 
    } 
    ?>
  </body>
</html>

