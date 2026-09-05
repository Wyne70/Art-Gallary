<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Add Artwork';
$base = '../';
$error = '';

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
        } elseif ($year !== false && $year !== null && ($year < 1000 || $year > 9999)) {
            $error = 'Please enter a valid 4-digit year.';
        } elseif ($price !== '' && (!is_numeric($price) || (float)$price < 0)) {
            $error = 'Please enter a valid price amount.';
        } else {
            $image = upload_image($_FILES['image'] ?? [], 'artworks');

            $stmt = $pdo->prepare("
                INSERT INTO artworks (artist_id, title, description, category, year_created, medium, price, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $artistId,
                $title,
                $description,
                $category,
                $year ?: null,
                $medium ?: null,
                $price !== '' ? (float)$price : null,
                $image
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
                <h1>Add <span>Artwork.</span></h1>
                <p>Register an original creation into your collection.</p>
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
                        <input type="text" id="title" name="title" maxlength="200" placeholder="Enter artwork title" required value="<?= old('title') ?>">
                    </div>

                    <div class="admin-field">
                        <label for="artist_id">Artist <span>*</span></label>
                        <select id="artist_id" name="artist_id" required>
                            <option value="">Select Artist</option>
                            <?php foreach ($artists as $a): ?>
                                <option value="<?= (int)$a['id'] ?>" <?= (int)($_POST['artist_id'] ?? 0) === (int)$a['id'] ? 'selected' : '' ?>>
                                    <?= e($a['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="category">Category <span>*</span></label>
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat) ?>" <?= ($_POST['category'] ?? '') === $cat ? 'selected' : '' ?>>
                                    <?= e($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="description">Description <span>*</span></label>
                        <textarea id="description" name="description" rows="8" placeholder="Describe this artwork..." required><?= old('description') ?></textarea>
                    </div>
                </div>

                <div class="artwork-form-side">
                    <div class="admin-field">
                        <label for="year_created">Year Created</label>
                        <input type="number" id="year_created" name="year_created" min="1000" max="9999" placeholder="Example: 2026" value="<?= old('year_created') ?>">
                    </div>

                    <div class="admin-field">
                        <label for="medium">Medium</label>
                        <input type="text" id="medium" name="medium" maxlength="150" placeholder="Example: Oil on Canvas" value="<?= old('medium') ?>">
                    </div>

                    <div class="admin-field">
                        <label for="price">Price (₱)</label>
                        <div class="price-input">
                            <span>₱</span>
                            <input type="number" id="price" name="price" step="0.01" min="0" placeholder="0.00" value="<?= old('price') ?>">
                        </div>
                    </div>

                    <div class="admin-field">
                        <label>Artwork Image</label>
                        <div class="file-upload">
                            <input type="file" id="imageInput" name="image" accept=".jpg,.jpeg,.png,.webp">
                            <label for="imageInput" class="file-upload-label">
                                <span class="upload-icon">↑</span>
                                <span class="upload-title">Choose Artwork Image</span>
                                <span class="upload-subtitle">JPG, PNG, or WEBP • Max 5MB</span>
                            </label>
                        </div>
                    </div>

                    <div class="artwork-preview-container">
                        <div class="preview-heading">
                            <span>Image Preview</span>
                        </div>
                        <div class="artwork-preview">
                            <img id="imagePreview" class="image-preview" src="../assets/images/placeholder.svg" alt="Preview">
                        </div>
                    </div>
                </div>
            </div>

            <div class="artwork-form-actions">
                <button type="submit" class="btn btn-primary">Save Artwork <span>→</span></button>
                <a href="artworks.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>