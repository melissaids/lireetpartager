<?php
require_once 'header.php';
require_once '../config/db.php';

$users = $pdo->query("SELECT * FROM utilisateurs ORDER BY date_inscription DESC")->fetchAll();
?>

<div class="container">
    <h1>Gestion des utilisateurs</h1>
    <a href="index.php">← Retour</a>

    <table style="margin-top:20px;">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Inscription</th>
        </tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td>
                <span style="padding:3px 8px; border-radius:10px; font-size:12px; background: <?= $u['role'] == 'admin' ? '#d9534f' : '#5bc0de' ?>; color:white;">
                    <?= $u['role'] ?>
                </span>
            </td>
            <td><?= $u['date_inscription'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once 'footer.php'; ?>