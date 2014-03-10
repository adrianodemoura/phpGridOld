<?php
/**
 * Class Boot
 * 
 * @package		Core
 * @subpackage	Core.Boot
 */
class Boot {
	/**
	 * Variáveis da view
	 * 
	 * @var		array
	 * @access	public
	 */
	public $viewVars = array();

	/**
	 * Incrementa o Head do layout
	 * 
	 * o nome do arquivo será incrementado com o nome do controller
	 * 
	 * @param	string	$tipo	Tipo da tab head
	 * @param	string	$t		Nome do arquivo CSS ou JS
	 * @return	void
	 */
	public function head($tipo='', $t='')
	{
		switch($tipo)
		{
			case 'css':
				$link = htmlentities('<link rel="stylesheet" type="text/css" href="'.$this->viewVars['base'].'css/'.strtolower(strtolower($this->viewVars['module'])).'_'.strtolower(strtolower($this->viewVars['controller'])).'_'.$t.'.css" />');
				break;
			case 'js':
				$link = htmlentities('<script type="text/javascript" src="'.$this->viewVars['base'].'js/'.strtolower(strtolower($this->viewVars['module'])).'_'.strtolower(strtolower($this->viewVars['controller'])).'_'.$t.'.js"></script>');
				break;
			case 'texto':
				$link = $t;
				break;
		}
		if (isset($link))
		{
			array_push($this->viewVars['head'],$link);
		}
	}

