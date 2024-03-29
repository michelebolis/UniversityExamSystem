<!doctype html>
<html lang="en">
  <head>
    <title>University Exam System: esito</title>
    <?php include_once('template/head.html'); ?>
  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<?php
    session_start();
    if ($_SESSION['ruolo']=='Docente'){
      include_once('template/navbar_docente.php');
?>
    <div class="container">
      <div class="row">
        <div class="list-group col-3">
            <a aria-current="true" class="list-group-item list-group-item-action 
            <?php if(!isset($_GET['getAll'])){echo "active";}?>
            " href="#">
                Inserisci un esito per un esame
            </a>
            <a aria-current="true" class="list-group-item list-group-item-action 
            <?php if(isset($_GET['getAll'])){echo "active";}?>
            " href="esito.php?getAll=True">
                Visualizza tutti gli esiti di un esame
            </a>
        </div>
        <?php 
        if (isset($_GET['getAll'])){
          include_once('template/form_allEsito.php');
        }else{
          include_once('template/form_newesito.php');
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
              <a aria-current="true" class="list-group-item list-group-item-action active" href="#">
                  Visualizza esito
              </a>
          </div>
          <?php 
            include_once('template/form_handleesito.php');
          ?>
        </div>
    </div>
<?php
    }
?>
  </body>
</html>

