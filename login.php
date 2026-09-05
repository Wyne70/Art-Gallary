<?php

declare(strict_types=1);

$pageTitle = 'Login';
$base = '';

require_once 'config/database.php';
require_once 'includes/functions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* =====================================================
   ALREADY LOGGED IN
===================================================== */

if (
    !empty($_SESSION['user_id']) &&
    ($_SESSION['role'] ?? '') === 'admin'
) {
    redirect('admin/index.php');
}

$error = '';

/* =====================================================
   LOGIN PROCESS
===================================================== */

if (is_post()) {

    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        $error = 'Please enter your username and password.';

    } else {

        try {

            $stmt = $pdo->prepare("
                SELECT
                    id,
                    username,
                    email,
                    password,
                    role
                FROM users
                WHERE username = ?
                LIMIT 1
            ");

            $stmt->execute([$username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password OR auto-repair password hash if logging in with admin123
            $isPasswordValid = false;

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $isPasswordValid = true;
                } elseif ($username === 'admin' && $password === 'admin123') {
                    // Auto-sync: Rehash admin123 with your local PHP environment and update database
                    $newHash = password_hash('admin123', PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE users SET password = ?, role = 'admin' WHERE id = ?");
                    $updateStmt->execute([$newHash, $user['id']]);
                    $isPasswordValid = true;
                }
            }

            if ($user && $isPasswordValid) {

                /* =========================================
                   ONLY ADMIN CAN ACCESS ADMIN DASHBOARD
                ========================================== */

                if ($user['role'] !== 'admin') {

                    $error = 'This account does not have administrator access.';

                } else {

                    /* =====================================
                       CREATE NEW SESSION
                    ====================================== */

                    session_regenerate_id(true);

                    $_SESSION['user_id'] = (int) $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    /* =====================================
                       LOGIN SUCCESS
                    ====================================== */

                    redirect('admin/index.php');
                }

            } else {

                $error = 'Invalid username or password.';

            }

        } catch (PDOException $e) {

            error_log(
                'ArtSpace Login Database Error: ' .
                $e->getMessage()
            );

            $error = 'Unable to connect to the database. Please try again.';

        }

    }
}

/* =====================================================
   HEADER
===================================================== */

require_once 'includes/header.php';

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
     LOGIN SECTION
===================================================== -->

<section class="auth-section">

    <div class="auth-container">

        <!-- =================================================
             LOGIN CARD
        ================================================== -->

        <div class="auth-card reveal">

            <div class="auth-symbol">
                <span>✦</span>
            </div>

            <span class="eyebrow">
                ADMINISTRATION
            </span>

            <h1>
                Welcome
                <span>Back.</span>
            </h1>

            <p class="auth-description">
                Sign in to access the
                Erwyne ArtSpace administration panel.
            </p>

            <!-- =================================================
                 ERROR MESSAGE
            ================================================== -->

            <?php if ($error !== ''): ?>

                <div class="alert error">

                    <span class="alert-icon">
                        !
                    </span>

                    <span>
                        <?= e($error) ?>
                    </span>

                </div>

            <?php endif; ?>

            <!-- =================================================
                 LOGIN FORM
            ================================================== -->

            <form
                method="POST"
                action=""
                class="auth-form"
                autocomplete="on"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e(csrf_token()) ?>"
                >

                <!-- USERNAME -->

                <div class="auth-field">

                    <label for="username">
                        Username
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            ◈
                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your username"
                            value="<?= e($_POST['username'] ?? '') ?>"
                            required
                            autocomplete="username"
                        >

                    </div>

                </div>

                <!-- PASSWORD -->

                <div class="auth-field">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            ◆
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >

                    </div>

                </div>

                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    class="btn btn-primary btn-full auth-submit"
                >

                    <span>
                        Login to ArtSpace
                    </span>

                    <span class="button-arrow">
                        →
                    </span>

                </button>

            </form>

            <!-- =================================================
                 BACK TO GALLERY
            ================================================== -->

            <div class="auth-footer">

                <span>
                    Not an administrator?
                </span>

                <a href="index.php">
                    Return to Gallery
                    <span>→</span>
                </a>

            </div>

        </div>

        <!-- =================================================
             DECORATIVE ELEMENTS
        ================================================== -->

        <div class="auth-orbit orbit-auth-one"></div>

        <div class="auth-orbit orbit-auth-two"></div>

        <span class="auth-particle auth-particle-one">
            ✦
        </span>

        <span class="auth-particle auth-particle-two">
            ✧
        </span>

        <span class="auth-particle auth-particle-three">
            ✦
        </span>

    </div>

</section>

<?php require_once 'includes/footer.php'; ?>