<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Admin Dashboard';
$base = '../';

$totalArtworks = (int) $pdo->query("SELECT COUNT(*) FROM artworks")->fetchColumn();
$totalArtists  = (int) $pdo->query("SELECT COUNT(*) FROM artists")->fetchColumn();
$totalMessages = (int) $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

$recentArtworks = $pdo->query("
    SELECT artworks.*, artists.name AS artist_name
    FROM artworks
    LEFT JOIN artists ON artworks.artist_id = artists.id
    ORDER BY artworks.created_at DESC
    LIMIT 6
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
                <span class="eyebrow">CONTROL PANEL</span>
                <h1>Admin <span>Dashboard.</span></h1>
                <p class="admin-subtitle">Welcome back, <?= e($_SESSION['username'] ?? 'Administrator') ?>. Manage gallery content and inquiries below.</p>
            </div>
            <div class="admin-actions">
                <a href="add-artwork.php" class="btn btn-primary">+ Add Artwork</a>
                <a href="add-artist.php" class="btn btn-outline">+ Add Artist</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span>Total Artworks</span>
                <strong><?= $totalArtworks ?></strong>
                <a href="artworks.php" class="text-link">Manage artworks <span>→</span></a>
            </div>
            <div class="stat-card">
                <span>Total Artists</span>
                <strong><?= $totalArtists ?></strong>
                <a href="artists.php" class="text-link">Manage artists <span>→</span></a>
            </div>
            <div class="stat-card">
                <span>Messages</span>
                <strong><?= $totalMessages ?></strong>
                <a href="messages.php" class="text-link">View inquiries <span>→</span></a>
            </div>
        </div>

        <div class="admin-dashboard-panel">
            <div class="dashboard-panel-header">
                <h2>Recent Artworks</h2>
                <a href="artworks.php" class="text-link">View All <span>→</span></a>
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
                        <?php if (!empty($recentArtworks)): ?>
                            <?php foreach ($recentArtworks as $art): ?>
                                <tr>
                                    <td>
                                        <img
                                            class="table-image"
                                            src="<?= !empty($art['image']) ? '../assets/images/artworks/' . e($art['image']) : '../assets/images/placeholder.svg' ?>"
                                            alt=""
                                        >
                                    </td>
                                    <td><?= e($art['title']) ?></td>
                                    <td><?= e($art['artist_name'] ?? 'Unknown') ?></td>
                                    <td><?= e($art['category']) ?></td>
                                    <td><?= format_price($art['price']) ?></td>
                                    <td class="actions">
                                        <a href="edit-artwork.php?id=<?= (int)$art['id'] ?>" class="action-edit">Edit</a>
                                        <a href="delete-artwork.php?id=<?= (int)$art['id'] ?>" class="action-delete" onclick="return confirm('Delete this artwork?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 28px; color: var(--text-subtle);">No artworks recorded yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>