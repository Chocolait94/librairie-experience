<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) { // Vérifier si l'utilisateur est connecté
    header("Location: login.php");
    exit();
}

if (!isset($_GET['book_id'], $_GET['title'], $_GET['authors'], $_GET['thumbnail'])) { // Vérifier si les paramètres sont présents
    header("Location: dashboard.php");
    exit();
}
// variables pour stocker les données
$user_id = $_SESSION['user']['id'];
$book_id = $_GET['book_id'];
$title = $_GET['title'];
$authors = $_GET['authors'];
$thumbnail = $_GET['thumbnail'];

// Vérifier si le livre existe déjà en base
$stmt = $pdo->prepare("SELECT id FROM books WHERE id = ?");
$stmt->execute([$book_id]);
if (!$stmt->fetch()) {
    // Insérer le livre s'il n'existe pas
    $stmt = $pdo->prepare("INSERT INTO books (id, title, authors, thumbnail) VALUES (?, ?, ?, ?)");
    $stmt->execute([$book_id, $title, $authors, $thumbnail]);
}

// Vérifier si le livre est déjà dans les favoris de l'utilisateur
$stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND book_id = ?");
$stmt->execute([$user_id, $book_id]);
if (!$stmt->fetch()) {
    // Ajouter aux favoris
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, book_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $book_id]);
}
// localisation de la page dashboard.php
header("Location: dashboard.php");
exit();
