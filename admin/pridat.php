<?php
require_once '../includes/bootstrap.php';

$db    = Database::getConnection();
$auth  = new Auth($db);
$model = new Remeslar($db);

$auth->requireLogin();

$chyby = [];
$data  = ['meno'=>'','email'=>'','telefon'=>'','mesto'=>'','odbor'=>'','popis'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'meno'    => $_POST['meno']    ?? '',
        'email'   => $_POST['email']   ?? '',
        'telefon' => $_POST['telefon'] ?? '',
        'mesto'   => $_POST['mesto']   ?? '',
        'odbor'   => $_POST['odbor']   ?? '',
        'popis'   => $_POST['popis']   ?? '',
    ];

    if (trim($data['meno'])  === '') $chyby[] = 'Meno je povinné.';
    if (trim($data['email']) === '') $chyby[] = 'Email je povinný.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $chyby[] = 'Email nie je platný.';
    if (trim($data['mesto']) === '') $chyby[] = 'Mesto je povinné.';
    if (trim($data['odbor']) === '') $chyby[] = 'Odbor je povinný.';

    if (empty($chyby)) {
        try {
            $fotka = uploadFotka($_FILES['fotka'] ?? []);
            $data['fotka'] = $fotka;
            $model->create($data);
            setFlash('success', 'Remeselník bol úspešne pridaný!');
            redirect(BASE_URL . '/admin/index.php');
        } catch (RuntimeException $e) {
            $chyby[] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pridať remeselníka</title>
<link href="../css/templatemo_style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.form-group { margin-bottom: 14px; }
.form-group label { display: block; font-weight: bold; font-size: 12px; margin-bottom: 4px; }
.form-group input[type="text"], .form-group input[type="email"], .form-group input[type="tel"], .form-group textarea { width: 380px; padding: 6px 8px; border: 1px solid #c8b89a; background: #faf7f0; font-family: Georgia, serif; font-size: 12px; color: #3a3228; }
.form-group textarea { resize: vertical; width: 380px; }
.btn-ulozit { padding: 6px 20px; background: #494234; color: #f5e6c8; border: none; cursor: pointer; font-family: Georgia, serif; font-size: 12px; font-style: italic; }
.chyby { padding: 8px 12px; background: #fff5f5; border-left: 3px solid #c0392b; color: #8b1a1a; margin-bottom: 15px; font-size: 12px; }
.required { color: #c0392b; }
</style>
</head>
<body>
<div id="templatemo_container">
	<div id="bulb"></div>
	<div id="templatemo_site_title_bar">	
        <div id="site_title">
            <h1>
                <a href="../index.php">PORTÁL REMESELNÍKOV</a>
                <span>Admin – pridanie remeselníka</span>
            </h1>
        </div>
    </div>
    <div id="templatemo_menu">
    	<ul>
            <li><a href="index.php">← Späť</a></li>
            <li><a href="logout.php">Odhlásiť</a></li>
        </ul>
    </div>
    <div id="templatemo_content">
        <div class="section_w670">
        	<h2>Pridať remeselníka</h2>

            <?php if (!empty($chyby)): ?>
                <div class="chyby"><ul><?php foreach($chyby as $ch): ?><li><?php echo h($ch); ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>

            <form method="post" action="pridat.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Meno a priezvisko: <span class="required">*</span></label>
                    <input type="text" name="meno" value="<?php echo h($data['meno']); ?>" />
                </div>
                <div class="form-group">
                    <label>Email: <span class="required">*</span></label>
                    <input type="email" name="email" value="<?php echo h($data['email']); ?>" />
                </div>
                <div class="form-group">
                    <label>Telefón:</label>
                    <input type="tel" name="telefon" value="<?php echo h($data['telefon']); ?>" />
                </div>
                <div class="form-group">
                    <label>Mesto: <span class="required">*</span></label>
                    <input type="text" name="mesto" value="<?php echo h($data['mesto']); ?>" />
                </div>
                <div class="form-group">
                    <label>Odbor / remeslo: <span class="required">*</span></label>
                    <input type="text" name="odbor" value="<?php echo h($data['odbor']); ?>" placeholder="napr. Hrnčiarstvo, Tkáčstvo..." />
                </div>
                <div class="form-group">
                    <label>Popis:</label>
                    <textarea name="popis" rows="5"><?php echo h($data['popis']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Fotka (JPG/PNG, max 2 MB):</label>
                    <input type="file" name="fotka" accept="image/*" />
                </div>
                <input type="submit" value="Pridať remeselníka" class="btn-ulozit" />
                &nbsp;<a href="index.php">Zrušiť</a>
            </form>
        </div>
        <div class="cleaner"></div>
    </div>
<div id="content_bottom"></div>
    <div id="templatemo_footer">
        <ul class="footer_menu"><li class="last_menu"><a href="index.php">← Späť</a></li></ul>
        Copyright &copy; <?php echo date('Y'); ?> Portál Remeselníkov
    </div>
    <div class="cleaner"></div>
</div>
</body>
</html>
