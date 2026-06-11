<?php
require_once __DIR__ . '/config.php';
spl_autoload_register(function (string $className): void {
    $file = __DIR__ . '/../classes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function uploadFotka(array $file, ?string $stara = null): ?string
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE || $file['size'] === 0) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Chyba pri nahrávaní súboru.');
    }

    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $mime    = mime_content_type($file['tmp_name']);

    if (!in_array($mime, $allowed, true)) {
        throw new RuntimeException('Povolené sú len obrázky (JPG, PNG, WebP, GIF).');
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Obrázok je príliš veľký (max 2 MB).');
    }

    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nazov    = uniqid('foto_', true) . '.' . strtolower($ext);
    $ciel     = UPLOAD_DIR . $nazov;

    if (!move_uploaded_file($file['tmp_name'], $ciel)) {
        throw new RuntimeException('Nepodarilo sa uložiť obrázok.');
    }

    if ($stara && file_exists(UPLOAD_DIR . $stara)) {
        unlink(UPLOAD_DIR . $stara);
    }

    return $nazov;
}
