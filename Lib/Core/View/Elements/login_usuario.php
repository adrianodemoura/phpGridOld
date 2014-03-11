<?php 
	$ip = (strlen($_SERVER['SERVER_ADDR'])>4) ? $_SERVER['SERVER_ADDR'] : $_SERVER['REMOTE_ADDR'];
	if ($ip=='::1') $ip = '127.0.0.1';
	if (isset($_SESSION['Usuario'])) : ?>
	<a href='<?= $base.strtolower($module) ?>/usuarios/info'><?= $_SESSION['Usuario']['nome'] ?></a>
	| Seu IP: <?= $ip; ?>
	| <a href='<?= $base.strtolower($module) ?>/usuarios/sair'>sair</a>
<?php else : ?>
	<a href='<?= $base.strtolower($module) ?>/usuarios/login'>login</a>
<?php endif ?>
