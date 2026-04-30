<?php
// Initialise ou récupère la session utilisateur
session_start();
// Vérification que l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
// Récupération de la configuration de la base de donnée & du modele header administrateur
require_once 'header.php';
require_once '../config/db.php';
// Récupération des statistiques globales pour affichage sur le tableau de bord
$nb_livres       = $pdo->query("SELECT COUNT(*) FROM livres")->fetchColumn();
$nb_avis         = $pdo->query("SELECT COUNT(*) FROM avis WHERE statut = 'en_attente'")->fetchColumn();
$nb_suggestions  = $pdo->query("SELECT COUNT(*) FROM suggestions WHERE statut = 'en_attente'")->fetchColumn();
$nb_users        = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'client'")->fetchColumn();
?>

<main class="content-wrapper">
    <div class="container">
        <h1>Tableau de bord Administration</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] ?? 'Admin') ?>.</p>

        <div class="card-admin">
            <div class="card-admin-livres">
                <div><?= $nb_livres ?></div>
                <div>Livres</div>
            </div>
            <div class="card-admin-avis">
                <div><?= $nb_avis ?></div>
                <div>Avis en attente</div>
            </div>
            <div class="card-admin-suggestions">
                <div><?= $nb_suggestions ?></div>
                <div>Suggestions</div>
            </div>
            <div class="card-admin-users">
                <div><?= $nb_users ?></div>
                <div>Utilisateurs</div>
            </div>
        </div>

        <div class="admin-actions">
            <a href="livres.php" class="btn-nav">Gérer les livres</a>
            <a href="categories.php" class="btn-nav">Gérer les catégories</a>
            <a href="avis.php" class="btn-nav">Modérer les avis</a>
            <a href="suggestions.php" class="btn-nav">Voir les suggestions</a>
            <a href="utilisateurs.php" class="btn-nav">Gérer les utilisateurs</a>
            <a href="../index.php" class="btn-nav secondary">Retour au site</a>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>