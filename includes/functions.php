<?php
declare(strict_types=1);

/* Start session automatically if not already running */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header("Location: $url");
    exit;
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        empty($_SESSION['csrf_token']) ||
        empty($token) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(419);
        exit('Invalid security token. Please refresh the page and try again.');
    }
}

function upload_image(
    array $file,
    string $folder,
    int $maxBytes = 5242880
): ?string {

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed.');
    }

    if ($file['size'] > $maxBytes) {
        throw new RuntimeException('Image must be 5MB or smaller.');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (
        !isset($allowed[$mime]) ||
        @getimagesize($file['tmp_name']) === false
    ) {
        throw new RuntimeException(
            'Only JPG, JPEG, PNG and WEBP images are allowed.'
        );
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];

    $directory = __DIR__ . '/../assets/images/' . $folder;

    if (!is_dir($directory)) {
        if (!mkdir($directory, 0755, true)) {
            throw new RuntimeException(
                'Could not create image directory.'
            );
        }
    }

    if (
        !move_uploaded_file(
            $file['tmp_name'],
            $directory . '/' . $filename
        )
    ) {
        throw new RuntimeException(
            'Could not save uploaded image.'
        );
    }

    return $filename;
}

function delete_image(
    string $folder,
    ?string $filename
): void {

    if (!$filename) {
        return;
    }

    $safe = basename($filename);

    $path =
        __DIR__ .
        '/../assets/images/' .
        $folder .
        '/' .
        $safe;

    if (is_file($path)) {
        @unlink($path);
    }
}

function format_price($price): string
{
    if ($price === null || $price === '') {
        return 'Price on request';
    }

    return '₱' . number_format(
        (float)$price,
        2
    );
}

function old(string $key, string $default = ''): string
{
    return e($_POST[$key] ?? $default);
}