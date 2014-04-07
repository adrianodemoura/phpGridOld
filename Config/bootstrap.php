<?php
	// configurando o ambiente
	define('AMBIENTE','DESENVOLVIMENTO');
	//define('AMBIENTE','HOMOLOGAÇÃO');
	//define('AMBIENTE','PRODUÇÃO');

	// sistema
	define('SISTEMA','phpGrid');

	// salt
	define('SALT','GoiAbab0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');

	// nome_sessão
	define('SESSAO','F2a3l5a6S8i9l0v578ioOiL7o8m345ba5r6d7e');

	// incluindo o diretório da APP no path
	set_include_path(get_include_path() . PATH_SEPARATOR . APP);

	// incluido bootstrap do core
	require_once(CORE.'Config/bootstrap.php');

	// definindo rota raiz 
	router('/',array('sistema','usuarios','index'));
