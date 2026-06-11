<?php
require_once 'includes/bootstrap.php';

$db    = Database::getConnection();
$model = new Remeslar($db);

$query = isset($_GET['q'])     ? trim($_GET['q'])     : '';
$odbor = isset($_GET['odbor']) ? trim($_GET['odbor']) : '';

if ($query !== '') {
    $remeslari = $model->search($query);
} elseif ($odbor !== '') {
    $remeslari = $model->getByOdbor($odbor);
} else {
    $remeslari = $model->getAll();
}

$odbory = $model->getOdbory();
$flash  = getFlash();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Portál Remeselníkov – Remeselníci</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.search-form { margin-bottom: 10px; }
.search-input { padding: 4px 8px; border: 1px solid #c8b89a; background: #faf7f0; font-family: Georgia, serif; font-size: 12px; color: #494234; width: 240px; }
.filter-select { padding: 4px 6px; border: 1px solid #c8b89a; background: #faf7f0; font-family: Georgia, serif; font-size: 12px; color: #494234; margin: 0 5px; }
.btn-submit { padding: 4px 14px; background: #494234; color: #f5e6c8; border: none; cursor: pointer; font-family: Georgia, serif; font-size: 12px; font-style: italic; }
.pocet { font-size: 16px; font-style: normal; color: #947c58; }
.remeslar-riadok { clear: both; padding-bottom: 15px; margin-bottom: 15px; border-bottom: 1px dotted #c8b89a; }
.remeslar-riadok img { float: left; width: 100px; height: 85px; object-fit: cover; margin-right: 15px; margin-top: 3px; }
.remeslar-riadok h4 { margin: 0 0 4px 0; padding: 0; font-size: 14px; color: #2f291d; }
.odbor-tag { display: inline-block; padding: 1px 8px; background: #947c58; color: #f5e6c8; font-size: 11px; font-style: italic; margin-bottom: 5px; }
.remeslar-info { overflow: hidden; }
</style>
</head>
<body>
<div id="templatemo_container">
	<div id="bulb"></div>
	<div id="templatemo_site_title_bar">	
        <div id="site_title">
            <h1>
                <a href="index.php">PORTÁL REMESELNÍKOV</a>
                <span>Objavte lokálnych majstrov a hand-made tvorcov vo vašom okolí</span>
            </h1>
        </div>
    </div>
    
    <div id="templatemo_menu">
    	<ul>
            <li><a href="index.php">Domov</a></li>
            <li><a href="remeslari.php" class="current fast">Remeselníci</a></li>
            <li><a href="admin/login.php">Admin</a></li>
        </ul>
    </div>
   	
    <div id="templatemo_content">

        <?php if ($flash): ?>
        <div class="section_w670">
            <p style="padding:8px 12px; background:#f0faf0; border-left:3px solid #4caf50; color:#2e7d32; margin-bottom:15px;">
                <?php echo h($flash['message']); ?>
            </p>
        </div>
        <?php endif; ?>

        <div class="section_w670">
        	<h2>Nájdi remeselníka</h2>
            <form method="get" action="remeslari.php" class="search-form">
                <input type="text" name="q" value="<?php echo h($query); ?>" placeholder="Meno, mesto, odbor..." class="search-input" />
                <select name="odbor" class="filter-select">
                    <option value="">– Všetky odbory –</option>
                    <?php foreach ($odbory as $o): ?>
                        <option value="<?php echo h($o); ?>" <?php echo ($odbor===$o?'selected="selected"':''); ?>>
                            <?php echo h($o); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Hľadaj" class="btn-submit" />
                <?php if ($query || $odbor): ?>
                    &nbsp;<a href="remeslari.php">Zrušiť filter</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="cleaner_h40"></div>

        <div class="section_w670">
        	<h2>Remeselníci <span class="pocet">(<?php echo count($remeslari); ?>)</span></h2>

            <?php if (empty($remeslari)): ?>
                <p>Žiadni remeselníci nenájdení.</p>
            <?php else: ?>
                <?php foreach ($remeslari as $r): ?>
                <div class="remeslar-riadok">
                    <?php if ($r['fotka'] && file_exists('images/' . $r['fotka'])): ?>
                        <img src="images/<?php echo h($r['fotka']); ?>" alt="<?php echo h($r['meno']); ?>" />
                    <?php else: ?>
                        <img src="images/templatemo_image_01.png" alt="<?php echo h($r['meno']); ?>" />
                    <?php endif; ?>
                    <div class="remeslar-info">
                        <h4><?php echo h($r['meno']); ?></h4>
                        <span class="odbor-tag"><?php echo h($r['odbor']); ?></span>
                        <p><?php echo h($r['mesto']); ?><?php if($r['telefon']): ?> &nbsp;|&nbsp; <?php echo h($r['telefon']); ?><?php endif; ?></p>
                        <?php if ($r['popis']): ?>
                            <p><?php echo h(mb_substr($r['popis'], 0, 120)); ?>...</p>
                        <?php endif; ?>
                        <?php if ($r['email']): ?>
                            <p><a href="mailto:<?php echo h($r['email']); ?>"><?php echo h($r['email']); ?></a></p>
                        <?php endif; ?>
                    </div>
                    <div class="cleaner"></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

		<div class="cleaner"></div>
  </div>
  
<div id="content_bottom"></div>
    <div id="templatemo_footer">
  		<ul class="footer_menu">
            <li><a href="index.php">Domov</a></li>
            <li><a href="remeslari.php">Remeselníci</a></li>
            <li class="last_menu"><a href="admin/login.php">Admin</a></li>
        </ul>
        Copyright &copy; <?php echo date('Y'); ?> <a href="#">Portál Remeselníkov</a>
	</div>
	<div class="cleaner"></div>
</div>
</body>
</html>
