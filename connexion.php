<?php
// Initialise ou récupère la session utilisateur
session_start();
require_once 'config/db.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
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
$page_title = "Connexion";
include 'models/header.php';
?>
    <main>
        <section class="login-card">
            <div class="logo-large">
                <img src="./assets/photo/lplogobleu.png" alt="L&P Logo">
            </div>

            <div class="form-container">
                <h2>Connectez-vous à votre espace :</h2>
                
                <?php if ($error): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>

            <form method="POST" action="">
                <label>Adresse mail :</label>
                <input type="email" name="email" placeholder="votreadressemail@gmail.com" required>

                <label>Mot de passe :</label>
                <input type="password" name="password" placeholder="Mot de passe" required>

                <div class="actions">
                    <button type="submit" class="btn-primary">Connexion</button>
                    <a href="inscription.php" class="btn-secondary">Inscription</a>
                </div>
            </form>
        </div>
    </section>
</main>
<?php include 'models/footer.php'; ?>