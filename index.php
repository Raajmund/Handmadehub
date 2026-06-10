<?php
require_once 'includes/bootstrap.php';
$flash = getFlash();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Portál Remeselníkov</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
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
            <li><a href="index.php" class="current fast">Domov</a></li>
            <li><a href="remeslari.php">Remeselníci</a></li>
            <li><a href="admin/login.php">Admin</a></li>
        </ul>
    </div>
   	
    <div id="templatemo_content"> 

        <?php if ($flash): ?>
        <div class="section_w670">
            <p style="padding:8px 12px; background:#f0faf0; border-left:3px solid #4caf50; color:#2e7d32;">
                <?php echo h($flash['message']); ?>
            </p>
        </div>
        <div class="cleaner_h20"></div>
        <?php endif; ?>

        <div class="section_w670">
        	<h2>Vitajte na portáli remeselníkov!</h2>
            <p>Tento portál slúži ako miesto, kde môžete objaviť lokálnych remeselníkov a hand-made tvorcov vo vašom okolí. Či hľadáte hrnčiara, tkáča, krajčíra alebo iného majstra svojho remesla – tu ich nájdete.</p>
            <p>Remeselná výroba je súčasťou našej kultúrnej identity. Podporujme lokálnych tvorcov a tradičné remeslá, ktoré sa odovzdávajú z generácie na generáciu.</p>
        </div>
        
        <div class="cleaner_h40"></div>
		
        <div class="section_w670">
        	<h2>Čo tu nájdete?</h2>
            
			<a href="remeslari.php"><img class="image_wrapper fl_image" src="images/templatemo_image_01.png" alt="Remeselníci" /></a>

			<p>Na našom portáli nájdete zoznam remeselníkov z celého Slovenska. Každý remeselník má svoj profil s kontaktnými údajmi, odborom a popisom svojej činnosti.</p>
			<p>Môžete vyhľadávať podľa mena, mesta alebo odboru. Chcete pridať svojho remeselníka? Kontaktujte nás cez administráciu.</p>
			<p><a href="remeslari.php">→ Pozrieť všetkých remeselníkov</a></p>
			<div class="cleaner"></div>
      </div>
        
        <div class="cleaner_h40"></div>
        
        <div class="section_w670">
        	
            <div class="section_w330 fl">
            	
                <div class="section_w330_content">
            	
                    <h2>Naše služby</h2>
                    
                    <ul class="list_01">
                        <li><a href="remeslari.php">Zoznam remeselníkov</a></li>
                        <li><a href="remeslari.php?odbor=Hrnčiarstvo">Hrnčiarstvo</a></li>
                        <li><a href="remeslari.php?odbor=Tkáčstvo">Tkáčstvo</a></li>
                        <li><a href="remeslari.php?odbor=Krajčírstvo">Krajčírstvo</a></li>
                        <li><a href="remeslari.php">Všetky odbory</a></li>
                    </ul>

                </div>
                
            </div>
            
            <div class="section_w330 contact_section fr">
                <h2>Kontakt</h2>
          		<p>
                    Tel: 010-100-1000<br />
                    Email: info@remeslari.sk<br />
                    Web: <a href="index.php">www.remeslari.sk</a>
                </p>
            </div>
            	
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
