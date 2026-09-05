<?php

$pageTitle = 'Contact';
$base = '';

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message === '') {

        $error = 'Please fill in all fields.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'Please enter a valid email address.';

    } else {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO contact_messages
                (name, email, subject, message)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $name,
                $email,
                $subject,
                $message
            ]);

            $success = 'Your message has been sent successfully!';

            $_POST = [];

        } catch (PDOException $e) {

            $error = 'Something went wrong. Please try again later.';

        }
    }
}

?>

<!-- CONTACT HERO -->

<section class="page-hero">

    <div class="container">

        <div class="page-hero-content reveal">

            <span class="eyebrow">
                GET IN TOUCH
            </span>

            <h1>
                Contact <span>Us.</span>
            </h1>

            <p>
                Have a question about our gallery?
                Send us a message.
            </p>

        </div>

    </div>

</section>


<!-- CONTACT SECTION -->

<section class="contact-section">

    <div class="container">

        <div class="contact-layout">

            <!-- CONTACT INFORMATION -->

            <div class="contact-info reveal-left">

                <span class="eyebrow">
                    CONTACT
                </span>

                <h2>
                    Let's start a
                    <span>conversation.</span>
                </h2>

                <p>
                    Whether you want to ask about an artwork,
                    an artist, or the gallery itself,
                    we'd love to hear from you.
                </p>


                <div class="contact-details">

                    <div class="contact-detail">

                        <div class="contact-detail-icon">
                            ✉
                        </div>

                        <div>

                            <span>Email</span>

                            <strong>
                                erwynepogi@example.com
                            </strong>

                        </div>

                    </div>


                    <div class="contact-detail">

                        <div class="contact-detail-icon">
                            ◉
                        </div>

                        <div>

                            <span>Location</span>

                            <strong>
                                Philippines
                            </strong>

                        </div>

                    </div>


                    <div class="contact-detail">

                        <div class="contact-detail-icon">
                            ✦
                        </div>

                        <div>

                            <span>Gallery</span>

                            <strong>
                                Erwyne ArtSpace
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            <!-- CONTACT FORM -->

            <div class="contact-form-wrapper reveal-right">

                <div class="contact-form-glow"></div>

                <form
                    action="contact.php"
                    method="POST"
                    class="contact-form"
                >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e(csrf_token()) ?>"
                    >


                    <?php if ($success): ?>

                        <div class="form-message success">
                            <?= e($success) ?>
                        </div>

                    <?php endif; ?>


                    <?php if ($error): ?>

                        <div class="form-message error">
                            <?= e($error) ?>
                        </div>

                    <?php endif; ?>


                    <!-- NAME -->

                    <div class="form-group">

                        <label for="name">
                            Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Your name"
                            value="<?= e($_POST['name'] ?? '') ?>"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="your@email.com"
                            value="<?= e($_POST['email'] ?? '') ?>"
                            required
                        >

                    </div>


                    <!-- SUBJECT -->

                    <div class="form-group">

                        <label for="subject">
                            Subject
                        </label>

                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            placeholder="What is your message about?"
                            value="<?= e($_POST['subject'] ?? '') ?>"
                            required
                        >

                    </div>


                    <!-- MESSAGE -->

                    <div class="form-group">

                        <label for="message">
                            Message
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            placeholder="Write your message here..."
                            required
                        ><?= e($_POST['message'] ?? '') ?></textarea>

                    </div>


                    <!-- BUTTON -->

                    <button
                        type="submit"
                        class="btn btn-primary form-submit"
                    >
                        <span>Send Message</span>
                        <span>→</span>
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>


<!-- CTA -->

<section class="cta-section contact-cta">

    <div class="container reveal">

        <span class="eyebrow">
            ERWYNE ARTSPACE
        </span>

        <h2>
            Where ideas become
            <span>art.</span>
        </h2>

        <a
            href="artworks.php"
            class="btn btn-outline"
        >
            Explore Gallery
        </a>

    </div>

</section>


<?php require_once 'includes/footer.php'; ?>