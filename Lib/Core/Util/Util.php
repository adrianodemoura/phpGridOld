<?php
/**
 * Debug
 */
function debug($d)
{
	$t = debug_backtrace();
	echo '<pre>';
	echo $t['0']['file'].' ('.$t['0']['line'].")\n";
	echo print_r($d,true);
	echo '</pre>';
}


/**
 * inicia automatica uma classe que esteja dentro do include_path
 */
function __autoload($class_name) 
{
    require_once $class_name . '.php';
}

/**
 * Retorna a url do sistema
 * 
 * @return	string	$base 	Url do Sistema
 */
function getBase()
{
	$base = '';
	$base = isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:'http';
	$base .= '://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	$base = str_replace('webroot/','',$base);
	$base = str_replace('index.php','',$base);
	return $base;
}

/**
 * Executa um redirecionamento
 * 
 * @param	string	$rota		url que vai receber o redirecionamento
 * @param	array	$destino	url destino do redirecionamento
 * @param	array	$params		Url com parâmetros do redirecionamento, aqui se pode colocar algo como /pag:1/ordem:nome/dire:asc e etc ...
 * @return 	void
 */
function router($rota='', $destino=array(), $params=array())
{
	$base 	= getBase();
	$_aqui 	= explode('/',$base);
	$aqui 	= $_SERVER['REQUEST_URI'];
	foreach($_aqui as $_l => $_url) if (!empty($_url)) $aqui = str_replace('/'.$_url,'',$aqui);
	$redir	= ($rota==$aqui) ? true : false;
	if ($redir)
	{
		$url = '';
		foreach($destino as $_l => $_url)
		{
			if ($_l) $url .= '/';
			$url .= $_url;
		}
		if ($rota!=$url)
		{
			if (!empty($params))
			{
				foreach($params as $_tag => $_vlr)
				{
					$url .= "/$_tag:$_vlr";
				}
			}
			header('Location: '.$base.$url);
			die();
		}
	}
}

/**
 * inclui na aplicação o tipo de objeto conforme o tipo
 * 
 * @param 	string	$tipo	Tipo a ser incluído
 * @param	string	$class	Classe a ser incluída
 * @return	void
 */
function appUses($tipo='',$class='')
{
	$class = ucfirst($class);
	switch(strtolower($tipo))
	{
		case 'component':
			require_once('Controller/Component/'.$class.'Component.php');
			break;
		case 'model':
			require_once('Model/'.$class.'.php');
			break;
		case 'controller':
			require_once('Controller/'.$class.'Controller.php');
			break;
		case 'cache':
			require_once(CORE.'Cache/'.$class.'.php');
			break;
	}
}
