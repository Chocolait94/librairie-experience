<?php
session_start();
require 'config.php';
require 'utilisateur.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Récupérer les livres favoris de l'utilisateur
$stmt = $pdo->prepare("SELECT books.id, books.title, books.authors, books.thumbnail FROM favorites 
                        JOIN books ON favorites.book_id = books.id WHERE favorites.user_id = ?");
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Bienvenue, <?= htmlspecialchars($user['name']) ?> 👋</h2>
            <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
        </div>

        <h3 class="mt-4">📚 Vos livres favoris</h3>
        <div class="row">
            <?php if (empty($favorites)) : ?>
                <p class="text-muted">Vous n'avez pas encore ajouté de livres.</p>
            <?php else : ?>
                <?php foreach ($favorites as $book) : ?>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <img src="<?= $book['thumbnail'] ?>" class="card-img-top" alt="Couverture">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                                <p class="card-text"><small class="text-muted">Auteur(s) : <?= htmlspecialchars($book['authors']) ?></small></p>
                                <a href="remove_favorite.php?book_id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-danger">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <h3 class="mt-4">🔍 Rechercher des livres</h3>
        <form action="search_books.php" method="GET" class="d-flex">
            <input type="text" name="query" class="form-control me-2" placeholder="Rechercher un livre..." required>
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>
</body>
</html>
