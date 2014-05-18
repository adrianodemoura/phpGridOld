<?php
/**
 * Arquivo index do sistema
 * 
 */
	// constantes locais
	define('APP','../');
	define('CORE','../Lib/Core/');
	define('VENDOR','../Vendor/');

	// incluindo a view de saída
	require_once(CORE.'Boot.php');
	$Boot = new Boot();

	// saída
	echo $Boot->render();
