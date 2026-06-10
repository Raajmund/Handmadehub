<?php
require_once '../includes/bootstrap.php';

$db    = Database::getConnection();
$auth  = new Auth($db);
$model = new Remeslar($db);

$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $fotka = $model->delete($id);
        if ($fotka && file_exists(UPLOAD_DIR . $fotka)) {
            unlink(UPLOAD_DIR . $fotka);
        }
        setFlash('success', 'Remeselník bol zmazaný.');
    }
    redirect(BASE_URL . '/admin/index.php');
}

$remeslari = $model->getAll();
$flash     = getFlash();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin – Remeselníci</title>
<link href="../css/templatemo_style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.tabulka { width: 100%; border-collapse: collapse; font-size: 12px; }
.tabulka th { background: #494234; color: #f5e6c8; padding: 7px 10px; text-align: left; font-style: italic; font-weight: normal; }
.tabulka td { padding: 7px 10px; border-bottom: 1px dotted #c8b89a; vertical-align: middle; }
.tabulka tr:hover td { background: #faf7f0; }
.btn-edit { padding: 3px 10px; background: #947c58; color: #fff; font-size: 11px; text-decoration: none; margin-right: 4px; }
.btn-delete { padding: 3px 10px; background: #8b3a2a; color: #fff; border: none; cursor: pointer; font-size: 11px; font-family: Georgia, serif; }
.btn-pridat { display: inline-block; padding: 6px 18px; background: #494234; color: #f5e6c8; font-family: Georgia, serif; font-size: 12px; font-style: italic; text-decoration: none; margin-bottom: 15px; }
.sprava { padding: 8px 12px; background: #f0faf0; border-left: 3px solid #4caf50; color: #2e7d32; margin-bottom: 15px; font-size: 12px; }
</style>
</head>
<body>
<div id="templatemo_container">
	<div id="bulb"></div>
	<div id="templatemo_site_title_bar">	
        <div id="site_title">
            <h1>
                <a href="../index.php">PORTÁL REMESELNÍKOV</a>
                <span>Admin – prihlásený: <?php echo h($_SESSION['admin_username']); ?></span>
            </h1>
        </div>
    </div>
    
    <div id="templatemo_menu">
    	<ul>
            <li><a href="../index.php">Web</a></li>
            <li><a href="index.php" class="current">Remeselníci</a></li>
            <li><a href="pridat.php">+ Pridať</a></li>
            <li><a href="logout.php">Odhlásiť</a></li>
        </ul>
    </div>
   	
    <div id="templatemo_content">
        <div class="section_w670">
        	<h2>Správa remeselníkov</h2>

            <?php if ($flash): ?>
                <div class="sprava"><?php echo h($flash['message']); ?></div>
            <?php endif; ?>

            <a href="pridat.php" class="btn-pridat">+ Pridať remeselníka</a>

            <?php if (empty($remeslari)): ?>
                <p>Žiadni remeselníci. <a href="pridat.php">Pridaj prvého.</a></p>
            <?php else: ?>
                <table class="tabulka">
                    <thead>
                        <tr>
                            <th>Meno</th>
                            <th>Odbor</th>
                            <th>Mesto</th>
                            <th>Email</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($remeslari as $r): ?>
                        <tr>
                            <td><?php echo h($r['meno']); ?></td>
                            <td><?php echo h($r['odbor']); ?></td>
                            <td><?php echo h($r['mesto']); ?></td>
                            <td><?php echo h($r['email']); ?></td>
                            <td style="white-space:nowrap;">
                                <a href="editovat.php?id=<?php echo $r['id']; ?>" class="btn-edit">Editovať</a>
                                <form method="post" action="index.php" style="display:inline;" onsubmit="return confirm('Naozaj zmazať <?php echo h(addslashes($r['meno'])); ?>?')">
                                    <input type="hidden" name="action" value="delete" />
                                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>" />
                                    <button type="submit" class="btn-delete">Zmazať</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="cleaner"></div>
    </div>

<div id="content_bottom"></div>
    <div id="templatemo_footer">
        <ul class="footer_menu">
            <li><a href="../index.php">Web</a></li>
            <li class="last_menu"><a href="logout.php">Odhlásiť</a></li>
        </ul>
        Copyright &copy; <?php echo date('Y'); ?> Portál Remeselníkov
    </div>
    <div class="cleaner"></div>
</div>
</body>
</html>
