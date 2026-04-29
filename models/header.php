<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Lire et Partager'; ?></title>
    <link rel="stylesheet" href="./views/style.css"> 
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="logo-container">
                <img src="./assets/photo/lplogobleu.png" alt="L&P Logo" class="logo-small">
                <span>Lire et Partager</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="apropos.php">À propos</a></li>
                <li><a href="catalogue.php">Catalogue</a></li>
                // Si l'utilisateur est connecté et est admin, afficher le lien vers le tableau de bord admin
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="./admin/index.php" class="nav-adminlink">Tableau de bord Admin</a></li>
                <?php endif; ?>
            </ul>
            
            <div class="nav-auth">
                <?php if (isset($_SESSION['user_id'])): ?>
                    // affichage du nom de  l'utilisateur connecté & affiche de lien de suggestion & de déconnexion
                    <span style="color: white; margin-right: 10px;">Bonjour, <?php echo htmlspecialchars($_SESSION['prenom'] ?? 'Utilisateur'); ?></span>
                    <a href="proposer.php" class="btn-outline">Suggestion</a>
                    <a href="controllers/deconnexion.php" class="btn-outline">Déconnexion</a>
                <?php else: ?>
                    // Si l'utilisateur n'est pas connecté, afficher les liens de connexion par défaut
                    <a href="connexion.php" class="btn-outline">Connexion / inscription</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>   
        <div class="hero-title">
            <h1><?php echo $page_title ?? 'Accueil'; ?></h1>
        </div>