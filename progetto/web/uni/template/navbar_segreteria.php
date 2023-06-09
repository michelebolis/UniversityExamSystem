<nav class="navbar navbar-expand-lg ">
  <div class="container-fluid">
    <a class="navbar-brand active" href="index.php">
    <?php
        include('lib/get/get_utente_bio.php');
        $info = get_utente_bio($_SESSION['utente']);
        echo $info['nome'] . ' ' . $info['cognome'];
    ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'corso_laurea.php')){echo 'active';}?>" 
          href="corso_laurea.php">Gestione corsi di laurea</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'insegnamento.php')){echo 'active';}?>" 
          href="insegnamento.php">Gestione insegnamenti</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'utente.php')){echo 'active';}?>" 
          href="utente.php">Gestione utenti</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'laurea.php')){echo 'active';}?>" 
          href="laurea.php">Gestione laurea</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(str_contains($_SERVER['PHP_SELF'], 'carriera.php')){echo 'active';}?>" 
          href="carriera.php">Carriere</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(isset($_GET['change'])){echo 'active';}?>" 
          href="utente.php?change=True">Cambia credenziali</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?logout=True">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


