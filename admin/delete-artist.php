<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirect('artists.php');
}

$stmt = $pdo->prepare("SELECT profile_image FROM artists WHERE id = ?");
$stmt->execute([$id]);
$artist = $stmt->fetch();

if ($artist) {
    // Delete associated artwork files from the disk
    $artworksStmt = $pdo->prepare("SELECT image FROM artworks WHERE artist_id = ?");
    $artworksStmt->execute([$id]);
    $artworks = $artworksStmt->fetchAll();

    foreach ($artworks as $art) {
        delete_image('artworks', $art['image']);
    }

    // Delete artist record (artworks cascade via foreign key)
    $delete = $pdo->prepare("DELETE FROM artists WHERE id = ?");
    $delete->execute([$id]);

    delete_image('artists', $artist['profile_image']);
}

redirect('artists.php');