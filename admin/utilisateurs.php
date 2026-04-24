<?php
require_once 'header.php';
require_once '../config/db.php';

$users = $pdo->query("SELECT * FROM utilisateurs ORDER BY date_inscription DESC")->fetchAll();
?>

<div class="container">
    <h1>Gestion des utilisateurs</h1>
    <a href="index.php" class="btn btn-bleu" style="margin-bottom:20px;">← Retour</a>

    <table>
        <thead>
            <tr>
                <th>Nom & Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Inscription</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td class="bold"><?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <span class="badge" style="padding:4px 10px; border-radius:12px; font-size:11px; font-weight:bold; background: <?= $u['role'] == 'admin' ? '#d9534f' : '#5bc0de' ?>; color:white;">
                        <?= strtoupper($u['role']) ?>
                    </span>
                </td>
                <td style="color:#666; font-size:13px;"><?= date('d/m/Y', strtotime($u['date_inscription'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>