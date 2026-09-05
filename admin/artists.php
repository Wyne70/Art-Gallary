<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Manage Artists';
$base = '../';

$artists = $pdo->query("
    SELECT
        artists.*,
        COUNT(artworks.id) AS artwork_count
    FROM artists
    LEFT JOIN artworks ON artworks.artist_id = artists.id
    GROUP BY artists.id
    ORDER BY artists.name ASC
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
                <h1>Artists <span>Directory.</span></h1>
            </div>
            <a href="add-artist.php" class="btn btn-primary">+ Add Artist</a>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Artworks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($artists)): ?>
                        <?php foreach ($artists as $artist): ?>
                            <tr>
                                <td>
                                    <img
                                        class="table-image"
                                        src="<?= !empty($artist['profile_image']) ? '../assets/images/artists/' . e($artist['profile_image']) : '../assets/images/placeholder.svg' ?>"
                                        alt=""
                                    >
                                </td>
                                <td><strong><?= e($artist['name']) ?></strong></td>
                                <td><?= (int)$artist['artwork_count'] ?></td>
                                <td class="actions">
                                    <a href="edit-artist.php?id=<?= (int)$artist['id'] ?>" class="action-edit">Edit</a>
                                    <a href="delete-artist.php?id=<?= (int)$artist['id'] ?>" class="action-delete" onclick="return confirm('Delete this artist and their artworks?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 28px; color: var(--text-subtle);">No artists found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>