<?php
require_once 'header.php';
require_once '../config/db.php';

$msg = '';
$err = '';

if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $row = $pdo->prepare("SELECT couverture FROM livres WHERE id = ?");
    $row->execute([$id]);
    $r = $row->fetch();
    if ($r['couverture'] && file_exists('../uploads/' . $r['couverture'])) {
        unlink('../uploads/' . $r['couverture']);
    }
    $pdo->prepare("DELETE FROM livres WHERE id = ?")->execute([$id]);
    $msg = "Livre supprimé.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'ajouter') {
    $titre  = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $desc   = trim($_POST['description']);
    $prix   = floatval($_POST['prix']);
    $cat    = intval($_POST['id_categorie']);
    $couverture = null;

    if ($titre == '' || $auteur == '') {
        $err = "Titre et auteur obligatoires.";
    } else {
        if (!empty($_FILES['couverture']['name'])) {
            $ext = strtolower(pathinfo($_FILES['couverture']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $couverture = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['couverture']['tmp_name'], '../uploads/' . $couverture);
            }
        }
        $ins = $pdo->prepare("INSERT INTO livres (titre, auteur, description, prix, id_categorie, couverture) VALUES (?, ?, ?, ?, ?, ?)");
        $ins->execute([$titre, $auteur, $desc, $prix, $cat ?: null, $couverture]);
        $msg = "Livre ajouté.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'modifier') {
    $id     = intval($_POST['id']);
    $titre  = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $desc   = trim($_POST['description']);
    $prix   = floatval($_POST['prix']);
    $cat    = intval($_POST['id_categorie']);
    $ancienne = $_POST['ancienne_couverture'] ?? '';
    $couverture = $ancienne;

    if (!empty($_FILES['couverture']['name'])) {
        $ext = strtolower(pathinfo($_FILES['couverture']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            if ($ancienne && file_exists('../uploads/' . $ancienne)) unlink('../uploads/' . $ancienne);
            $couverture = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['couverture']['tmp_name'], '../uploads/' . $couverture);
        }
    }

    $upd = $pdo->prepare("UPDATE livres SET titre=?, auteur=?, description=?, prix=?, id_categorie=?, couverture=? WHERE id=?");
    $upd->execute([$titre, $auteur, $desc, $prix, $cat ?: null, $couverture, $id]);
    $msg = "Livre modifié.";
}

$livres = $pdo->query("SELECT livres.*, categories.nom AS cat FROM livres LEFT JOIN categories ON livres.id_categorie = categories.id ORDER BY livres.titre")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$modifier = null;
if (isset($_GET['modifier'])) {
    $s = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
    $s->execute([intval($_GET['modifier'])]);
    $modifier = $s->fetch();
}
?>

<div class="container">
    <h1>Gestion des livres</h1>
    <a href="index.php">← Retour</a>

    <?php if ($msg): ?><div class="succes" style="margin-top:15px;"><?= $msg ?></div><?php endif; ?>
    <?php if ($err): ?><div class="erreur" style="margin-top:15px;"><?= $err ?></div><?php endif; ?>

    <h2 style="margin-top:20px;"><?= $modifier ? 'Modifier' : 'Ajouter un livre' ?></h2>

    <form method="POST" enctype="multipart/form-data" style="max-width:600px;">
        <input type="hidden" name="action" value="<?= $modifier ? 'modifier' : 'ajouter' ?>">
        <?php if ($modifier): ?>
            <input type="hidden" name="id" value="<?= $modifier['id'] ?>">
            <input type="hidden" name="ancienne_couverture" value="<?= htmlspecialchars($modifier['couverture'] ?? '') ?>">
        <?php endif; ?>

        <label>Titre</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($modifier['titre'] ?? '') ?>">
        <label>Auteur</label>
        <input type="text" name="auteur" value="<?= htmlspecialchars($modifier['auteur'] ?? '') ?>">
        <label>Description</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($modifier['description'] ?? '') ?></textarea>
        <label>Prix</label>
        <input type="number" step="0.01" name="prix" value="<?= $modifier['prix'] ?? '' ?>">
        <label>Catégorie</label>
        <select name="id_categorie">
            <option value="">-- Aucune --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($modifier['id_categorie'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Image <?= $modifier ? '(laisser vide pour garder l\'actuelle)' : '' ?></label>
        <?php if (!empty($modifier['couverture'])): ?>
            <img src="../uploads/<?= htmlspecialchars($modifier['couverture']) ?>" style="height:60px; margin-bottom:8px; display:block;">
        <?php endif; ?>
        <input type="file" name="couverture" accept="image/*">

        <div style="display:flex; gap:10px; margin-top:10px;">
            <button type="submit" class="btn btn-bleu"><?= $modifier ? 'Enregistrer' : 'Ajouter' ?></button>
            <?php if ($modifier): ?><a href="livres.php" class="btn" style="background:#eee;color:#333;">Annuler</a><?php endif; ?>
        </div>
    </form>

    <h2 style="margin-top:30px;">Liste (<?= count($livres) ?> livres)</h2>
    <table>
        <tr>
            <th>Image</th><th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Prix</th><th>Actions</th>
        </tr>
        <?php foreach ($livres as $l): ?>
        <tr>
            <td>
                <?php if ($l['couverture']): ?>
                    <img src="../uploads/<?= htmlspecialchars($l['couverture']) ?>" style="height:40px; border-radius:3px;">
                <?php else: ?>
                    <div style="width:28px;height:40px;background:#eee;border-radius:3px;"></div>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($l['titre']) ?></td>
            <td><?= htmlspecialchars($l['auteur']) ?></td>
            <td><?= htmlspecialchars($l['cat'] ?? '-') ?></td>
            <td><?= $l['prix'] ?> €</td>
            <td style="display:flex; gap:6px;">
                <a href="livres.php?modifier=<?= $l['id'] ?>" class="btn btn-vert" style="font-size:12px;padding:5px 10px;">Modifier</a>
                <a href="livres.php?supprimer=<?= $l['id'] ?>" class="btn btn-rouge" style="font-size:12px;padding:5px 10px;" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once 'footer.php'; ?>
