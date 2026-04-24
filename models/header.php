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
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin_dashboard.php" style="color: #d9534f; font-weight: bold;">Tableau de bord Admin</a></li>
                <?php endif; ?>
            </ul>
            
            <div class="nav-auth">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="color: white; margin-right: 10px;">Bonjour, <?php echo htmlspecialchars($_SESSION['prenom'] ?? 'Utilisateur'); ?></span>
                    <a href="controllers/deconnexion.php" class="btn-outline">Déconnexion</a>
                <?php else: ?>
                    <a href="connexion.php" class="btn-outline">Connexion / inscription</a>
                <?php endif; ?>
            </div>
        </nav>
        
        <div class="hero-title">
            <h1><?php echo $page_title ?? 'Accueil'; ?></h1>
        </div>
    </header>