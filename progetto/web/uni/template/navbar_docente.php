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
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php if(isset($exam) && $exam){echo 'active';}?>" aria-current="page" href="esame.php">Sessione d'esame</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="esito.php">Esiti esame</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="utente.php?change=True">Cambia credenziali</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?logout=True">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


