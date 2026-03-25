<?php
require_once 'header.php';
require_once '../config/db.php';

$msg = '';

if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    if ($id == $_SESSION['user']['id']) {
        $msg = "Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        $pdo->prepare("DELETE FROM avis WHERE id_utilisateur = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM suggestions WHERE id_utilisateur = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?")->execute([$id]);
        $msg = "Utilisateur supprimé.";
    }
}

if (isset($_GET['toggle_role'])) {
    $id = intval($_GET['toggle_role']);
    $u  = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
    $u->execute([$id]);
    $role_actuel  = $u->fetchColumn();
    $nouveau_role = $role_actuel == 'admin' ? 'client' : 'admin';
    $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?")->execute([$nouveau_role, $id]);
    $msg = "Rôle mis à jour.";
}

$utilisateurs = $pdo->query("
    SELECT utilisateurs.*, COUNT(avis.id) AS nb_avis
    FROM utilisateurs
    LEFT JOIN avis ON avis.id_utilisateur = utilisateurs.id
    GROUP BY utilisateurs.id
    ORDER BY utilisateurs.id DESC
")->fetchAll();
?>

<div class="container">
    <h1>Utilisateurs</h1>
    <a href="index.php">← Retour</a>

    <?php if ($msg): ?><div class="succes" style="margin-top:15px;"><?= $msg ?></div><?php endif; ?>

    <table style="margin-top:20px;">
        <tr>
            <th>Prénom Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Avis</th>
            <th>Inscrit le</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($utilisateurs as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td>
                <?php $couleur = $u['role'] == 'admin' ? '#9b59b6' : '#2c6e9b'; ?>
                <span style="background:<?= $couleur ?>; color:white; padding:2px 8px; border-radius:20px; font-size:12px;"><?= $u['role'] ?></span>
            </td>
            <td><?= $u['nb_avis'] ?></td>
            <td style="font-size:13px; color:#777;">
                <?= $u['date_inscription'] ? date('d/m/Y', strtotime($u['date_inscription'])) : '-' ?>
            </td>
            <td style="display:flex; gap:6px;">
                <?php if ($u['id'] != $_SESSION['user']['id']): ?>
                    <a href="utilisateurs.php?toggle_role=<?= $u['id'] ?>" class="btn" style="background:#9b59b6; color:white; font-size:12px; padding:5px 10px;" onclick="return confirm('Changer le rôle ?')">
                        <?= $u['role'] == 'admin' ? 'Rétrograder' : 'Promouvoir' ?>
                    </a>
                    <a href="utilisateurs.php?supprimer=<?= $u['id'] ?>" class="btn btn-rouge" style="font-size:12px; padding:5px 10px;" onclick="return confirm('Supprimer ?')">Supprimer</a>
                <?php else: ?>
                    <span style="color:#aaa; font-size:13px;">Vous</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once 'footer.php'; ?>
