<?php
session_start();
require_once 'config/db.php'; 

// On récupère les 3 derniers livres ajoutés
$stmt = $pdo->query("SELECT id, titre, auteur, couverture FROM livres ORDER BY id DESC LIMIT 3");
$derniers_livres = $stmt->fetchAll();
$page_title = "Accueil";
include 'models/header.php';
?>

<main class="content-wrapper">
    <section class="history-section">
        <h2>Notre histoire :</h2>
        <p>Lire et Partager a vu le jour sur une étagère trop pleine...</p>
        <a href="apropos.php" class="btn-blue-icon">En savoir plus sur nous</a>
    </section>

    <h2 style="text-align: center; margin-top: 50px;">Derniers livres ajoutés</h2>
    <div class="cards-container">
        <?php foreach ($derniers_livres as $livre): ?>
            <div class="card">
                <div class="card-image">
                    <img src="assets/photo/<?php echo htmlspecialchars($livre['couverture']); ?>" alt="<?php echo htmlspecialchars($livre['titre']); ?>">
                </div>
                <div class="card-content">
                    <h3><?php echo htmlspecialchars($livre['titre']); ?></h3>
                    <p><?php echo htmlspecialchars($livre['auteur']); ?></p>
                    <div class="card-footer-item">
                        <a href="livre.php?id=<?php echo $livre['id']; ?>" class="btn-blue-icon">En savoir plus</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'models/footer.php'; ?>