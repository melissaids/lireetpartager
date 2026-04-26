<?php
require_once 'header.php';
require_once '../config/db.php';

$msg = '';
$err = '';

if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $check = $pdo->prepare("SELECT COUNT(*) FROM livres WHERE id_categorie = ?");
    $check->execute([$id]);
    if ($check->fetchColumn() > 0) {
        $err = "Impossible : des livres utilisent cette catégorie.";
    } else {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
        $msg = "Catégorie supprimée.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'ajouter') {
    $nom = trim($_POST['nom']);
    if ($nom == '') {
        $err = "Le nom est obligatoire.";
    } else {
        $pdo->prepare("INSERT INTO categories (nom) VALUES (?)")->execute([$nom]);
        $msg = "Catégorie ajoutée.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'modifier') {
    $id  = intval($_POST['id']);
    $nom = trim($_POST['nom']);
    if ($nom == '') {
        $err = "Le nom est obligatoire.";
    } else {
        $pdo->prepare("UPDATE categories SET nom = ? WHERE id = ?")->execute([$nom, $id]);
        $msg = "Catégorie modifiée.";
    }
}

$categories = $pdo->query("
    SELECT categories.*, COUNT(livres.id) AS nb
    FROM categories
    LEFT JOIN livres ON livres.id_categorie = categories.id
    GROUP BY categories.id
    ORDER BY categories.nom
")->fetchAll();

$modifier = null;
if (isset($_GET['modifier'])) {
    $s = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $s->execute([intval($_GET['modifier'])]);
    $modifier = $s->fetch();
}
?>

<div class="container">
    <h1>Catégories</h1>
    <a href="index.php">← Retour</a>

    <?php if ($msg): ?><div class="succes" style="margin-top:15px;"><?= $msg ?></div><?php endif; ?>
    <?php if ($err): ?><div class="erreur" style="margin-top:15px;"><?= $err ?></div><?php endif; ?>

    <h2 class="admin-page-categorieh2"><?= $modifier ? 'Modifier' : 'Ajouter une catégorie' ?></h2>
    <form method="POST" class="admin-page-categorieh2">
        <input type="hidden" name="action" value="<?= $modifier ? 'modifier' : 'ajouter' ?>">
        <?php if ($modifier): ?>
            <input type="hidden" name="id" value="<?= $modifier['id'] ?>">
        <?php endif; ?>
        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($modifier['nom'] ?? '') ?>">
        <div>
            <button type="submit" class="btn btn-bleu"><?= $modifier ? 'Enregistrer' : 'Ajouter' ?></button>
            <?php if ($modifier): ?>
                <a href="categories.php" class="btn">Annuler</a>
            <?php endif; ?>
        </div>
    </form>

    <h2>Liste</h2>
    <table>
        <tr>
            <th>Nom</th><th>Nb livres</th><th>Actions</th>
        </tr>
        <?php foreach ($categories as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['nom']) ?></td>
            <td><?= $c['nb'] ?></td>
            <td>
                <a href="categories.php?modifier=<?= $c['id'] ?>" class="btn btn-vert">Modifier</a>
                <a href="categories.php?supprimer=<?= $c['id'] ?>" class="btn btn-rouge" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once 'footer.php'; ?>
