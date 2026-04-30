<?php
require_once 'header.php';
// Initialise ou récupère la session utilisateur
require_once '../config/db.php';
// Stockage des messages d'erreur ou de reussite
$msg = '';
// Traitement des actions d'acceptation ou de refus de suggestion
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] == 'accepter') {
        $pdo->prepare("UPDATE suggestions SET statut='accepte' WHERE id=?")->execute([$id]);
        $msg = "Suggestion acceptée.";
    }
    if ($_GET['action'] == 'refuser') {
        $pdo->prepare("UPDATE suggestions SET statut='refuse' WHERE id=?")->execute([$id]);
        $msg = "Suggestion refusée.";
    }
}
// Récupération de toutes les suggestions avec le prénom de l'utilisateur pour affichage
$suggestions = $pdo->query("
    SELECT suggestions.*, utilisateurs.prenom
    FROM suggestions
    JOIN utilisateurs ON suggestions.id_utilisateur = utilisateurs.id
    ORDER BY suggestions.statut = 'en_attente' DESC
")->fetchAll();
?>

<div class="container">
    <h1>Suggestions</h1>
    <a href="index.php">← Retour</a>

    <?php if ($msg): ?><div class="succes" style="margin-top:15px;"><?= $msg ?></div><?php endif; ?>

    <?php foreach ($suggestions as $s): ?>
        <div style="background:white; padding:15px; border-radius:8px; border:1px solid #ddd; margin-top:15px;">
            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                <div>
                    <strong><?= htmlspecialchars($s['titre']) ?></strong> par <?= htmlspecialchars($s['auteur']) ?>
                    — proposé par <em><?= htmlspecialchars($s['prenom']) ?></em>
                </div>
                <?php
                $couleur = $s['statut'] == 'accepte' ? '#27ae60' : ($s['statut'] == 'refuse' ? '#e74c3c' : '#e67e22');
                ?>
                <span style="background:<?= $couleur ?>; color:white; padding:3px 10px; border-radius:20px; font-size:12px;"><?= $s['statut'] ?></span>
            </div>
            <?php if ($s['commentaire']): ?>
                <p style="margin-top:8px; color:#555;"><?= htmlspecialchars($s['commentaire']) ?></p>
            <?php endif; ?>
            <div style="margin-top:10px; display:flex; gap:8px;">
                <?php if ($s['statut'] != 'accepte'): ?>
                    <a href="suggestions.php?action=accepter&id=<?= $s['id'] ?>" class="btn btn-vert" style="font-size:12px; padding:5px 12px;">Accepter</a>
                <?php endif; ?>
                <?php if ($s['statut'] != 'refuse'): ?>
                    <a href="suggestions.php?action=refuser&id=<?= $s['id'] ?>" class="btn btn-rouge" style="font-size:12px; padding:5px 12px;">Refuser</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'footer.php'; ?>
