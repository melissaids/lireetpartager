<?php
session_start();
require_once 'config/db.php';
$stmt_livres = $pdo->query("SELECT * FROM livres ORDER BY id DESC LIMIT 3");
$derniers_livres = $stmt_livres->fetchAll();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_pass'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Identifiants invalides.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

$page_title = "Accueil - Lire et Partager";
include 'models/header.php';
?>

<main class="content-wrapper">
    <section class="title-section">
        <h1> Accueil </h1>
    </section>
    <section class="history-section">
        <h2>Notre histoire :</h2>
        <p>
            <strong>Lire et Partager</strong> est née d'une conviction simple : un livre n'est jamais aussi vivant que 
            lorsqu'il change de mains.<br> Plus qu'une librairie, nous sommes une escale pour les 
            rêveurs et les curieux.<br> Ici, nous sélectionnons des ouvrages qui font vibrer, avec une 
            seule mission : <strong>faites circuler l'imaginaire</strong>. <br> Venez trouver l'histoire qui vous attend, et 
            préparez-vous à la transmettre.
        </p>
        <a href="apropos.php" class="btn-blue-icon">En savoir plus <span class="arrow">→</span></a>
    </section>

    <section class="arrivals-section">
        <h2>Nos derniers arrivées :</h2>
        
        <div class="cards-container">
    <?php foreach ($derniers_livres as $livre): ?>
        <div class="card">
            <div class="card-image">
                <?php if (!empty($livre['couverture'])): ?>
                    <img src="assets/photo/<?php echo htmlspecialchars($livre['couverture']); ?>" alt="Couverture">
                <?php else: ?>
                    <img src="assets/photo/lplogobleu.png" alt="Pas de couverture">
                <?php endif; ?>
            </div>
            <div class="card-content">
                <div>
                    <h3><?php echo htmlspecialchars($livre['titre'] ?? ''); ?></h3>
                    <p class="author"><?php echo htmlspecialchars($livre['auteur'] ?? ''); ?></p>
                    <p class="description">
                        <?php
                            $texte = $livre['description'] ?? '';
                            echo htmlspecialchars(substr($texte, 0, 120)) . '...'; 
                        ?>
                    </p>
                </div>
                <a href="livre.php?id=<?php echo $livre['id']; ?>" class="btn-blue-icon">
                    En savoir plus <span class="arrow">→</span>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
    </section>

</main>

<?php
include 'models/footer.php'; 
?>