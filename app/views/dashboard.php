<?php
//securite
if (!isset($_SESSION['user'])) {
    header("Location: index.php?action=login");
    exit;
}
?>

<h1>Dashboard</h1>
<p>Bienvenue <?= $_SESSION['user']['name']; ?></p>

<a href="index.php?action=logout">Déconnexion</a>