<?php
// Initialise ou récupère la session utilisateur
session_start();
// Destruction de la session et redirection vers la page d'accueil
$_SESSION = array();
// Si la session utilise des cookies, on les supprime aussi
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: ../index.php");
exit();
?>