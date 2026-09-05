<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirect('artworks.php');
}

$stmt = $pdo->prepare("SELECT image FROM artworks WHERE id = ?");
$stmt->execute([$id]);
$artwork = $stmt->fetch();

if ($artwork) {
    $delete = $pdo->prepare("DELETE FROM artworks WHERE id = ?");
    $delete->execute([$id]);

    delete_image('artworks', $artwork['image']);
}

redirect('artworks.php');