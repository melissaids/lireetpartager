<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: /lire-et-partager/connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Lire et Partager</title>
    <link rel="stylesheet" href="/lire-et-partager/assets/style.css">
</head>
<body>
<nav>
    <a href="/lire-et-partager/index.php" class="logo">Lire et Partager</a>
    <ul>
        <li><a href="index.php">Tableau de bord</a></li>
        <li><a href="livres.php">Livres</a></li>
        <li><a href="categories.php">Catégories</a></li>
        <li><a href="avis.php">Avis</a></li>
        <li><a href="suggestions.php">Suggestions</a></li>
        <li><a href="utilisateurs.php">Utilisateurs</a></li>
        <li><a href="/lire-et-partager/deconnexion.php">Déconnexion</a></li>
    </ul>
</nav>
