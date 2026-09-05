<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Add Artist';
$base = '../';
$error = '';

if (is_post()) {
    try {
        verify_csrf();

        $name = trim($_POST['name'] ?? '');
        $bio  = trim($_POST['bio'] ?? '');

        if ($name === '') {
            $error = 'Artist name is required.';
        } else {
            $image = upload_image($_FILES['profile_image'] ?? [], 'artists');

            $stmt = $pdo->prepare("
                INSERT INTO artists (name, bio, profile_image)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$name, $bio, $image]);

            redirect('artists.php');
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

require_once '../includes/header.php';
?>

<div class="futuristic-background">
    <canvas id="particleCanvas"></canvas>
    <div class="background-glow glow-one"></div>
    <div class="background-glow glow-two"></div>
</div>

<section class="admin-section artist-page">
    <div class="container">
        <div class="artist-page-header">
            <a href="artists.php" class="back-button">← Back to Artists</a>
            <div class="artist-heading">
                <span class="eyebrow">MANAGEMENT</span>
                <h1>Add <span>Artist.</span></h1>
                <p>Add a new artist profile to your collection.</p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert error">
                <span class="alert-icon">!</span>
                <span><?= e($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="artist-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

            <div class="artist-form-layout">
                <div class="artist-form-left">
                    <div class="artist-field">
                        <label for="name">Artist Name <span>*</span></label>
                        <input type="text" id="name" name="name" maxlength="200" placeholder="Enter artist full name" required value="<?= old('name') ?>">
                    </div>

                    <div class="artist-field">
                        <label for="bio">Biography</label>
                        <textarea id="bio" name="bio" rows="8" placeholder="Write a brief overview of the artist..."><?= old('bio') ?></textarea>
                    </div>
                </div>

                <div class="artist-form-right">
                    <div class="artist-field">
                        <label>Profile Photo</label>
                        <div class="artist-upload">
                            <input type="file" name="profile_image" id="imageInput" accept=".jpg,.jpeg,.png,.webp">
                            <label for="imageInput" class="artist-upload-label">
                                <div class="upload-icon">↑</div>
                                <strong>Choose Profile Picture</strong>
                                <span>JPG / PNG / WEBP (Max 5MB)</span>
                            </label>
                        </div>
                    </div>

                    <div class="artist-preview">
                        <div class="preview-top">
                            <span>Image Preview</span>
                        </div>
                        <div class="artwork-preview">
                            <img id="imagePreview" class="image-preview" src="../assets/images/placeholder.svg" alt="Preview">
                        </div>
                    </div>
                </div>
            </div>

            <div class="artist-form-actions">
                <button type="submit" class="btn btn-primary">Save Artist <span>→</span></button>
                <a href="artists.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>