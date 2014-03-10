<?php
/**
 *
 */
	header('Content-Type: text/html; charset=utf-8');

	// iniciando sessão
	/*ini_set("session.gc_maxlifetime", 4);
	session_cache_limiter('private');
	session_cache_expire(4);
	session_start();*/
	//session_set_cookie_params('10');	//10 seconds 
	//session_cache_expire(1);

	// fuso-horário
	date_default_timezone_set('America/Sao_Paulo');

	// iniciando o cronômetro
	define('INICIO',microtime(true));

	// exibindo todos os erros
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// constantes locais
	define('APP','../');
	define('CORE','../Lib/Core/');
	//define('CORE','/home/Core/');

	// incluindo bootstrap
	require_once(APP.'Config/bootstrap.php');

	// sessão
	session_name(SESSAO); 
	session_start();
	
	// incluindo a view de saída
	require_once(CORE.'Boot.php');
	$Boot = new Boot();

	// saída
	echo $Boot->render();
