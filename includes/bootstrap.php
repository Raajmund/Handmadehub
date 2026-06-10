<?php
// ============================================
// includes/bootstrap.php
// Načíta config, triedy a pomocné funkcie
// Tento súbor vložíš na začiatok každej PHP stránky
// ============================================

require_once __DIR__ . '/config.php';

// --- Autoloader: automaticky načíta triedy z priečinka /classes/ ---
// Namiesto písania require_once pre každú triedu zvlášť
spl_autoload_register(function (string $className): void {
    $file = __DIR__ . '/../classes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// ============================================
// Pomocné funkcie (helper functions)
// ============================================

/**
 * Bezpečný výstup textu do HTML – zabraňuje XSS útokom.
 * Použitie: echo h($premenná);  namiesto echo $premenná;
 */
function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Presmeruje na danú URL a ukončí skript.
 * Použitie: redirect('index.php');
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Nastaví flash správu (jednorazová správa po presmerovaní).
 * Použitie: setFlash('success', 'Remeselník bol pridaný!');
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Zobrazí a zmaže flash správu.
 * Volaj v HTML časti stránky.
 */
function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Spracuje nahrávanie fotky.
 * Vráti názov súboru alebo null ak sa nič nenahralo.
 * Hádže výnimku pri chybe.
 */
function uploadFotka(array $file, ?string $stara = null): ?string
{
    // Ak nebol vybraný súbor, vráť null
    if ($file['error'] === UPLOAD_ERR_NO_FILE || $file['size'] === 0) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Chyba pri nahrávaní súboru.');
    }

    // Povolené typy súborov
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $mime    = mime_content_type($file['tmp_name']);

    if (!in_array($mime, $allowed, true)) {
        throw new RuntimeException('Povolené sú len obrázky (JPG, PNG, WebP, GIF).');
    }

    // Maximálna veľkosť: 2 MB
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Obrázok je príliš veľký (max 2 MB).');
    }

    // Vygeneruj unikátny názov súboru
    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nazov    = uniqid('foto_', true) . '.' . strtolower($ext);
    $ciel     = UPLOAD_DIR . $nazov;

    if (!move_uploaded_file($file['tmp_name'], $ciel)) {
        throw new RuntimeException('Nepodarilo sa uložiť obrázok.');
    }

    // Zmaž starú fotku ak existuje
    if ($stara && file_exists(UPLOAD_DIR . $stara)) {
        unlink(UPLOAD_DIR . $stara);
    }

    return $nazov;
}
