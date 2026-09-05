<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Manage Artworks';
$base = '../';

$artworks = $pdo->query("
    SELECT
        artworks.*,
        artists.name AS artist_name
    FROM artworks
    LEFT JOIN artists ON artworks.artist_id = artists.id
    ORDER BY artworks.created_at DESC
")->fetchAll();

require_once '../includes/header.php';
?>

<div class="futuristic-background">
    <canvas id="particleCanvas"></canvas>
    <div class="background-glow glow-one"></div>
    <div class="background-glow glow-two"></div>
</div>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <div>
                <span class="eyebrow">MANAGEMENT</span>
                <h1>Artworks <span>Collection.</span></h1>
            </div>
            <a href="add-artwork.php" class="btn btn-primary">+ Add Artwork</a>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($artworks)): ?>
                        <?php foreach ($artworks as $art): ?>
                            <tr>
                                <td>
                                    <img
                                        class="table-image"
                                        src="<?= !empty($art['image']) ? '../assets/images/artworks/' . e($art['image']) : '../assets/images/placeholder.svg' ?>"
                                        alt=""
                                    >
                                </td>
                                <td><strong><?= e($art['title']) ?></strong></td>
                                <td><?= e($art['artist_name'] ?? 'Unknown') ?></td>
                                <td><?= e($art['category']) ?></td>
                                <td><?= format_price($art['price']) ?></td>
                                <td class="actions">
                                    <a href="edit-artwork.php?id=<?= (int)$art['id'] ?>" class="action-edit">Edit</a>
                                    <a href="delete-artwork.php?id=<?= (int)$art['id'] ?>" class="action-delete" onclick="return confirm('Delete this artwork permanently?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 28px; color: var(--text-subtle);">No artworks found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>