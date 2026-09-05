<?php

declare(strict_types=1);

require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$pageTitle = 'Inquiries';
$base = '../';

if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
    }
    redirect('messages.php');
}

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="futuristic-background">
    <canvas id="particleCanvas"></canvas>
    <div class="background-glow glow-one"></div>
    <div class="background-glow glow-two"></div>
</div>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <div>
                <span class="eyebrow">COMMUNICATION</span>
                <h1>Visitor <span>Messages.</span></h1>
            </div>
        </div>

        <div class="messages-list">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <article class="message-card">
                        <div class="message-header">
                            <div>
                                <h3><?= e($message['subject']) ?></h3>
                                <span><?= e($message['name']) ?> — <?= e($message['email']) ?></span>
                            </div>
                            <a
                                href="messages.php?delete=<?= (int)$message['id'] ?>"
                                class="action-delete"
                                onclick="return confirm('Delete this message?');"
                            >
                                Delete
                            </a>
                        </div>
                        <p><?= nl2br(e($message['message'])) ?></p>
                        <small><?= e($message['created_at']) ?></small>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">✦</div>
                    <h3>No messages found</h3>
                    <p>Contact inquiries sent through the public form will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>