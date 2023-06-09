<?php
    function printlogin($err){
?>
    <div class="container pt-5" style="width:50%;">
        <h1 class="text-center"><i>Benvenuto, effettua l'accesso</i></h1>
        <form class="justify-content-center" action=<?php echo $_SERVER['PHP_SELF'];?> method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control w-10" id="email" name="email" autocomplete=off aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control w-10" id="password" name="password" style="">
            </div>
            <?php 
                if (!is_null($err)){echo $err;}
            ?>
            <button type="submit" class="btn btn-primary" >Login</button>
        </form>
    </div>
<?php
    }
?>