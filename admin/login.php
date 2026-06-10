<?php
require_once '../includes/bootstrap.php';

$db   = Database::getConnection();
$auth = new Auth($db);

if ($auth->isLoggedIn()) {
    redirect(BASE_URL . '/admin/index.php');
}

$chyba = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $chyba = 'Vyplň meno aj heslo.';
    } elseif ($auth->login($username, $password)) {
        setFlash('success', 'Vitaj, ' . $username . '!');
        redirect(BASE_URL . '/admin/index.php');
    } else {
        $chyba = 'Nesprávne meno alebo heslo.';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin – Prihlásenie</title>
<link href="../css/templatemo_style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.form-group { margin-bottom: 14px; }
.form-group label { display: block; font-weight: bold; font-size: 12px; margin-bottom: 4px; }
.form-group input { width: 280px; padding: 6px 8px; border: 1px solid #c8b89a; background: #faf7f0; font-family: Georgia, serif; font-size: 12px; color: #3a3228; }
.btn-prihlasit { padding: 6px 20px; background: #494234; color: #f5e6c8; border: none; cursor: pointer; font-family: Georgia, serif; font-size: 12px; font-style: italic; }
.chyba { padding: 8px 12px; background: #fff5f5; border-left: 3px solid #c0392b; color: #8b1a1a; margin-bottom: 15px; font-size: 12px; }
</style>
</head>
<body>
<div id="templatemo_container">
	<div id="bulb"></div>
	<div id="templatemo_site_title_bar">	
        <div id="site_title">
            <h1>
                <a href="../index.php">PORTÁL REMESELNÍKOV</a>
                <span>Administrácia</span>
            </h1>
        </div>
    </div>
    
    <div id="templatemo_menu">
    	<ul>
            <li><a href="../index.php">← Späť na web</a></li>
        </ul>
    </div>
   	
    <div id="templatemo_content">
        <div class="section_w670">
        	<h2>Prihlásenie do adminu</h2>

            <?php if ($chyba): ?>
                <div class="chyba"><?php echo h($chyba); ?></div>
            <?php endif; ?>

            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="username">Používateľské meno:</label>
                    <input type="text" id="username" name="username" value="<?php echo h($_POST['username'] ?? ''); ?>" />
                </div>
                <div class="form-group">
                    <label for="password">Heslo:</label>
                    <input type="password" id="password" name="password" />
                </div>
                <input type="submit" value="Prihlásiť sa" class="btn-prihlasit" />
            </form>
        </div>
        <div class="cleaner"></div>
    </div>

<div id="content_bottom"></div>
    <div id="templatemo_footer">
        <ul class="footer_menu">
            <li class="last_menu"><a href="../index.php">Domov</a></li>
        </ul>
        Copyright &copy; <?php echo date('Y'); ?> Portál Remeselníkov
    </div>
    <div class="cleaner"></div>
</div>
</body>
</html>
