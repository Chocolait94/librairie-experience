<?php
// pour rechercher des livres sur Google Books
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['query']) || empty($_GET['query'])) {
    header("Location: dashboard.php");
    exit();
}

$query = urlencode($_GET['query']);
$url = "https://www.googleapis.com/books/v1/volumes?q={$query}&langRestrict=fr";
$response = file_get_contents($url);
$books = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Résultats pour "<?= htmlspecialchars($_GET['query']) ?>"</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Retour</a>

        <div class="row">
            <?php if (!empty($books['items'])) : ?>
                <?php foreach ($books['items'] as $book) : 
                    $book_id = $book['id'];
                    $title = $book['volumeInfo']['title'] ?? 'Titre inconnu';
                    $authors = isset($book['volumeInfo']['authors']) ? implode(", ", $book['volumeInfo']['authors']) : 'Auteur inconnu';
                    $thumbnail = $book['volumeInfo']['imageLinks']['thumbnail'] ?? 'placeholder.jpg';
                ?>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <img src="<?= $thumbnail ?>" class="card-img-top" alt="Couverture">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($title) ?></h5>
                                <p class="card-text"><small class="text-muted"><?= htmlspecialchars($authors) ?></small></p>
                                <a href="add_favorite.php?book_id=<?= $book_id ?>&title=<?= urlencode($title) ?>&authors=<?= urlencode($authors) ?>&thumbnail=<?= urlencode($thumbnail) ?>" 
                                   class="btn btn-sm btn-success">Ajouter aux favoris</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-muted">Aucun livre trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
