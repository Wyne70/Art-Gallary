<?php

$pageTitle = 'Artists';
$base = '';

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$artists = $pdo->query("
    SELECT
        artists.*,
        COUNT(artworks.id) AS artwork_count
    FROM artists
    LEFT JOIN artworks
        ON artworks.artist_id = artists.id
    GROUP BY artists.id
    ORDER BY artists.name ASC
")->fetchAll();

?>

<section class="page-hero">

    <div class="container">

        <span class="eyebrow">
            CREATIVE MINDS
        </span>

        <h1>
            Artists
        </h1>

        <p>
            Meet the creators behind the collection.
        </p>

    </div>

</section>


<section class="section">

    <div class="container">

        <div class="artist-grid artist-grid-large">

            <?php foreach ($artists as $artist): ?>

                <a
                    href="artist.php?id=<?= (int)$artist['id'] ?>"
                    class="artist-card reveal"
                >

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


                    <div>

                        <h3>
                            <?= e($artist['name']) ?>
                        </h3>

                        <span>
                            <?= (int)$artist['artwork_count'] ?>
                            artworks →
                        </span>

                    </div>

                </a>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php require_once 'includes/footer.php'; ?>