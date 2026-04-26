<?php
session_start();
$page_title = "Mentions Légales";
include 'models/header.php';
?>

<main class="content-wrapper">
    <section class="reviews">
        <h1>Mentions Légales</h1>
        <div style="line-height: 1.8;">
            <h3>Éditeur du site</h3>
            <p>Le site <strong>Lire et Partager</strong> est un projet pédagogique réalisé par Melissa Inacio Dos Santos & Othmane Chetouani.</p>
            
            <h3>Hébergement</h3>
            <p>Hébergé localement sur serveur XAMPP/WAMP.</p>
            
            <h3>Données Personnelles</h3>
            <p>Les informations collectées (nom, email) sont uniquement utilisées pour la gestion de votre compte et ne sont jamais cédées à des tiers.</p>
            
            <a href="index.php" class="badge-categorie" style="display:inline-block; margin-top:20px;">Retour à l'accueil</a>
        </div>
    </section>
</main>

<?php include 'models/footer.php'; ?>