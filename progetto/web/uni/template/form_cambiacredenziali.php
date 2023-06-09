<?php
    include_once('lib/update/modifica_utente.php');
    include_once('lib/get/get_utente_bio.php');
    
    function printform($err){
?>
    <form class="col-7" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?change=True';?>>
    <?php if (!is_null($err)){echo $err;}
        $info = get_utente_bio($_SESSION['utente']);
    ?>
        <div class="mb-2">
            <label for="email" class="form-label">Email</label>
            <input type="text" id="email" class="form-control" name="email" autocomplete=off 
            <?php
                if ((isset($_POST['email']))){echo 'value="' . $_POST['email'] . '"';}
                else {echo 'value="' . $info['email'] . '"';}
            ?>
            >
        </div>
        <div class="mb-2">
            <label for="password" class="form-label">Nuova Password</label>
            <input type="text" id="password" class="form-control" name="password" autocomplete=off 
            <?php
                if ((isset($_POST['password']))){echo 'value="' . $_POST['password'] . '"';}
            ?>
            >
            <label class="form-label">(Lasciare nuova password vuota se non la si vuola modificare)</label>
        </div>
        <div class="mb-2">
            <label for="cellulare" class="form-label">Cellulare</label>
            <input type="text" id="cellulare" class="form-control" name="cellulare" autocomplete=off 
            <?php
                if ((isset($_POST['cellulare']))){echo 'value="' . $_POST['cellulare'] . '"';}
                else {echo 'value="' . $info['cellulare'] . '"';}
            ?>
            >
        </div>
        <button type="submit" class="btn btn-primary">Cambia</button>
    </form>
<?php
    }

    if (isset($_POST['email']) && isset($_POST['password'])&& isset($_POST['cellulare'])){
        $password = $_POST['password'];
        if ($password==''){
            $password=NULL;
        } 
        $err = cambia_credenziali($_SESSION['utente'], $_POST['email'], $password, $_POST['cellulare']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Credenziali modificate correttamente</div>';
        }
    }else{
        printform(null);
    }
?>