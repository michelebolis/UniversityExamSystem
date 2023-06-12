<?php
    include_once('lib/insert/insert_insegnamento.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
            <?php if (!is_null($err)){echo $err;}?>
            <div class="mb-3">
                <label for="docente" class="form-label">Docente</label>
                <select name="docente" id="docente" class="form-select">
                    <?php
                    echo '<option value="'. null . '"' ;
                    if (isset($_POST['docente']) && $_POST['docente']=='null'){
                        echo ' selected';
                    }
                    echo '>';
                    echo 'Nessun responsabile';
                    echo '</option>';
                    include_once('lib/get/get_all_docente.php');
                    $res = get_all_docente();
                    while($docente = pg_fetch_assoc($res)){
                        echo '<option value='. $docente['iddocente'];
                        if (isset($_POST['docente']) && $_POST['docente']==$docente['iddocente']){
                            echo ' selected';
                        }
                        echo '>';
                        include_once('lib/get/get_utente_bio.php');
                        $info = get_utente_bio($docente['iddocente']);
                        echo $info['nome'] . ' ' . $info['cognome'];
                        echo '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" class="form-control" name="nome" autocomplete=off
                <?php
                    if (isset($_POST['nome'])){
                        echo 'value="' . $_POST['nome'] . '"';
                    } 
                ?>
                >
            </div>
            <div class="mb-3">
                <label for="descrizione" class="form-label">Descrizione</label>
                <input type="text" id="descrizione" class="form-control" name="descrizione" autocomplete=off
                <?php
                    if (isset($_POST['descrizione'])){
                        echo 'value="' . $_POST['descrizione'] . '"';
                    } 
                ?>
                >
            </div>
            <div class="mb-3">
                <label for="crediti" class="form-label">Crediti</label>
                <input type="text" id="crediti" class="form-control" name="crediti" autocomplete=off
                <?php
                    if (isset($_POST['crediti'])){
                        echo 'value=' . $_POST['crediti'] . '';
                    } 
                ?>
                >
            </div>
            <div class="mb-3">
                <label for="anno" class="form-label">Anno di attivazione</label>
                <input type="text" id="anno" class="form-control" name="anno" autocomplete=off
                <?php
                    if (isset($_POST['anno'])){
                        echo 'value=' . $_POST['anno'] ;
                    } 
                ?>
                >
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi</button>
        </form>
<?php
    }/*Fine funzione di print */

    if (isset($_POST['docente']) && isset($_POST['nome']) && isset($_POST['descrizione']) && isset($_POST['crediti']) && isset($_POST['anno'])){
        $descrizione=$_POST['descrizione']=="";
        if ($descrizione==""){
            $descrizione=null;
        }
        $doc = $_POST['docente'];
        if ($doc==''){
            $doc = null;
        }
        $err=insert_insegnamento($doc,$_POST['nome'],$descrizione,$_POST['crediti'], $_POST['anno']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Insegnamento inserito correttamente</div>';
        }
    }else{
        printform(null);
    }
?>
