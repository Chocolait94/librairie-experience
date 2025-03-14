<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['book_id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$book_id = $_GET['book_id'];

// Supprimer le livre des favoris
$stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND book_id = ?");
$stmt->execute([$user_id, $book_id]);

header("Location: dashboard.php");
exit();
?>