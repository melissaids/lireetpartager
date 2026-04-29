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

        if ($user && password_verify($password, $user['mot_de_pass'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Identifiants invalides.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
$page_title = "À propos de nous";
include 'models/header.php'; 
?>
<main class="content-wrapper">
        <section class="history-section">
            <h2>L’histoire d’un voyage immobile</h2>
            <p>
                Tout a commencé par une étagère trop pleine et une envie de partage. <br>
                La librairie Lire et Partager a vu le jour au cœur de notre quartier avec l'idée que la lecture ne doit pas être une activité solitaire, mais le début d'une conversation.
            </p>
            <h2>Notre Philosophie</h2>
            <p>
                Pour nous, un livre qui reste fermé dans une bibliothèque est un trésor endormi. <br>
                C'est pourquoi nous avons conçu cet espace comme un lieu de rencontre entre les auteurs, les lecteurs et leurs émotions. <br>
                Que vous soyez amateur de littérature classique, passionné de romans graphiques ou en quête de découvertes jeunesse, chaque livre choisi sur nos étagères est une invitation au voyage.
            </p>
            <h2>"Faites circuler l’imaginaire"</h2>
            <p>
                Ce slogan est notre boussole.<br> Il rappelle que l'imaginaire est une énergie qui s'enrichit en se transmettant. <br>
                À travers nos conseils personnalisés, nos rencontres avec les auteurs et nos clubs de lecture, nous encourageons chaque lecteur à devenir un passeur d'histoires.
            </p>
            <p>
                Chez <strong>Lire et Partager</strong>, <br>vous n'achetez pas seulement un objet en papier ; vous rejoignez une communauté qui croit au pouvoir des mots pour transformer le quotidien. 
                Poussez la porte, évadez-vous, et surtout... faites circuler l'imaginaire.  
            </p>

        </section>

    </main>
<?php
include 'models/footer.php'; 
?>