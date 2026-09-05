<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'ArtSpace Gallery';
$base = $base ?? '';

require_once __DIR__ . '/functions.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= e($pageTitle) ?> | ArtSpace
    </title>

    <meta
        name="description"
        content="Erwyne ArtSpace — Discover creativity, culture, and contemporary art."
    >

    <link
        rel="stylesheet"
        href="<?= $base ?>assets/css/style.css"
    >

</head>

<body>


<!-- =====================================================
     FUTURISTIC BACKGROUND
===================================================== -->

<div class="background-animation" aria-hidden="true">

    <span class="bg-star bg-star-1">✦</span>
    <span class="bg-star bg-star-2">✧</span>
    <span class="bg-star bg-star-3">✦</span>
    <span class="bg-star bg-star-4">✧</span>
    <span class="bg-star bg-star-5">✦</span>

    <span class="bg-particle particle-1"></span>
    <span class="bg-particle particle-2"></span>
    <span class="bg-particle particle-3"></span>
    <span class="bg-particle particle-4"></span>
    <span class="bg-particle particle-5"></span>
    <span class="bg-particle particle-6"></span>
    <span class="bg-particle particle-7"></span>
    <span class="bg-particle particle-8"></span>

</div>


<?php require __DIR__ . '/navbar.php'; ?>