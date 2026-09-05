<?php

declare(strict_types=1);

$pageTitle = 'Home';
$base = '';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';


/* =====================================================
   START SESSION
===================================================== */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


/* =====================================================
   FEATURED ARTWORKS
===================================================== */

$featuredArtworksStmt = $pdo->query("
    SELECT
        artworks.*,
        artists.name AS artist_name
    FROM artworks
    LEFT JOIN artists
        ON artworks.artist_id = artists.id
    ORDER BY artworks.created_at DESC
    LIMIT 6
");

$featuredArtworks = $featuredArtworksStmt->fetchAll();


/* =====================================================
   ARTISTS
===================================================== */

$artistsStmt = $pdo->query("
    SELECT *
    FROM artists
    ORDER BY name ASC
    LIMIT 5
");

$artists = $artistsStmt->fetchAll();


/* =====================================================
   COUNTS
===================================================== */

$totalArtworks = (int) $pdo
    ->query("SELECT COUNT(*) FROM artworks")
    ->fetchColumn();

$totalArtists = (int) $pdo
    ->query("SELECT COUNT(*) FROM artists")
    ->fetchColumn();


/* =====================================================
   HEADER
===================================================== */

require_once __DIR__ . '/includes/header.php';

?>


<!-- =====================================================
     FUTURISTIC BACKGROUND
===================================================== -->

<div class="futuristic-background" aria-hidden="true">

    <canvas id="particleCanvas"></canvas>

    <div class="background-glow glow-one"></div>

    <div class="background-glow glow-two"></div>

</div>


<!-- =====================================================
     HERO
===================================================== -->

<section class="home-hero">

    <div class="container home-hero-grid">


        <!-- LEFT SIDE -->

        <div class="home-hero-content reveal">

            <span class="eyebrow">
                CONTEMPORARY ART GALLERY
            </span>


            <h1>

                Where Ideas

                <br>

                Become

                <span>
                    Art.
                </span>

            </h1>


            <p>

                Explore a curated collection of paintings,
                digital art, photography, sculpture,
                illustration and more.

            </p>


            <div class="hero-buttons">

                <a
                    href="artworks.php"
                    class="btn btn-primary"
                >
                    Explore Gallery
                    <span>→</span>
                </a>


                <a
                    href="artists.php"
                    class="btn btn-outline"
                >
                    Meet Artists
                    <span>→</span>
                </a>

            </div>


            <!-- HERO STATS -->

            <div class="home-stats">

                <div class="home-stat">

                    <strong>
                        <?= $totalArtworks ?>
                    </strong>

                    <span>
                        Artworks
                    </span>

                </div>


                <div class="home-stat">

                    <strong>
                        <?= $totalArtists ?>
                    </strong>

                    <span>
                        Artists
                    </span>

                </div>


                <div class="home-stat">

                    <strong>
                        24/7
                    </strong>

                    <span>
                        Online
                    </span>

                </div>

            </div>

        </div>


        <!-- RIGHT SIDE -->

        <div class="home-hero-visual reveal-right">

            <div class="home-art-orbit">

                <div class="home-orbit orbit-a"></div>

                <div class="home-orbit orbit-b"></div>

                <div class="home-orbit orbit-c"></div>


                <div class="home-art-core">

                    <span class="home-art-symbol">
                        ✦
                    </span>

                    <span class="home-art-title">
                        ERWYNE
                    </span>

                    <span class="home-art-subtitle">
                        ARTSPACE
                    </span>

                    <span class="home-art-line"></span>

                    <small>
                        DIGITAL ART GALLERY
                    </small>

                </div>


                <span class="floating-star star-1">
                    ✦
                </span>

                <span class="floating-star star-2">
                    ✧
                </span>

                <span class="floating-star star-3">
                    ✦
                </span>

                <span class="floating-star star-4">
                    ✧
                </span>

            </div>

        </div>

    </div>


    <!-- NETWORK FLOOR -->

    <div class="network-floor">

        <canvas id="networkCanvas"></canvas>

        <div class="network-glow"></div>

    </div>

</section>


<!-- =====================================================
     FEATURED COLLECTION
===================================================== -->

<section class="section home-featured">

    <div class="container">


        <div class="section-heading reveal">

            <span class="eyebrow">
                FEATURED COLLECTION
            </span>

            <h2>
                Selected Works
            </h2>

            <p>
                Discover the latest additions to
                the Erwyne ArtSpace collection.
            </p>

        </div>


        <div class="art-grid">


            <?php if (!empty($featuredArtworks)): ?>


                <?php foreach ($featuredArtworks as $art): ?>

                    <article class="art-card reveal">


                        <a
                            href="artwork.php?id=<?= (int) $art['id'] ?>"
                            class="art-image-link"
                        >

                            <?php if (!empty($art['image'])): ?>

                                <img
                                    src="assets/images/artworks/<?= e($art['image']) ?>"
                                    alt="<?= e($art['title']) ?>"
                                    loading="lazy"
                                >

                            <?php else: ?>

                                <img
                                    src="assets/images/placeholder.svg"
                                    alt="Artwork placeholder"
                                    loading="lazy"
                                >

                            <?php endif; ?>

                        </a>


                        <div class="art-card-content">

                            <span class="category">
                                <?= e(
                                    $art['category']
                                    ?? 'Artwork'
                                ) ?>
                            </span>


                            <h3>
                                <?= e($art['title']) ?>
                            </h3>


                            <p>
                                <?= e(
                                    $art['artist_name']
                                    ?? 'Unknown Artist'
                                ) ?>
                            </p>


                            <?php if (
                                isset($art['price']) &&
                                $art['price'] !== null &&
                                $art['price'] !== ''
                            ): ?>

                                <strong>
                                    <?= format_price(
                                        $art['price']
                                    ) ?>
                                </strong>

                            <?php else: ?>

                                <strong>
                                    Price on request
                                </strong>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>


            <?php else: ?>

                <div class="empty">

                    <span>
                        ✦
                    </span>

                    <h3>
                        No artworks available yet.
                    </h3>

                    <p>
                        Add artworks from the admin dashboard.
                    </p>

                </div>

            <?php endif; ?>


        </div>


        <div class="section-button reveal">

            <a
                href="artworks.php"
                class="btn btn-outline"
            >
                View All Artworks
                <span>→</span>
            </a>

        </div>

    </div>

</section>


<!-- =====================================================
     ARTISTS
===================================================== -->

<section class="section section-dark home-artists">

    <div class="container">


        <div class="section-heading reveal">

            <span class="eyebrow">
                OUR ARTISTS
            </span>

            <h2>
                Meet the Creators
            </h2>

            <p>
                The creative minds behind
                the ArtSpace collection.
            </p>

        </div>


        <div class="artist-grid">


            <?php foreach ($artists as $artist): ?>

                <a
                    href="artist.php?id=<?= (int) $artist['id'] ?>"
                    class="artist-card reveal"
                >

                    <?php if (
                        !empty($artist['profile_image'])
                    ): ?>

                        <img
                            src="assets/images/artists/<?= e(
                                $artist['profile_image']
                            ) ?>"
                            alt="<?= e($artist['name']) ?>"
                            loading="lazy"
                        >

                    <?php else: ?>

                        <img
                            src="assets/images/placeholder.svg"
                            alt="Artist placeholder"
                            loading="lazy"
                        >

                    <?php endif; ?>


                    <div>

                        <span>
                            ARTIST
                        </span>

                        <h3>
                            <?= e($artist['name']) ?>
                        </h3>

                        <span>
                            View Artist →
                        </span>

                    </div>

                </a>

            <?php endforeach; ?>


        </div>


        <div class="section-button reveal">

            <a
                href="artists.php"
                class="btn btn-outline"
            >
                View All Artists
                <span>→</span>
            </a>

        </div>

    </div>

</section>


<!-- =====================================================
     CTA
===================================================== -->

<section class="cta-section">

    <div class="container reveal">

        <span class="eyebrow">
            ERWYNE ARTSPACE
        </span>


        <h2>
            Where creativity
            <span>has no limits.</span>
        </h2>


        <p>
            Discover new perspectives,
            explore inspiring works,
            and become part of our artistic story.
        </p>


        <a
            href="contact.php"
            class="btn btn-primary"
        >
            Contact Us
            <span>→</span>
        </a>

    </div>

</section>


<?php

require_once __DIR__ . '/includes/footer.php';

?>