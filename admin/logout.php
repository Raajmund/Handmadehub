<?php
// ============================================
// admin/logout.php – Odhlásenie admina
// ============================================
require_once '../includes/bootstrap.php';

$db   = Database::getConnection();
$auth = new Auth($db);

// Odhlás admina (zruší session)
$auth->logout();

// Presmeruj na login stránku
setFlash('success', 'Bol si odhlásený.');
redirect(BASE_URL . '/admin/login.php');
