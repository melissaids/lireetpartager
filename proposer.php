<?php
// Initialise ou récupère la session utilisateur
session_start();
require_once 'config/db.php';
// Change le nom de la page 
$page_title = "Suggérer un livre";
include 'models/header.php';
// Stockage des messages d'erreur ou de reussite
$msg = "";
// Vérification si le formulaire a été soumis en POST & l'utilisateur est bien connecté
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    // Récuperation & Suppression des données envoyées 
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $comm = trim($_POST['commentaire']);
    // Vérification si l'un des champs ce soit pas vide 
    if (!empty($titre) && !empty($auteur)) {
        $ins = $pdo->prepare("INSERT INTO suggestions (id_utilisateur, titre, auteur, commentaire) VALUES (?, ?, ?, ?)");
        $ins->execute([$_SESSION['user_id'], $titre, $auteur, $comm]);
        $msg = "Merci ! Votre suggestion a été transmise.";
    }
}
?>

<main class="content-wrapper">
    <div class="login-card">
        <div class="form-container">
            <h1>Une idée de livre ?</h1>
            <p>Dites-nous ce que vous aimeriez trouver dans notre catalogue.</p>

            <?php if ($msg): ?>
                <div class="badge-categorie"><?= $msg ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST">
                    <label>Titre du livre</label>
                    <input type="text" name="titre" required placeholder="Le titre...">
                    
                    <label>Auteur</label>
                    <input type="text" name="auteur" required placeholder="L'auteur...">
                    
                    <label>Petit mot (optionnel)</label>
                    <textarea name="commentaire"></textarea>
                    
                    <div class="actions">
                        <button type="submit" class="btn-nav">Envoyer ma suggestion</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'models/footer.php'; ?>
