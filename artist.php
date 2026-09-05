<?php

$pageTitle = 'Artist';
$base = '';

require_once 'config/database.php';
require_once 'includes/functions.php';

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id) {
    redirect('artists.php');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM artists
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$artist = $stmt->fetch();

if (!$artist) {
    http_response_code(404);
    exit('Artist not found.');
}

$pageTitle = $artist['name'];

require_once 'includes/header.php';

$artworkStmt = $pdo->prepare("
    SELECT *
    FROM artworks
    WHERE artist_id = ?
    ORDER BY created_at DESC
");

$artworkStmt->execute([$id]);

$artworks = $artworkStmt->fetchAll();

?>

<section class="artist-profile">

    <div class="container artist-profile-grid">

        <div class="artist-profile-image reveal">

            <?php if ($artist['profile_image']): ?>

                <img
                    src="assets/images/artists/<?= e(
                        $artist['profile_image']
                    ) ?>"
                    alt="<?= e($artist['name']) ?>"
                >

            <?php else: ?>

                <img
                    src="assets/images/placeholder.svg"
                    alt="Artist"
                >

            <?php endif; ?>

        </div>


        <div class="artist-profile-info reveal-right">

            <span class="eyebrow">
                ARTIST
            </span>

            <h1>
                <?= e($artist['name']) ?>
            </h1>

            <p>
                <?= nl2br(
                    e(
                        $artist['bio']
                        ?: 'This artist has not added a biography yet.'
                    )
                ) ?>
            </p>

        </div>

    </div>

</section>


<section class="section">

    <div class="container">

        <div class="section-heading">

            <span class="eyebrow">
                PORTFOLIO
            </span>

            <h2>
                Works by <?= e($artist['name']) ?>
            </h2>

        </div>


        <div class="art-grid">

            <?php foreach ($artworks as $art): ?>

                <article class="art-card reveal">

                    <a
                        href="artwork.php?id=<?= (int)$art['id'] ?>"
                    >

                        <?php if ($art['image']): ?>

                            <img
                                src="assets/images/artworks/<?= e(
                                    $art['image']
                                ) ?>"
                                alt="<?= e($art['title']) ?>"
                            >

                        <?php else: ?>

                            <img
                                src="assets/images/placeholder.svg"
                                alt="Artwork"
                            >

                        <?php endif; ?>

                    </a>

                    <div class="art-card-content">

                        <span class="category">
                            <?= e($art['category']) ?>
                        </span>

                        <h3>
                            <?= e($art['title']) ?>
                        </h3>

                        <strong>
                            <?= format_price($art['price']) ?>
                        </strong>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>


        <?php if (!$artworks): ?>

            <div class="empty-state">

                <h3>
                    No artworks yet.
                </h3>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php require_once 'includes/footer.php'; ?>