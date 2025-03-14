<?php
session_start();
require 'config.php';
require 'utilisateur.php'; // Inclure la classe Utilisateur

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Vérifie si le formulaire a été soumis
    // Récupère les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new Utilisateur();
    $loggedUser = $user->connexion($email, $password);

    if ($loggedUser) {
        $_SESSION['user'] = $loggedUser; // Stocke les infos de l'utilisateur dans la session pour les utiliser plus tard
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Connexion</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                        <p class="mt-3 text-center">
                            Pas encore de compte ? <a href="register.php">Inscrivez-vous</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
