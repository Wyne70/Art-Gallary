<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

?>

<nav class="navbar">

    <!-- NAVBAR GLOW -->

    <div class="navbar-glow"></div>


    <div class="container nav-inner">


        <!-- =================================================
             BRAND
        ================================================== -->

        <a
            href="<?= $base ?>index.php"
            class="brand"
        >

            <span class="brand-name">
                ERWYNE
            </span>

            <span class="brand-art">
                ART
            </span>

            <span class="brand-space">
                SPACE
            </span>

            <span class="brand-symbol">
                ✦
            </span>

        </a>


        <!-- =================================================
             MOBILE MENU
        ================================================== -->

        <button
            class="menu-toggle"
            id="menuToggle"
            type="button"
            aria-label="Toggle navigation"
            aria-expanded="false"
        >

            <span></span>
            <span></span>
            <span></span>

        </button>


        <!-- =================================================
             NAVIGATION
        ================================================== -->

        <div
            class="nav-links"
            id="navLinks"
        >


            <!-- HOME -->

            <a
                href="<?= $base ?>index.php"
                class="nav-link"
            >

                <span class="nav-icon">
                    ⌂
                </span>

                <span>
                    Home
                </span>

            </a>


            <!-- GALLERY -->

            <a
                href="<?= $base ?>artworks.php"
                class="nav-link"
            >

                <span class="nav-icon">
                    ✦
                </span>

                <span>
                    Gallery
                </span>

            </a>


            <!-- ARTISTS -->

            <a
                href="<?= $base ?>artists.php"
                class="nav-link"
            >

                <span class="nav-icon">
                    ◈
                </span>

                <span>
                    Artists
                </span>

            </a>


            <!-- ABOUT -->

            <a
                href="<?= $base ?>about.php"
                class="nav-link"
            >

                <span class="nav-icon">
                    ◎
                </span>

                <span>
                    About
                </span>

            </a>


            <!-- CONTACT -->

            <a
                href="<?= $base ?>contact.php"
                class="nav-link"
            >

                <span class="nav-icon">
                    ↗
                </span>

                <span>
                    Contact
                </span>

            </a>


            <?php if (
                !empty($_SESSION['user_id']) &&
                ($_SESSION['role'] ?? '') === 'admin'
            ): ?>


                <!-- ADMIN -->

                <a
                    href="<?= $base ?>admin/index.php"
                    class="nav-link nav-admin"
                >

                    <span class="nav-icon">
                        ◉
                    </span>

                    <span>
                        Admin
                    </span>

                </a>


                <!-- LOGOUT -->

                <a
                    href="<?= $base ?>logout.php"
                    class="nav-link nav-logout"
                >

                    <span>
                        Logout
                    </span>

                    <span>
                        →
                    </span>

                </a>


            <?php else: ?>


                <!-- LOGIN -->

                <a
                    href="<?= $base ?>login.php"
                    class="nav-login"
                >

                    <span>
                        Login
                    </span>

                    <span>
                        →
                    </span>

                </a>


            <?php endif; ?>


        </div>


        <!-- NAVIGATION PARTICLES -->

        <div class="nav-particle particle-one"></div>

        <div class="nav-particle particle-two"></div>

        <div class="nav-particle particle-three"></div>


    </div>

</nav>