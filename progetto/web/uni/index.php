<!doctype html>
<html lang="en">
  <head>
    <title>University Exam System: home</title>
    <?php include_once('template/head.html'); ?>
  </head>
  <body >
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <?php 
        if ((isset($_GET['logout'])) && $_GET['logout']=='True'){
            include_once('lib/logout.php');  
            logout();  
            include_once('template/login.php');
            printlogin(null);
        }else{
          if (isset($_POST['email']) && isset($_POST['password'])){
            /* Prima volta che faccio il login */
            include_once('lib/login.php');  
            login();
            $trylogin=true;
          }else{
            /* Successive volte in cui arrivo nella pagina index */
            session_start();
          };
          if (isset($_SESSION['utente']) && isset($_SESSION['ruolo'])){
              switch($_SESSION['ruolo']){
                case ('Docente'): include_once('template/home_docente.php'); break;
                case ('Studente'): include_once('template/home_studente.php'); break;
                case ('Segreteria'): include_once('template/home_segreteria.php'); break;
                default: echo 'Interfaccia non implementata per il ruolo: '; echo $_SESSION['ruolo'];
              }
          }else{
            include('template/login.php');
            if (isset($trylogin) && $trylogin){
              printlogin('<span style="color:red;"> Email o password errati </span> <br /><br />');
            }else{
              printlogin(null);
            }
            
          };
        }  
    ?>
  </body>
</html>