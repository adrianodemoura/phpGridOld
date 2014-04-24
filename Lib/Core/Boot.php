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
	 * Renderiza o layout a view solicitada via GET
	 *
	 * - Verifica a permissão do perfil logado
	 * 
	 * @access	public
	 * @return	void
	 */
	public function render()
	{
		header('Content-Type: text/html; charset=utf-8');

		// fuso-horário
		date_default_timezone_set('America/Sao_Paulo');

		// exibindo todos os erros
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		// incluido utilitários
		require_once(CORE.'Util/Util.php');

		// incluindo bootstrap
		require_once(APP.'Config/bootstrap.php');

		// sessão
		session_name(SESSAO); 
		session_start();

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
					if (strpos($_tag,':'))
					{
						$arrTag = explode(':',$_tag);
						$params[$arrTag['0']] = $arrTag['1'];
					} else $params[$_tag] = $_tag;
				}
			}
		}

		// identificando o controller
		$arq = 'Modules/'.$module.'/Config/bootstrap.php';
		if (!file_exists(APP.$arq))
		{
			$_SESSION['sistemaErro']['tip'] = 'module';
			$_SESSION['sistemaErro']['txt'] = 'Não foi possível localizar o seguinte arquivo: <br /><br />'.$arq;
			die('<script>document.location.href="'.$this->$controller->base.'sistema/usuarios/erros'.'"</script>');
		} else
		{
			require_once(APP.$arq);
		}

		// atualizando path
		set_include_path(get_include_path() . PATH_SEPARATOR . APP.'Modules/'.$module.'/');
		set_include_path(get_include_path() . PATH_SEPARATOR . CORE);

		// instanciando o controller
		$arq = 'Modules/'.$module.'/Controller/'.$controller.'Controller.php';
		if (!include_once($arq))
		{
			$url = $this->$controller->base.'sistema/usuarios/erros';
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
			// recuperando as permissões da tela
			if (isset($_SESSION['Usuario']['perfil']))
			{
				$model = $this->$controller->modelClass;
				
				// recuperando minhas permissoes, conforme meu perfil, módulo e controller corrente
				$minhasPermissoes = array();
				$idPerfil = 0;
				foreach($_SESSION['Perfis'] as $_id => $_perfil) if ($_perfil==$_SESSION['Usuario']['perfil']) $idPerfil = $_id;
				$sql = 'SELECT id, modulo, controller, perfil_id, visualizar, incluir, alterar, excluir, imprimir, pesquisar FROM sis_permissoes';
				$sql .= ' WHERE perfil_id='.$idPerfil;
				$sql .= ' AND modulo="'.strtoupper($module).'"';
				$sql .= ' AND controller="'.strtoupper($controller).'"';
				$_minhasPermissoes = $this->$controller->$model->query($sql);
				if (!empty($_minhasPermissoes))
				{
					$minhasPermissoes = $_minhasPermissoes['0'];
				}

				// recuperando os parâmetros para a tela de configuração de permissões de cada perfil
				if ($_SESSION['Usuario']['perfil']=='ADMINISTRADOR')
				{
					// todas as permissões pro bonitão da bala chita
					$minhasPermissoes['id'] 			= 1;
					$minhasPermissoes['modulo'] 		= $module;
					$minhasPermissoes['controller'] 	= $controller;
					$minhasPermissoes['perfil_id'] 		= 1;
					$minhasPermissoes['incluir'] 		= 1;
					$minhasPermissoes['alterar'] 		= 1;
					$minhasPermissoes['excluir'] 		= 1;
					$minhasPermissoes['imprimir'] 		= 1;
					$minhasPermissoes['pesquisar'] 		= 1;

					// recuperando todos os perfis
					$_perfis = $this->$controller->$model->query('SELECT id, nome FROM '.$this->$controller->$model->prefixo.'perfis WHERE id>1 ORDER BY nome');
					$perfis = array();
					foreach($_perfis as $_l => $_arrCmps)
					{
						$perfis[$_arrCmps['id']] = $_arrCmps['nome'];
					}
					$this->$controller->viewVars['permissoes']['perfis'] = $perfis;
					
					// recuperando as permissões da página corrente
					$_permissoes = $this->$controller->$model->query('SELECT 
						visualizar, incluir, alterar, excluir, imprimir, pesquisar, perfil_id
						FROM '.$this->$controller->$model->prefixo.'permissoes 
						WHERE modulo="'.strtolower($module).'" 
						AND controller="'.strtolower($controller).'" ORDER BY modulo, controller');
					foreach($_permissoes as $_l => $_arrCmps)
					{
						$idPerfil = $_arrCmps['perfil_id'];
						foreach($_arrCmps as $_cmp => $_vlr)
						{
							if ($_cmp!='perfil_id')
								$this->$controller->viewVars['permissoes']['acao'][$idPerfil][$_cmp] = $_vlr;
						}
					}
				}
				$this->$controller->viewVars['minhasPermissoes'] = $minhasPermissoes;
			}
		}

		// excluindo acessoNegado
		if (isset($_SESSION['acessoNegado']))
		{
			$chaveMCA = strtolower($module.'/'.$controller.'/'.$action);
			if ($chaveMCA!='sistema/usuarios/acesso_negado')
			{
				unset($_SESSION['acessoNegado']);
			}
		}

		// validando a permissão
		if (isset($_SESSION['Usuario']) && $_SESSION['Usuario']['perfil'] != 'ADMINISTRADOR')
		{
			if (!in_array(strtolower($action),array('erros'))
				)
			{
				$pode = isset($minhasPermissoes['visualizar']) ? $minhasPermissoes['visualizar'] : 0;
				if (!$pode)
				{
					$_SESSION['acessoNegado'] = strtolower($module.'/'.$controller.'/'.$action);
					$_SESSION['sistemaErro']['tip'] = 'Acesso Negado';
					$_SESSION['sistemaErro']['txt'] = 'Caro '.$_SESSION['Usuario']['nome'].', o seu perfil não possui privilégios suficientes para acessar a página '.strtolower($module.'/'.$controller.'/'.$action);
					header('Location: '.$this->$controller->base.'sistema/usuarios/acesso_negado');
				}
			}
		}

		// verificando se o perfil logado pode filtrar, se não, deleta os registros
		if (isset($_SESSION['Filtros'][$module][$controller]) && $_SESSION['Usuario']['perfil'] != 'ADMINISTRADOR')
		{
			if (!$minhasPermissoes['pesquisar']) unset($_SESSION['Filtros'][$module][$controller]);
		}

		// executando a action
		if (!method_exists($this->$controller,$action))
		{
			$_SESSION['sistemaErro']['tip'] = 'Página';
			$_SESSION['sistemaErro']['txt'] = 'Não foi possível localizar a Página <b>'.$action.'</b> do Cadastro <b>'.$controller.'</b> do módulo <b>'.$module.'</b>: <br />';
			die('<script>document.location.href="'.$this->$controller->base.'sistema/usuarios/erros'.'"</script>');
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
				$this->$controller->viewVars['primaryKey'] 	= $this->$controller->$_mod->primaryKey;
				if (isset($this->$controller->$_mod->pag) && isset($this->$controller->viewVars['paginacao'])) $this->$controller->viewVars['paginacao'] 	= $this->$controller->$_mod->pag;
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

		// executando código antes da renderização e depois da action
		$this->$controller->beforeRender();

		// atualizando as sqls do model na view
		$modelClass = $this->$controller->modelClass;
		$this->$controller->viewVars['sql_dump'] 	= $this->$controller->$modelClass->sqls;

		// atualizando as variáveis locais
		$this->data		= $this->$controller->data;
		$this->viewVars = $this->$controller->viewVars;
		foreach($this->viewVars as $_var => $_vlr) if (!in_array($_var,array('controller','action','module'))) ${$_var} = $_vlr;
		$this->viewVars['module']	 	= $module;
		$this->viewVars['controller'] 	= $controller;
		$this->viewVars['action'] 		= $action;

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

		// configurando o css e js da action
		$arq = strtolower($module.'_'.$controller.'_'.$action);
		if (file_exists('./css/'.$arq.'.css'))
		{
			array_push($head,'<link rel="stylesheet" type="text/css" href="'.$base.'css/'.$arq.'.css" />');
		}
		if (file_exists('./js/'.$arq.'.js'))
		{
			array_push($head,'<script type="text/javascript" src="'.$base.'js/'.$arq.'.js"></script>');
		}

		// incluindo a view 
		$conteudo = '';
		$arq = 'Modules/'.$module.'/View/'.$viewPath.'/'.$view.'.phtml';
		if (!file_exists(APP.$arq))
		{
			$msg = 'N&atilde;o foi poss&iacute;vel localizar a view <b>'.$arq.'</b>';
			$arq = CORE.'View/Scaffolds/'.$view.'.phtml';
			if (!file_exists($arq))
			{
				die('<center>'.$msg.'</center>');
			}
		}

		ob_start();
		include_once($arq);
		$conteudo = ob_get_contents();
		ob_end_clean();

		// configurando o head da página
		foreach($this->Html->head as $_l => $_head) array_unshift($this->viewVars['head'],$_head);
		foreach($this->viewVars['head'] as $_l => $_line) $head[$_l] = $_line;

		// incluindo o layout
		ob_start();
		include_once('View/Layouts/'.$layout.'.php');
		$html = ob_get_contents();
		ob_end_clean();

		// retornando a página renderizada
		return $html;
	}
	
	/**
	 * Inclui o bloco de elemento
	 * 
	 * @param	string	$e		Nome do elemento
	 * @param	array	$vars	Variáveis quer serão importadas para dentro do element, mas se usar this->viewVars você pega dos as variáveis da view
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
		$arq = 'View/Elements/'.$e.'.phtml';

		require_once($arq);
	}
}
