<?php
session_start();
require_once 'config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: catalogue.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_avis']) && isset($_SESSION['user_id'])) {
    $note = intval($_POST['note']);
    $commentaire = trim($_POST['commentaire']);
    $id_user = $_SESSION['user_id'];

    if (!empty($commentaire) && $note >= 1 && $note <= 5) {
        $stmt = $pdo->prepare("INSERT INTO avis (id_utilisateur, id_livre, note, commentaire, statut) VALUES (?, ?, ?, ?, 'en_attente')");
        $stmt->execute([$id_user, $id, $note, $commentaire]);
        $message_avis = "Merci ! Votre avis est en attente de validation.";
    }
}

$sqlLivre = "SELECT l.*, c.nom as categorie_nom 
             FROM livres l 
             LEFT JOIN categories c ON l.id_categorie = c.id 
             WHERE l.id = ?";
$stmtLivre = $pdo->prepare($sqlLivre);
$stmtLivre->execute([$id]);
$livre = $stmtLivre->fetch();

if (!$livre) {
    die("Ce livre n'existe pas.");
}

$sqlAvis = "SELECT a.*, u.prenom FROM avis a 
            JOIN utilisateurs u ON a.id_utilisateur = u.id 
            WHERE a.id_livre = ? AND a.statut = 'accepte' 
            ORDER BY a.date_avis DESC";
$stmtAvis = $pdo->prepare($sqlAvis);
$stmtAvis->execute([$id]);
$avis_liste = $stmtAvis->fetchAll();

$page_title = $livre['titre'] . " - Lire et Partager";
include 'models/header.php';
?>

<main class="content-wrapper">
    <section class="book-details">
        <div class="book-image">
            <?php if (!empty($livre['couverture'])): ?>
                <img src="assets/photo/<?= htmlspecialchars($livre['couverture']) ?>" alt="Couverture">
            <?php else: ?>
                <img src="assets/photo/lplogobleu.png" alt="Image par défaut">
            <?php endif; ?>
        </div>

        <div class="book-info">
            <p><?= htmlspecialchars($livre['categorie_nom'] ?? 'Général') ?></p>
            <h1><?= htmlspecialchars($livre['titre']) ?></h1>
            <p>Par <strong><?= htmlspecialchars($livre['auteur']) ?></strong></p>
            <p><?= number_format($livre['prix'], 2, ',', ' ') ?> €</p>

            <p>
                Statut : 
                <?php if (isset($livre['stock']) && $livre['stock'] > 0): ?>
                    <span>En stock (<?= $livre['stock'] ?>)</span>
                <?php else: ?>
                    <span>Indisponible</span>
                <?php endif; ?>
            </p>
            
            <h3>Résumé</h3>
            <p><?= nl2br(htmlspecialchars($livre['description'])) ?></p>
        </div>
    </section>

    <hr>

    <section class="reviews">
        <h2>Avis des lecteurs (<?= count($avis_liste) ?>)</h2>

        <?php if (isset($message_avis)): ?>
            <p><?= $message_avis ?></p>
        <?php endif; ?>

        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <h3>Partagez votre avis</h3>
                <form method="POST" action="">
                    <label>Note :</label>
                    <select name="note" required>
                        <option value="5">5/5</option>
                        <option value="4">4/5</option>
                        <option value="3">3/5</option>
                        <option value="2">2/5</option>
                        <option value="1">1/5</option>
                    </select>

                    <label>Commentaire :</label>
                    <textarea name="commentaire" required></textarea>

                    <button type="submit" name="submit_avis">Publier mon avis</button>
                </form>
            <?php else: ?>
                <p>Vous devez être <a href="connexion.php">connecté</a> pour laisser un avis.</p>
            <?php endif; ?>
        </div>

        <div class="reviews-list">
            <?php if (count($avis_liste) > 0): ?>
                <?php foreach ($avis_liste as $avis): ?>
                    <div class="review-item">
                        <p><strong><?= htmlspecialchars($avis['prenom']) ?></strong> - Note : <?= $avis['note'] ?>/5</p>
                        <p>"<?= htmlspecialchars($avis['commentaire']) ?>"</p>
                        <small>Le <?= date('d/m/Y', strtotime($avis['date_avis'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun avis publié pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'models/footer.php'; ?>