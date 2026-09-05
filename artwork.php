<?php

$pageTitle = 'Artwork';
$base = '';

require_once 'config/database.php';
require_once 'includes/functions.php';

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id) {
    redirect('artworks.php');
}

$stmt = $pdo->prepare("
    SELECT
        artworks.*,
        artists.name AS artist_name,
        artists.bio AS artist_bio,
        artists.profile_image
    FROM artworks
    LEFT JOIN artists
        ON artworks.artist_id = artists.id
    WHERE artworks.id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$artwork = $stmt->fetch();

if (!$artwork) {
    http_response_code(404);
    exit('Artwork not found.');
}

$pageTitle = $artwork['title'];

require_once 'includes/header.php';

$relatedStmt = $pdo->prepare("
    SELECT
        artworks.*,
        artists.name AS artist_name
    FROM artworks
    LEFT JOIN artists
        ON artworks.artist_id = artists.id
    WHERE artworks.category = ?
      AND artworks.id != ?
    ORDER BY artworks.created_at DESC
    LIMIT 4
");

$relatedStmt->execute([
    $artwork['category'],
    $artwork['id']
]);

$related = $relatedStmt->fetchAll();

?>

<section class="section artwork-detail">

    <div class="container artwork-detail-grid">

        <div class="artwork-image reveal">

            <?php if (!empty($artwork['image'])): ?>

                <img
                    src="assets/images/artworks/<?= e($artwork['image']) ?>"
                    alt="<?= e($artwork['title']) ?>"
                    class="lightbox-image"
                >

            <?php else: ?>

                <img
                    src="assets/images/placeholder.svg"
                    alt="Artwork"
                >

            <?php endif; ?>

        </div>


        <div class="artwork-info reveal-right">

            <span class="category">
                <?= e($artwork['category']) ?>
            </span>

            <h1>
                <?= e($artwork['title']) ?>
            </h1>

            <p class="artist-name">

                By

                <?php if ($artwork['artist_id']): ?>

                    <a
                        href="artist.php?id=<?= (int)$artwork['artist_id'] ?>"
                    >
                        <?= e($artwork['artist_name']) ?>
                    </a>

                <?php else: ?>

                    Unknown Artist

                <?php endif; ?>

            </p>

            <div class="artwork-description">

                <?= nl2br(e($artwork['description'])) ?>

            </div>


            <div class="artwork-meta">

                <?php if ($artwork['year_created']): ?>

                    <div>
                        <span>Year</span>
                        <strong>
                            <?= e(
                                (string)$artwork['year_created']
                            ) ?>
                        </strong>
                    </div>

                <?php endif; ?>


                <?php if ($artwork['medium']): ?>

                    <div>
                        <span>Medium</span>
                        <strong>
                            <?= e($artwork['medium']) ?>
                        </strong>
                    </div>

                <?php endif; ?>

            </div>


            <div class="artwork-price">

                <?= format_price($artwork['price']) ?>

            </div>

            <a
                href="contact.php?subject=<?= urlencode(
                    'Inquiry about ' . $artwork['title']
                ) ?>"
                class="btn btn-primary"
            >
                Inquire About This Artwork
            </a>

        </div>

    </div>

</section>


<?php if ($related): ?>

<section class="section section-dark">

    <div class="container">

        <div class="section-heading">

            <span class="eyebrow">
                YOU MAY ALSO LIKE
            </span>

            <h2>
                Related Works
            </h2>

        </div>

        <div class="art-grid">

            <?php foreach ($related as $item): ?>

                <article class="art-card">

                    <a
                        href="artwork.php?id=<?= (int)$item['id'] ?>"
                    >

                        <?php if ($item['image']): ?>

                            <img
                                src="assets/images/artworks/<?= e($item['image']) ?>"
                                alt="<?= e($item['title']) ?>"
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
                            <?= e($item['category']) ?>
                        </span>

                        <h3>
                            <?= e($item['title']) ?>
                        </h3>

                        <p>
                            <?= e($item['artist_name'] ?? 'Unknown') ?>
                        </p>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>