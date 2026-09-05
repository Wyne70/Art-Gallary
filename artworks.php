<?php

$pageTitle = 'Gallery';
$base = '';

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$artist = trim($_GET['artist'] ?? '');
$sort = $_GET['sort'] ?? 'newest';

$sql = "
    SELECT
        artworks.*,
        artists.name AS artist_name
    FROM artworks
    LEFT JOIN artists
        ON artworks.artist_id = artists.id
    WHERE 1=1
";

$params = [];

if ($search !== '') {

    $sql .= "
        AND (
            artworks.title LIKE ?
            OR artworks.description LIKE ?
            OR artists.name LIKE ?
        )
    ";

    $searchValue = '%' . $search . '%';

    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;
}

if ($category !== '') {

    $sql .= "
        AND artworks.category = ?
    ";

    $params[] = $category;
}

if ($artist !== '') {

    $sql .= "
        AND artworks.artist_id = ?
    ";

    $params[] = $artist;
}

switch ($sort) {

    case 'oldest':
        $sql .= " ORDER BY artworks.created_at ASC";
        break;

    case 'title':
        $sql .= " ORDER BY artworks.title ASC";
        break;

    case 'price-low':
        $sql .= " ORDER BY artworks.price ASC";
        break;

    case 'price-high':
        $sql .= " ORDER BY artworks.price DESC";
        break;

    default:
        $sql .= " ORDER BY artworks.created_at DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$artworks = $stmt->fetchAll();

$categories = $pdo->query("
    SELECT DISTINCT category
    FROM artworks
    WHERE category IS NOT NULL
      AND category != ''
    ORDER BY category
")->fetchAll();

$artists = $pdo->query("
    SELECT id, name
    FROM artists
    ORDER BY name
")->fetchAll();

?>

<section class="page-hero">

    <div class="container">

        <span class="eyebrow">
            ART COLLECTION
        </span>

        <h1>
            Gallery
        </h1>

        <p>
            Browse our complete collection.
        </p>

    </div>

</section>


<section class="section">

    <div class="container">

        <form
            class="filter-bar"
            method="GET"
        >

            <input
                type="search"
                name="search"
                placeholder="Search artworks..."
                value="<?= e($search) ?>"
            >

            <select name="category">

                <option value="">
                    All Categories
                </option>

                <?php foreach ($categories as $cat): ?>

                    <option
                        value="<?= e($cat['category']) ?>"
                        <?= $category === $cat['category']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($cat['category']) ?>
                    </option>

                <?php endforeach; ?>

            </select>


            <select name="artist">

                <option value="">
                    All Artists
                </option>

                <?php foreach ($artists as $item): ?>

                    <option
                        value="<?= (int)$item['id'] ?>"
                        <?= $artist == $item['id']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($item['name']) ?>
                    </option>

                <?php endforeach; ?>

            </select>


            <select name="sort">

                <option
                    value="newest"
                    <?= $sort === 'newest' ? 'selected' : '' ?>
                >
                    Newest
                </option>

                <option
                    value="oldest"
                    <?= $sort === 'oldest' ? 'selected' : '' ?>
                >
                    Oldest
                </option>

                <option
                    value="title"
                    <?= $sort === 'title' ? 'selected' : '' ?>
                >
                    Title
                </option>

                <option
                    value="price-low"
                    <?= $sort === 'price-low' ? 'selected' : '' ?>
                >
                    Price Low → High
                </option>

                <option
                    value="price-high"
                    <?= $sort === 'price-high' ? 'selected' : '' ?>
                >
                    Price High → Low
                </option>

            </select>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Filter
            </button>

        </form>


        <div class="art-grid">

            <?php foreach ($artworks as $art): ?>

                <article class="art-card reveal">

                    <a
                        href="artwork.php?id=<?= (int)$art['id'] ?>"
                    >

                        <?php if (!empty($art['image'])): ?>

                            <img
                                src="assets/images/artworks/<?= e($art['image']) ?>"
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

                        <p>
                            <?= e(
                                $art['artist_name']
                                ?? 'Unknown Artist'
                            ) ?>
                        </p>

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
                    No artworks found.
                </h3>

                <p>
                    Try another search or category.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php require_once 'includes/footer.php'; ?>