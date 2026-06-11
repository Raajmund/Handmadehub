<?php
define('DB_HOST',     'localhost');   
define('DB_NAME',     'remeslari');   
define('DB_USER',     'root');        
define('DB_PASS',     '');            
define('DB_CHARSET',  'utf8mb4');

define('BASE_URL', 'http://localhost/remeslari');

define('UPLOAD_DIR', __DIR__ . '/../images/');
define('UPLOAD_URL', BASE_URL . '/images/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>