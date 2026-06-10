<?php
// ============================================
// includes/config.php
// Nastavenia databázy – UPRAV podľa svojho servera
// ============================================

define('DB_HOST',     'localhost');   // adresa MySQL servera
define('DB_NAME',     'remeslari');   // názov databázy
define('DB_USER',     'root');        // MySQL používateľ (napr. 'root' na lokále)
define('DB_PASS',     '');            // MySQL heslo (na lokále zvyčajne prázdne)
define('DB_CHARSET',  'utf8mb4');

// Základná URL tvojho webu (bez lomky na konci)
define('BASE_URL', 'http://localhost/remeslari');

// Cesta k priečinku s fotkami
define('UPLOAD_DIR', __DIR__ . '/../images/');
define('UPLOAD_URL', BASE_URL . '/images/');

// Spustí session, ak ešte nebeží
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
