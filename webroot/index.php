<?php
/**
 * Arquivo index do sistema
 * 
 */
	// iniciando o cronômetro
	define('INICIO',microtime(true));

	// constantes locais
	define('APP','../');
	define('CORE','../Lib/Core/');

	// incluindo a view de saída
	require_once(CORE.'Boot.php');
	$Boot = new Boot();

	// saída
	echo $Boot->render();
