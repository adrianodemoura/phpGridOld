<?php
/**
 *
 */
	// iniciando o cronômetro
	define('INICIO',microtime(true));

	// configurando o ambiente
	define('AMBIENTE','DESENVOLVIMENTO');
	//define('AMBIENTE','HOMOLOGAÇÃO');
	//define('AMBIENTE','PRODUÇÃO');

	// constantes locais
	define('APP','../');
	define('CORE','../Lib/Core/');

	// incluindo a view de saída
	require_once(CORE.'Boot.php');
	$Boot = new Boot();

	// saída
	echo $Boot->render();