	/**
	 * Renderiza o layout a view solicitada via GET
	 * 
	 * @access	public
	 * @return	void
	 */
	public function render()
	{
		// configurando, módulo, controler, action e parâmetros
		$_url 	= explode('/',$_SERVER['REQUEST_URI']);
		$url 	= array();
		$params	= array();
		$module	= '';
		$l		= 0;
		foreach($_url as $_l => $_u)
		{
			if (!empty($_u)) if (!strpos($_SERVER['SCRIPT_FILENAME'],$_u) || $_u=='index')
			{
				$url[$l] = $_u;
				$l++;
			}
		}
		$url['0'] = isset($url['0']) ? ucfirst(strtolower($url['0'])) : 'Sistema';
		$url['1'] = isset($url['1']) ? ucfirst(strtolower($url['1'])) : 'Usuarios';
		$url['2'] = isset($url['2']) ? ucfirst(strtolower($url['2'])) : 'index';
		$module		= $url['0'];
		$controller = $url['1'];
		$action		= $url['2'];

		// identificando os parâmetros
		if (isset($url['3'])) 
		{
			foreach($url as $_l => $_tag)
			{
				if ($_l>=3)
				{
					$arrTag = explode(':',$_tag);
					$params[$arrTag['0']] = $arrTag['1'];
				}
			}
		} 

		// identificando o controller
		$arq = 'Modules/'.$module.'/Config/bootstrap.php';
		if (!file_exists(APP.$arq))
		{
			$url = getBase().'sistema/usuarios/erros';
			$_SESSION['sistemaErro']['tip'] = 'module';
			$_SESSION['sistemaErro']['txt'] = 'Não foi possível localizar o seguinte arquivo: <br /><br />'.$arq;
			die('<script>document.location.href="'.$url.'"</script>');
		} else
		{
			require_once(APP.$arq);
		}
		$arq = 'Modules/'.$module.'/Controller/'.$controller.'Controller.php';
		if (!include_once($arq))
		{
			$url = getBase().'sistema/usuarios/erros';
			$_SESSION['sistemaErro']['tip'] = 'controller';
			$_SESSION['sistemaErro']['txt'] = 'Não foi possível localizar o Controller <b>'.$controller.'</b> do módulo <b>'.$module.'</b>: <br /><br />'.$arq;
			die('<script>document.location.href="'.$url.'"</script>');
		}
		$_controller = $controller.'Controller';
		$this->$controller = new $_controller();
		$this->$controller->viewVars['params'] = $params;

		// atulizando alguns atributos do controller
		$this->$controller->module 				= $module;
		$this->$controller->controller			= $controller;
		$this->$controller->action				= $action;
		$this->$controller->view				= strtolower($action);
		$this->$controller->viewPath			= $controller;
		$this->$controller->base				= $this->$controller->viewVars['base'];
		$this->$controller->params				= $params;

		// letra de separação
		$this->$controller->viewVars['se'] = !empty($this->$controller->viewVars['se']) ? $this->$controller->viewVars['se'] : ':';

		// recuperando o data
		$this->$controller->data = isset($_POST['data']) ? $_POST['data'] : array();

		// executando código antes de tudo
		$this->$controller->beforeIndex();

		// instanciando o helper html
		include_once('View/Helpers/Html.php');
		$this->Html = new Html();
		$this->Html->controller = $controller;
		$this->Html->base		= $this->$controller->base;

		// model
		if (count($this->$controller->Model))
		{
			foreach($this->$controller->Model as $_mod)
			{
				include_once('Modules/'.$module.'/Model/'.ucfirst(strtolower($_mod)).'.php');
				$this->$controller->$_mod 					= new $_mod();
				$this->$controller->modelClass 				= $_mod;
				$this->$controller->viewVars['modelClass'] 	= $_mod;
			}
		}

		// executando a action
		if (!method_exists($this->$controller,$action))
		{
			$url = getBase().'sistema/usuarios/erros';
			$_SESSION['sistemaErro']['tip'] = 'action';
			$_SESSION['sistemaErro']['txt'] = 'Não foi possível localizar a Action <b>'.$action.'</b> do Controller <b>'.$controller.'</b> do módulo <b>'.$module.'</b>: <br />';
			die('<script>document.location.href="'.$url.'"</script>');
		}
		$this->$controller->$action();

		// identificando a posição
		$this->$controller->viewVars['position'] = "<a href='".$this->$controller->viewVars['base'].strtolower($module).'/'.strtolower($controller)."/index'>".((!empty($this->$controller->viewVars['tituloModule'])) 		? $this->$controller->viewVars['tituloModule'] 		: $module)."</a>";
		$this->$controller->viewVars['position'] .= " :: <a href='".$this->$controller->viewVars['base'].strtolower($module).'/'.strtolower($controller)."/index'>".((!empty($this->$controller->viewVars['tituloController'])) 	? $this->$controller->viewVars['tituloController'] 	: $controller)."</a>";
		$a = isset($this->$controller->viewVars['tituloAction']) ? $this->$controller->viewVars['tituloAction'] : $action;
		if (!empty($a)) $this->$controller->viewVars['position'] .= " :: <a href='".$this->$controller->viewVars['aqui']."'>$a</a>";
		$this->$controller->viewVars['position'] = htmlentities($this->$controller->viewVars['position']);
		unset($a);

		// atualizando viewVars do controller com algumas informações do model
		if (count($this->$controller->Model))
		{
			foreach($this->$controller->Model as $_mod)
			{
				$this->$controller->viewVars['sql_dump'] 	= $this->$controller->$_mod->sqls;
				$this->$controller->viewVars['primaryKey'] 	= $this->$controller->$_mod->primaryKey;
				if (isset($this->$controller->$_mod->pag)) $this->$controller->viewVars['paginacao'] 	= $this->$controller->$_mod->pag;
				if (isset($this->$controller->$_mod->esquema))
				{
					$this->$controller->viewVars['esquema'][$_mod] = $this->$controller->$_mod->esquema;
					if (isset($this->$controller->$_mod->outrosEsquemas))
					{
						foreach($this->$controller->$_mod->outrosEsquemas as $_mod2 => $_esquema2)
						{
							$this->$controller->viewVars['esquema'][$_mod2] = $_esquema2;
						}
					}
				}
			}
		}

		// atualizando as variáveis locais
		$this->data		= $this->$controller->data;
		$this->viewVars = $this->$controller->viewVars;
		foreach($this->viewVars as $_var => $_vlr) if (!in_array($_var,array('controller','action','module'))) ${$_var} = $_vlr;
		$this->viewVars['module']	 	= $module;
		$this->viewVars['controller'] 	= $controller;
		$this->viewVars['action'] 		= $action;

		// executando código antes da renderização e depois da action
		$this->$controller->beforeRender();

		// salvando dados da view e dando adeus ao controller, ele não vai pra view
		$viewPath 		= $this->$controller->viewPath;
		$view			= $this->$controller->view;
		$layout			= $this->$controller->layout;
		unset($this->$controller);

		// verificando a mensagen flash
		if (isset($_SESSION['msgFlash']))
		{
			$msgFlash = $_SESSION['msgFlash'];
			unset($_SESSION['msgFlash']);
		}

		// incluindo o conteúdo
		$conteudo = '';
		$arq = 'Modules/'.$module.'/View/'.$viewPath.'/'.$view.'.php';
		if (!file_exists(APP.$arq))
		{
			$arq = CORE.'View/Scaffolds/'.$view.'.php';
			if (!file_exists($arq))
			{
				die('<center>N&atilde;o foi poss&iacute;vel localizar a view <b>'.$arq.'</b></center>');
			}
		}
		ob_start();
		include_once($arq);
		$conteudo = ob_get_contents();
		ob_end_clean();

		foreach($this->Html->head as $_l => $_head) array_push($this->viewVars['head'],$_head);
		foreach($this->viewVars['head'] as $_l => $_line) $head[$_l] = $_line;

		// incluindo o layout
		ob_start();
		include_once('View/Layouts/'.$layout.'.php');
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
	
	/**
	 * Inclui o bloco de elemento
	 * 
	 * @param	string	$e		Nome do elemento
	 * @param	array	$vars	Variáveis quer serão importadas para dentro do element
	 * @return	void
	 */
	function element($e='',$vars=array())
	{
		$base 		= $this->viewVars['base'];
		$module		= $this->viewVars['module'];
		$controller = $this->viewVars['controller'];
		$action		= $this->viewVars['action'];
		// atualizando as variáveis locais
		foreach($vars as $_var => $_vlr) ${$_var} = $_vlr;
		$arq = 'View/Elements/'.$e.'.php';
		require_once($arq);
	}
}
