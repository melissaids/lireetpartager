<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Lire et Partager'; ?></title>
<<<<<<< HEAD
    <link rel="stylesheet" href="views/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-group">
                <img src="assets/photo/lplogobleu.png" alt="Logo de Lire et Partager" class="logo-image">
                <span class="logo-text">Lire et Partager</span>
            </div>

            <nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="apropos.php">À propos</a></li>
        <li><a href="catalogue.php">Catalogue</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="admin/index.php">Administration</a></li>
            <?php endif; ?>

            <li class="user-info">
                <?php
                    $prenomAffichage = $_SESSION['prenom'] ?? 'Utilisateur'; 
                ?>
                <span>Bonjour, <strong><?php echo htmlspecialchars($prenomAffichage); ?></strong></span>
            </li>
            <li><a href="controllers/deconnexion.php" class="btn-nav secondary">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="connexion.php" class="btn-nav">Connexion / Inscription</a></li>
        <?php endif; ?>
    </ul>
</nav>
        </div>
=======
    <link rel="stylesheet" href="/views/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">L&P Lire et Partager</div>
            <ul>
                <li><a href="/index.php">Accueil</a></li>
                <li><a href="/a-propos.php">À propos</a></li>
                <li><a href="/catalogue.php">Catalogue</a></li>
                <li class="btn-connexion">Connexion / Inscription</li>
            </ul>
        </nav>
>>>>>>> 2dcb82e5f4bfb95db90fd2ea251b790bec4dba7d
    </header>