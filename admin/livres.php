<?php
require_once 'header.php';
// Initialise ou récupère la session utilisateur
require_once '../config/db.php';
// Stockage des messages d'erreur ou de reussite
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
<main class="content-wrapper">
    <div class="container admin-container">
        <header class="admin-page-header">
            <h1>Gestion des livres</h1>
            <a href="index.php" class="btn-back">← Retour au tableau de bord</a>
        </header>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?= $msg ?></div>
        <?php endif; ?>
        <?php if ($err): ?>
            <div class="alert alert-danger"><?= $err ?></div>
        <?php endif; ?>

        <section class="admin-form-section">
            <h2 class="section-title"><?= $modifier ? 'Modifier le livre' : 'Ajouter un nouveau livre' ?></h2>
            
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <input type="hidden" name="action" value="<?= $modifier ? 'modifier' : 'ajouter' ?>">
                <?php if ($modifier): ?>
                    <input type="hidden" name="id" value="<?= $modifier['id'] ?>">
                    <input type="hidden" name="ancienne_couverture" value="<?= htmlspecialchars($modifier['couverture'] ?? '') ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="titre" value="<?= htmlspecialchars($modifier['titre'] ?? '') ?>" placeholder="Ex: Le Petit Prince">
                </div>

                <div class="form-group">
                    <label>Auteur</label>
                    <input type="text" name="auteur" value="<?= htmlspecialchars($modifier['auteur'] ?? '') ?>" placeholder="Ex: Antoine de Saint-Exupéry">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"><?= htmlspecialchars($modifier['description'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Prix (€)</label>
                        <input type="number" step="0.01" name="prix" value="<?= $modifier['prix'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Catégorie</label>
                        <select name="id_categorie">
                            <option value="">-- Aucune --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($modifier['id_categorie'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Image de couverture</label>
                    <?php if (!empty($modifier['couverture'])): ?>
                        <div class="current-image">
                            <img src="../assets/photo/<?= htmlspecialchars($modifier['couverture']) ?>" alt="Couverture actuelle">
                            <span>Image actuelle</span>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="couverture" accept="image/*" class="input-file">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-save"><?= $modifier ? 'Enregistrer les modifications' : 'Ajouter au catalogue' ?></button>
                    <?php if ($modifier): ?>
                        <a href="livres.php" class="btn btn-cancel">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="admin-table-section">
            <h2 class="section-title">Catalogue (<?= count($livres) ?> livres)</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Couverture</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livres as $l): ?>
                        <tr>
                            <td class="col-img">
                                <?php if ($l['couverture']): ?>
                                    <img src="../assets/photo/<?= htmlspecialchars($l['couverture']) ?>" class="table-img">
                                <?php else: ?>
                                    <span class="no-img">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="bold"><?= htmlspecialchars($l['titre']) ?></td>
                            <td><?= htmlspecialchars($l['auteur']) ?></td>
                            <td><span class="badge"><?= htmlspecialchars($l['cat'] ?? 'Sans catégorie') ?></span></td>
                            <td class="price"><?= number_format($l['prix'], 2) ?> €</td>
                            <td class="actions">
                                <a href="livres.php?modifier=<?= $l['id'] ?>" class="action-link edit">Modifier</a>
                                <a href="livres.php?supprimer=<?= $l['id'] ?>" class="action-link delete" onclick="return confirm('Supprimer ce livre ?')">Supprimer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<?php require_once 'footer.php'; ?>
