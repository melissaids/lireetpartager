<?php
// Initialise ou récupère la session utilisateur
session_start();
require_once 'config/db.php';
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Cet email est déjà utilisé.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
                
                if ($stmt->execute([$nom, $prenom, $email, $hashed_password])) {
                    header('Location: connexion.php');
                    exit();
                } else {
                    $error = "Une erreur est survenue lors de l'inscription.";
                }
            }
        } else {
            $error = "Les mots de passe ne correspondent pas.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
$page_title = "Inscription";
include 'models/header.php';
?>
    <main>
        <section class="login-card">
            <div class="logo-large">
                <img src="./assets/photo/lplogobleu.png" alt="L&P Logo">
            </div>

            <div class="form-container">
                <h2>Créez votre compte :</h2>
                
                <?php if ($error): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="POST" action="">
                    <label>Nom :</label>
                    <input type="text" name="nom" placeholder="Votre nom" required>
                    <label>Prénom :</label>
                    <input type="text" name="prenom" placeholder="Votre prénom" required>
                    <label>Adresse mail :</label>
                    <input type="email" name="email" placeholder="Votre adresse mail" required>
                    <label>Mot de passe :</label>
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <label>Confirmer le mot de passe :</label>
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                    <div class="actions">
                        <button type="submit" class="btn-primary">S'inscrire</button>
                        <a href="connexion.php" class="btn-secondary">Connexion</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
<?php
include 'models/footer.php';
?>