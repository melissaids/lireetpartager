<?php
require_once 'header.php';
require_once '../config/db.php';

$nb_livres      = $pdo->query("SELECT COUNT(*) FROM livres")->fetchColumn();
$nb_avis        = $pdo->query("SELECT COUNT(*) FROM avis WHERE statut = 'en_attente'")->fetchColumn();
$nb_suggestions = $pdo->query("SELECT COUNT(*) FROM suggestions WHERE statut = 'en_attente'")->fetchColumn();
$nb_users       = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'client'")->fetchColumn();
?>

<div class="container">
    <h1>Tableau de bord</h1>

    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:15px; margin-bottom:30px;">
        <div style="background:white; padding:20px; border-radius:8px; border:1px solid #ddd; text-align:center;">
            <div style="font-size:36px; font-weight:bold; color:#2c6e9b;"><?= $nb_livres ?></div>
            <div style="color:#777; margin-top:5px;">Livres</div>
        </div>
        <div style="background:white; padding:20px; border-radius:8px; border:1px solid #ddd; text-align:center;">
            <div style="font-size:36px; font-weight:bold; color:#e67e22;"><?= $nb_avis ?></div>
            <div style="color:#777; margin-top:5px;">Avis en attente</div>
        </div>
        <div style="background:white; padding:20px; border-radius:8px; border:1px solid #ddd; text-align:center;">
            <div style="font-size:36px; font-weight:bold; color:#9b59b6;"><?= $nb_suggestions ?></div>
            <div style="color:#777; margin-top:5px;">Suggestions</div>
        </div>
        <div style="background:white; padding:20px; border-radius:8px; border:1px solid #ddd; text-align:center;">
            <div style="font-size:36px; font-weight:bold; color:#27ae60;"><?= $nb_users ?></div>
            <div style="color:#777; margin-top:5px;">Utilisateurs</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:15px;">
        <a href="livres.php" class="btn btn-bleu" style="text-align:center; padding:15px;">Gérer les livres</a>
        <a href="categories.php" class="btn btn-bleu" style="text-align:center; padding:15px;">Gérer les catégories</a>
        <a href="avis.php" class="btn btn-bleu" style="text-align:center; padding:15px;">Modérer les avis</a>
        <a href="suggestions.php" class="btn btn-bleu" style="text-align:center; padding:15px;">Voir les suggestions</a>
        <a href="utilisateurs.php" class="btn btn-bleu" style="text-align:center; padding:15px;">Gérer les utilisateurs</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
