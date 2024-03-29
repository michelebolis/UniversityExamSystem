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
                    if (!isset($_GET['manifesto']) && !isset($_GET['view_manifesto']) && !isset($_GET['allCorso']) && !isset($_GET['attiva']) ){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php">
                    Inserisci un nuovo corso di laurea
                </a>
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php 
                    if (isset($_GET['attiva'])){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php?attiva=True">
                    Disattiva/attiva un corso di laurea
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
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php 
                    if (isset($_GET['view_manifesto'])){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php?view_manifesto=True">
                    Visualizza gli insegnamenti di un corso di laurea
                </a>
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php 
                    if (isset($_GET['allCorso'])){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php?allCorso=True">
                    Visualizza tutti i corsi di laurea
                </a>
                
            </div>
            <?php 
            if (!isset($_GET['manifesto']) && !isset($_GET['view_manifesto']) && !isset($_GET['allCorso'] ) && !isset($_GET['attiva'] )){
                include_once('template/form_newcorso.php');
            }else if(isset($_GET['manifesto'])){
                include_once('template/form_insert_in_manifesto.php');
            }else if(isset($_GET['view_manifesto'])){
                include_once('template/form_viewmanifesto.php');
            }else if(isset($_GET['allCorso'])){
                include_once('template/form_allCorso.php');
            }else if (isset($_GET['attiva'])){
                include_once('template/form_statocorso.php');
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
                    if (!isset($_GET['propedeuticita'])){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php">
                    Visualizza gli insegnamenti di un corso di laurea
                </a>
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php
                    if (isset($exstudente)){echo "disabled";} 
                    if (isset($_GET['propedeuticita'])){
                        echo "active";
                    }
                ?>
                " href="corso_laurea.php?propedeuticita=True">
                    Visualizza le propedeuticita del tuo corso di laurea
                </a>
            </div>
            <?php 
            if (isset($_GET['propedeuticita'])){
                echo '<div class="col-6 offset-1 home_element">';
                echo '<h4>Elenco propedeuticità del corso ' . $info['idcorso'] . '</h4>';
                include_once('lib/print_prope.php');
                print_prope($info['idcorso']);
                echo '</div>';
            }else{
                include_once('template/form_viewmanifesto.php');
            }
            ?>
        </div>
    </div>
    <?php
        }
    ?>
  </body>
</html>

