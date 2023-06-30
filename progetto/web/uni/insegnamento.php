<!doctype html>
<html lang="en">
  <head>
    <title>University Exam System: insegnamento</title>
    <?php include_once('template/head.html'); ?>
  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <?php
        session_start();
        include_once('template/navbar_segreteria.php');
    ?>
    <div class="container">
        <div class="row">
            <div class="list-group col-3">
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php 
                    if (!isset($_GET['prope']) && !isset($_GET['responsabile']) && !isset($_GET['allInsegnamento'])){
                        echo "active";
                    }
                ?>
                " href="insegnamento.php">
                    Inserisci un nuovo insegnamento
                </a>

                <a class="list-group-item list-group-item-action 
                <?php 
                    if (isset($_GET['prope']) && $_GET['prope']=='True'){
                        echo "active";
                    }
                ?>
                " href="insegnamento.php?prope=True">
                    Inserisci propedeuticità
                </a>
                
                <a class="list-group-item list-group-item-action 
                <?php 
                    if (isset($_GET['responsabile']) && $_GET['responsabile']=='True'){
                        echo "active";
                    }
                ?>
                " href="insegnamento.php?responsabile=True">
                    Cambia responsabile
                </a>
                <a aria-current="true" class="list-group-item list-group-item-action 
                <?php 
                    if (isset($_GET['allInsegnamento'])){
                        echo "active";
                    }
                ?>
                " href="insegnamento.php?allInsegnamento=True">
                    Visualizza tutti gli insegnamenti
                </a>
            </div>
            <?php 
                if (isset($_GET['prope']) && $_GET['prope']=='True'){
                    include_once('template/form_newprope.php');
                }else if(isset($_GET['responsabile']) && $_GET['responsabile']=='True'){
                    include_once('template/form_responsabile.php');
                }else if(isset($_GET['allInsegnamento'])){
                    include_once('template/form_allInsegnamento.php');
                }else{
                    include_once('template/form_newinsegnamento.php');
                }
                ?>
        </div>
    </div>
  </body>
</html>

