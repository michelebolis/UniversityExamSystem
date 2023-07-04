<?php
    include_once('lib/insert/insert_corso_laurea.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
            <?php if (!is_null($err)){echo $err;}?>
            <div class="mb-3">
                <label for="IDCorso" class="form-label">IDCorso</label>
                <input type="text" id="IDCorso" class="form-control" name="IDCorso"
                <?php if(isset($_POST['IDCorso'])){echo 'value="' . $_POST['IDCorso'] . '"';}?> >
            </div>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" class="form-control" name="nome"
                <?php if(isset($_POST['nome'])){echo 'value="' . $_POST['nome'] . '"';}?> >
            </div>
            <div class="mb-3">
                <label for="anni2" class="form-label">Anni totali</label>
                <div class="btn-group-vertical" role="group" aria-label="Vertical radio toggle button group">
                    <input type="radio" class="btn-check " name="anni" id="anni2" autocomplete="off" 
                    <?php if(isset($_POST['anni']) && $_POST['anni']=="2"){echo 'checked ';}?> value=2>
                    <label class="btn btn-outline" for="anni2">2</label>
                    <input type="radio" class="btn-check" name="anni" id="anni3" autocomplete="off" checked value=3>
                    <label class="btn btn-outline" for="anni3">3</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="lode" class="form-label">Valore lode</label>
                <input type="text" id="lode" class="form-control" name="lode"
                <?php if(isset($_POST['lode'])){echo 'value=' . $_POST['lode'];}?> >
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi</button>
        </form>
<?php
    } /*Fine function di print */

    if (isset($_POST['IDCorso']) && isset($_POST['nome']) && isset($_POST['anni']) && isset($_POST['lode'])){
        $err=insert_corso_laurea($_POST['IDCorso'], $_POST['nome'], $_POST['anni'], $_POST['lode']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Corso di laurea inserito correttamente</div>';
        }
    }else{
        printform(null);
    }
?>
