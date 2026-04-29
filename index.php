<?php
// Initialisation ou récuperation d la session d'utilisateur
session_start();
// Récuperation de la configuration de la base de donnée
require_once 'config/db.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Lire et Partager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="main-header">
        <nav class="navbar">
            <div class="logo-container">
                <img src="./assets/photo/lplogobleu.png" alt="L&P Logo" class="logo-small">
                <span>Lire et Partager</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Accueil</a></li>
                <li><a href="a-propos.php">À propos</a></li>
                <li><a href="catalogue.php">Catalogue</a></li>
            </ul>
            <div class="nav-auth">
                <a href="connexion.php" class="btn-outline">Connexion / inscription</a>
            </div>
        </nav>
        
        <div class="hero-title">
            <h1>Accueil</h1>
        </div>
    </header>

    <main class="content-wrapper">
        
        <section class="history-section">
            <h2>Notre histoire :</h2>
            <p>
                <strong>Lire et Partager</strong> est née d'une conviction simple : un livre n'est jamais aussi vivant que 
                lorsqu'il change de mains. Plus qu'une librairie, nous sommes une escale pour les 
                rêveurs et les curieux. Ici, nous sélectionnons des ouvrages qui font vibrer, avec une 
                seule mission : <strong>faites circuler l'imaginaire</strong>. Venez trouver l'histoire qui vous attend, et 
                préparez-vous à la transmettre.
            </p>
            <a href="a-propos.php" class="btn-blue-icon">En savoir plus <span class="arrow">→</span></a>
        </section>

        <section class="arrivals-section">
            <h2>Nos derniers arrivées :</h2>
            
            <div class="cards-container">
                
            </div>
        </section>

    </main>

    <footer class="main-footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="./assets/photo/lplogobleu.png" alt="L&P Logo Large">
                <p>Faites circuler l'imaginaire</p>
            </div>
            
            <div class="footer-nav">
                <h3>Plan du site :</h3>
                <div class="nav-lists">
                    <ul>
                        <li><a href="connexion.php">Connexion</a></li>
                        <li><a href="inscription.php">Inscription</a></li>
                        <li><a href="mentions.php">Mention légale</a></li>
                    </ul>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="a-propos.php">À propos</a></li>
                        <li><a href="catalogue.php">Catalogue</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-contact">
                <p><strong>Nous trouver :</strong><br>12, Rue des Enluminures<br>75005 Paris</p>
                <p><strong>Nous contacter :</strong><br>01 42 33 45 67<br>contact@lireetpartager.fr</p>
                <p><strong>Horaires :</strong><br>Lundi : 10h - 20h<br>Mardi - Samedi : 10h - 19h</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 - Lire et partager, librairie indépendante</p>
        </div>
    </footer>

</body>
</html>
