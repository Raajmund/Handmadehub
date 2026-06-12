<?php
require_once '../includes/bootstrap.php';

$db   = Database::getConnection();
$auth = new Auth($db);

$auth->logout();

setFlash('success', 'Bol si odhlásený.');
redirect(BASE_URL . '/admin/login.php');
