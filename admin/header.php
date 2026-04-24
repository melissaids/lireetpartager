<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Correction ici : on vérifie directement $_SESSION['role']
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /lireetpartager/connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Lire et Partager</title>
    <link rel="stylesheet" href="../views/style.css">
</head>
<body>
    <header class="header-admin">
    <div class="logo-group">
                <img src="../assets/photo/lplogobleu.png" alt="Logo de Lire et Partager" class="logo-image">
                <span class="logo-text">Lire et Partager</span>
    </div>
<nav class="navigation-admin">
    <ul>
        <li><a href="index.php">Tableau de bord</a></li>
        <li><a href="livres.php">Livres</a></li>
        <li><a href="categories.php">Catégories</a></li>
        <li><a href="avis.php">Avis</a></li>
        <li><a href="suggestions.php">Suggestions</a></li>
        <li><a href="utilisateurs.php">Utilisateurs</a></li>
        <li><a href="../index.php">Accueil publique</a></li>
        <li><a href="../controllers/deconnexion.php">Déconnexion</a></li>
    </ul>
</nav>
    </header>