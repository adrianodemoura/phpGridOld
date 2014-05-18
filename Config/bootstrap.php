<?php
	// configurando o ambiente
	define('AMBIENTE','DESENVOLVIMENTO');
	//define('AMBIENTE','HOMOLOGAÇÃO');
	//define('AMBIENTE','PRODUÇÃO');

	// sistema
	define('SISTEMA','phpGrid');

	// salt
	define('SALT','GoiAbab0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');

	// incluindo o diretório da APP no path
	set_include_path(get_include_path() . PATH_SEPARATOR . APP);

	// incluido bootstrap do core
	require_once(CORE.'Config/bootstrap.php');

	// definindo rota raiz 
	router('/',array('sistema','usuarios','listar'));
