<?php
require_once 'header.php';
require_once '../config/db.php';

$msg = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] == 'valider') {
        $pdo->prepare("UPDATE avis SET statut='valide' WHERE id=?")->execute([$id]);
        $msg = "Avis validé.";
    }
    if ($_GET['action'] == 'refuser') {
        $pdo->prepare("UPDATE avis SET statut='refuse' WHERE id=?")->execute([$id]);
        $msg = "Avis refusé.";
    }
}

$avis = $pdo->query("
    SELECT avis.*, livres.titre AS titre_livre, utilisateurs.prenom
    FROM avis
    JOIN livres ON avis.id_livre = livres.id
    JOIN utilisateurs ON avis.id_utilisateur = utilisateurs.id
    ORDER BY avis.statut = 'en_attente' DESC, avis.date_avis DESC
")->fetchAll();
?>

<<div class="container">
    <h1>Modération des avis</h1>
    <a href="index.php" class="btn btn-bleu">← Retour</a>

    <?php if ($msg): ?><div class="alert alert-success" style="margin-top:15px;"><?= $msg ?></div><?php endif; ?>

    <?php foreach ($avis as $a): ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <strong><?= htmlspecialchars($a['prenom']) ?></strong> sur <em><?= htmlspecialchars($a['titre_livre']) ?></em>
                    <span class="etoiles"><?= str_repeat('★', (int)$a['note']) ?><?= str_repeat('☆', 5 - (int)$a['note']) ?></span>
                </div>
                <?php $couleur = $a['statut'] == 'valide' ? '#27ae60' : ($a['statut'] == 'refuse' ? '#e74c3c' : '#e67e22'); ?>
                <span class="badge" style="background:<?= $couleur ?>; color:white; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:bold;">
                    <?= strtoupper($a['statut']) ?>
                </span>
            </div>
            <p><?= htmlspecialchars($a['commentaire']) ?></p>
            <div style="display:flex; gap:10px; margin-top:15px;">
                <?php if ($a['statut'] != 'valide'): ?>
                    <a href="avis.php?action=valider&id=<?= $a['id'] ?>" class="btn btn-vert">Valider</a>
                <?php endif; ?>
                <?php if ($a['statut'] != 'refuse'): ?>
                    <a href="avis.php?action=refuser&id=<?= $a['id'] ?>" class="btn btn-rouge">Refuser</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'footer.php'; ?>
