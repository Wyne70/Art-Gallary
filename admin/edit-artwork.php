<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Edit Artwork';
$base = '../';
$error = '';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirect('artworks.php');
}

$stmt = $pdo->prepare("SELECT * FROM artworks WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$artwork = $stmt->fetch();

if (!$artwork) {
    exit('Artwork not found.');
}

$artists = $pdo->query("SELECT id, name FROM artists ORDER BY name ASC")->fetchAll();

if (is_post()) {
    try {
        verify_csrf();

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $artistId    = filter_input(INPUT_POST, 'artist_id', FILTER_VALIDATE_INT);
        $year        = filter_input(INPUT_POST, 'year_created', FILTER_VALIDATE_INT);
        $medium      = trim($_POST['medium'] ?? '');
        $price       = trim($_POST['price'] ?? '');

        if ($title === '' || $description === '' || $category === '' || !$artistId) {
            $error = 'Please complete all required fields.';
        } else {
            $newImage = upload_image($_FILES['image'] ?? [], 'artworks');
            $image = $artwork['image'];

            if ($newImage) {
                delete_image('artworks', $artwork['image']);
                $image = $newImage;
            }

            $update = $pdo->prepare("
                UPDATE artworks
                SET artist_id = ?, title = ?, description = ?, category = ?, year_created = ?, medium = ?, price = ?, image = ?
                WHERE id = ?
            ");
            $update->execute([
                $artistId,
                $title,
                $description,
                $category,
                $year ?: null,
                $medium ?: null,
                $price !== '' ? (float)$price : null,
                $image,
                $id
            ]);

            redirect('artworks.php');
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$categories = [
    'Painting', 'Digital Art', 'Photography', 'Sculpture',
    'Illustration', 'Abstract', 'Portrait', 'Landscape'
];

require_once '../includes/header.php';
?>

<div class="futuristic-background">
    <canvas id="particleCanvas"></canvas>
    <div class="background-glow glow-one"></div>
    <div class="background-glow glow-two"></div>
</div>

<section class="admin-section artwork-form-section">
    <div class="container">
        <div class="admin-page-header">
            <a href="artworks.php" class="back-button">← Back to Artworks</a>
            <div class="admin-title-area">
                <span class="eyebrow">MANAGEMENT</span>
                <h1>Edit <span>Artwork.</span></h1>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert error">
                <span class="alert-icon">!</span>
                <span><?= e($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="artwork-admin-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

            <div class="artwork-form-grid">
                <div class="artwork-form-main">
                    <div class="admin-field">
                        <label for="title">Title <span>*</span></label>
                        <input type="text" id="title" name="title" required value="<?= e($artwork['title']) ?>">
                    </div>

                    <div class="admin-field">
                        <label for="artist_id">Artist <span>*</span></label>
                        <select id="artist_id" name="artist_id" required>
                            <?php foreach ($artists as $a): ?>
                                <option value="<?= (int)$a['id'] ?>" <?= (int)$artwork['artist_id'] === (int)$a['id'] ? 'selected' : '' ?>>
                                    <?= e($a['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="category">Category <span>*</span></label>
                        <select id="category" name="category" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat) ?>" <?= $artwork['category'] === $cat ? 'selected' : '' ?>>
                                    <?= e($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="description">Description <span>*</span></label>
                        <textarea id="description" name="description" rows="8" required><?= e($artwork['description']) ?></textarea>
                    </div>
                </div>

                <div class="artwork-form-side">
                    <div class="admin-field">
                        <label for="year_created">Year Created</label>
                        <input type="number" id="year_created" name="year_created" min="1000" max="9999" value="<?= e((string)$artwork['year_created']) ?>">
                    </div>

                    <div class="admin-field">
                        <label for="medium">Medium</label>
                        <input type="text" id="medium" name="medium" value="<?= e($artwork['medium'] ?? '') ?>">
                    </div>

                    <div class="admin-field">
                        <label for="price">Price (₱)</label>
                        <div class="price-input">
                            <span>₱</span>
                            <input type="number" id="price" name="price" step="0.01" min="0" value="<?= e((string)$artwork['price']) ?>">
                        </div>
                    </div>

                    <div class="admin-field">
                        <label>Replace Image</label>
                        <div class="file-upload">
                            <input type="file" id="imageInput" name="image" accept=".jpg,.jpeg,.png,.webp">
                            <label for="imageInput" class="file-upload-label">
                                <span class="upload-icon">↑</span>
                                <span class="upload-title">Choose New Image</span>
                                <span class="upload-subtitle">Leave empty to keep existing</span>
                            </label>
                        </div>
                    </div>

                    <div class="artwork-preview-container">
                        <div class="preview-heading">
                            <span>Current / New Preview</span>
                        </div>
                        <div class="artwork-preview">
                            <img
                                id="imagePreview"
                                class="image-preview"
                                src="<?= !empty($artwork['image']) ? '../assets/images/artworks/' . e($artwork['image']) : '../assets/images/placeholder.svg' ?>"
                                alt="Preview"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="artwork-form-actions">
                <button type="submit" class="btn btn-primary">Update Artwork <span>→</span></button>
                <a href="artworks.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>