<!doctype html>
<html lang="en">
  <head>
    <title>University Exam System: carriera</title>
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
                if (!isset($_GET['carriera']) && !isset($_GET['allCarriera'])){
                    echo "active";
                }
            ?>
            " href="carriera.php">
                Visualizza la carriera completa di uno studente
            </a>

            <a aria-current="true" class="list-group-item list-group-item-action 
            <?php 
                if (isset($_GET['carriera']) && $_GET['carriera']=='True'){
                    echo "active";
                }
            ?>
            " href="carriera.php?carriera=True">
                Visualizza la carriera di uno studente
            </a>

            <a class="list-group-item list-group-item-action 
            <?php 
                if (isset($_GET['allCarriera']) && $_GET['allCarriera']=='True'){
                    echo "active";
                }
            ?>
            " href="carriera.php?allCarriera=True">
                Visualizza la carriera degli studenti di un corso di laurea
            </a>
        </div>
        
        <?php 
        if (isset($_GET['carriera']) && $_GET['carriera']=='True'){
            include_once('template/form_viewcarriera.php'); 
        }else if (isset($_GET['allCarriera']) && $_GET['allCarriera']=='True'){
            include_once('template/form_viewcarriera_bycorso.php'); 
        }else{
            include_once('template/form_viewcarriera_completa.php');
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
                if (!isset($_GET['carriera']) && !isset($_GET['past']) && !isset($exstudente)){
                    echo "active";
                }
            ?>
             <?php if (isset($exstudente)){echo 'disabled';} ?> " href="carriera.php">
                Visualizza la carriera completa
            </a>
            <a aria-current="true" class="list-group-item list-group-item-action 
            <?php 
                if (isset($_GET['carriera']) && $_GET['carriera']=='True'){
                    echo "active";
                }
            ?>
             <?php if (isset($exstudente)){echo 'disabled';} ?> " href="carriera.php?carriera=True">
                Visualizza la carriera
            </a>
            <a class="list-group-item list-group-item-action 
            <?php 
                if ((isset($_GET['past']) && $_GET['past']=='True') || isset($exstudente)){
                    echo "active";
                }
            ?>
            " href="carriera.php?past=True">
                Visualizza la carriera di un corso di laurea passato
            </a>
        </div>
        
        <?php 
        if (isset($_GET['carriera']) && $_GET['carriera']=='True'){
            include_once('template/form_viewcarriera.php'); 
        }else if ((isset($_GET['past']) && $_GET['past']=='True') || isset($exstudente)){
            include_once('template/form_viewcarriera_past.php'); 
        }else{
            include_once('template/form_viewcarriera_completa.php');
        }
        ?>
        </div>
    </div>
    <?php
        }
    ?>
    
  </body>
</html>

