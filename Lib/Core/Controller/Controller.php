<?php
/**
 * Core Controller
 * 
 * @package		Core
 * @subpackage	Core.Controller
 */
class Controller {
	/**
	 * Módulo
	 * 
	 * @var		string
	 * @access 	public
	 */
	public $module 		= null;

	/**
	 * Controller
	 * 
	 * @var		string
	 * @access 	public
	 */
	public $controller	= null;

	/**
	 * Action
	 * 
	 * @var		string
	 * @access 	public
	 */
	public $action 		= null;

	/**
	 * Layout padrão
	 * 
	 * @var		string
	 */
	public $layout 		= 'padrao';

	/**
	 * Nome da classe do model
	 *
	 @var 		string
	 @access 	public
	 */
	public $modelClass	= '';

	/**
	 * Model do controlador
	 * 
	 * @var		mixed
	 */
	public $Model		= array();

	/**
	 * Variáveis da visão
	 * 
	 * @var		mixed
	 */
	public $viewVars	= array();

	/**
	 * caminha da view
	 * 
	 * @var		mixed
	 */
	public $viewPath	= null;

	/**
	 * Dados do formulário data
	 * 
	 * @var		array
	 * @access	public
	 */
	public $data		= array();

	/**
	 * Start do controller
	 * 
	 * @return	void
	 */
	public function __construct()
	{
		// variávies obrigatórias para a view
		$this->viewVars['base'] 			= '';
		$this->viewVars['aqui'] 			= '';
		$this->viewVars['se'] 				= '';
		$this->viewVars['tituloPagina'] 	= '';
		$this->viewVars['tituloModule'] 	= '';
		$this->viewVars['tituloController'] = '';
		$this->viewVars['tituloAction'] 	= '';
		$this->viewVars['position'] 		= '';
		$this->viewVars['tempoOn'] 			= 20;
		$this->viewVars['primaryKey'] 		= array();
		$this->viewVars['permissoes'] 		= array();
		$this->viewVars['paginacao'] 		= array();
		$this->viewVars['params'] 			= array();
		$this->viewVars['msgFlash'] 		= array();
		$this->viewVars['head'] 			= array();
		$this->viewVars['onRead'] 			= array();
		$this->viewVars['esquema'] 			= array();
		
		// configurando o base
		$this->viewVars['base'] = getBase();

		// configura o aqui
		$aqui = isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:'http';
		$aqui .= '://'.$_SERVER['SERVER_NAME'];
		$this->viewVars['aqui'] = $aqui.$_SERVER['REQUEST_URI'];
	}

	/**
	 * Primeira função do index
	 * 
	 * @return	void
	 */
	public function index()
	{
		if (isset($_SESSION['Usuario']))
		{
			$modelClass = $this->modelClass;
			$params['pag'] = 1;
			$params['ord'] = $this->$modelClass->getDisplayField();
			$params['dir'] = 'asc';
			$this->redirect(strtolower($this->module), strtolower($this->controller), 'lista',$params);
		} else $this->redirect('sistema', 'usuarios', 'login');
	}

	/**
	 * Executa código antes da action
	 * 
	 * @return	void
	 */
	public function beforeIndex()
	{
	}

	/**
	 * Executa código depois da action
	 * 
	 * @return	void
	 */
	public function afterIndex()
	{
	}

	/**
	 * Executa código antes da renderização da View
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
	}

	/**
	 * Configura a mensagen Flahs, usada no topo do cabeçalho
	 * 
	 * @param	string	$texto	Texto da mensagen
	 * @param	string	$class	Classe que vai ser usada na div msg_flash
	 * @return	void
	 */
	public function setMsgFlash($texto='', $class='')
	{
		$_SESSION['msgFlash']['txt']	= $texto;
		$_SESSION['msgFlash']['class'] 	= $class;
	}

	/**
	 * Redirecion o fluxo da request
	 * 
	 * @param 	string 	$mod 	Módulo
	 * @param	string	$con	Controller
	 * @param	string	$act	Action
	 * @param	string	$plug	Plugin
	 * @param	string	$par	Parâmetros
	 * @return	void
	 */
	public function redirect($mod='', $con='', $act='', $par=array())
	{
		$url = $this->base.$mod.'/'.$con.'/'.$act;
		if (!empty($par))
		{
			foreach($par as $_l => $_vlr)
			{
				$url .= '/'.$_l.':'.$_vlr;
			}
		}
		header('Location: '.$url);
		die();
	}

	/**
	 * Exibe a tela de listagem do cadastro corrente
	 * 
	 * - Os parâmetros pag (página), ord (ordem), dir(direção) são OBRIGATÓRIOS, caso contrário o sistema vai criá-los automaticamente.
	 *
	 * @param 	array 	
	 * @return 	void
	 */
	public function lista()
	{
		$modelClass = $this->modelClass;
		$params 	= array();

		$this->viewVars['tituloPagina'] 	= !empty($this->viewVars['tituloPagina']) ? $this->viewVars['tituloPagina'] : 'Lista de '.$this->controller;

		// verificando a sessão de paginação, se for de outro módulo.controller, zera ela.
		if (isset($_SESSION['Pagi']))
		{
			if (!isset($_SESSION['Pagi'][$this->module][$this->controller]))
			{
				unset($_SESSION['Pagi']);
			}
		}

		// se não tem parâmetros, cria-os-os ...
		if (	!isset($this->params['pag'])
			||	!isset($this->params['ord'])
			||  !isset($this->params['dir'])
			)
		{
			$params['pag'] = isset($_SESSION['Pagi'][$this->module][$this->controller]['pag']) ? $_SESSION['Pagi'][$this->module][$this->controller]['pag'] : 1;
			$params['ord'] = $this->$modelClass->getDisplayField();
			$params['dir'] = 'asc';
			$this->redirect(strtolower($this->module),strtolower($this->controller),'lista',$params);
		}

		// salvando a página na sessão
		$_SESSION['Pagi'][$this->module][$this->controller]['pag'] = $this->params['pag'];

		// configurando os parâmetros dos filtros na sessão
		if (isset($_SESSION['Filtros'][$this->module][$this->controller]))
		{
			foreach($_SESSION['Filtros'][$this->module][$this->controller] as $_cmp => $_vlr)
			{
				if (strlen($_vlr)>0) $params['where'][$_cmp] = $_vlr;
			}
		}

		// recuperando os parâmetros da GET
		$params['pag'] 		= isset($this->params['pag']) ? $this->params['pag'] : 1;
		$params['pag']		= empty($params['pag']) ? 1 : $params['pag'];
		$params['order'] 	= isset($this->params['ord']) ? $this->params['ord'] : array();
		$params['direc'] 	= isset($this->params['dir']) ? $this->params['dir'] : 'ASC';

		// recuperando o data
		$this->data 		= $this->$modelClass->find('all',$params);
		if (!isset($this->viewVars['fields']))
		{
			$fields = array();
			foreach($this->data as $_l => $_arrMods)
			{
				foreach($_arrMods as $_mod => $_arrCmps)
				{
					foreach($_arrCmps as $_cmp => $_vlr)
					{
						if ($_mod==$modelClass && !in_array($_cmp,$this->$modelClass->primaryKey)) array_push($fields,$_mod.'.'.$_cmp);
					}
				}
				break;
			}
			$this->viewVars['fields'] = $fields;
		}
		$this->viewVars['urlRetorno'] = isset($this->viewVars['urlRetorno']) ? $this->viewVars['urlRetorno'] : $this->viewVars['aqui'];

		// opções para os marcadores
		$m = isset($this->viewVars['marcadores']) ? $this->viewVars['marcadores'] : array();
		if (!isset($m['Excluir']))
		{
			$m['Excluir'] = $this->base.strtolower($this->module.'/'.$this->controller.'/excluir/');
		}
		$this->viewVars['marcadores'] = $m;

		// configurando os filtros
		$filtros = array();
		foreach($this->$modelClass->esquema as $_cmp => $_arrProp)
		{
			if (isset($_arrProp['filtro']) && $_arrProp['filtro']==true)
			{
				$filtros[$_cmp]['emptyFiltro'] 	= isset($_arrProp['emptyFiltro']) ? $_arrProp['emptyFiltro'] : '-- todos --';
				$filtros[$_cmp]['options'] 		= isset($_arrProp['options']) ? $_arrProp['options'] : array();
				if (empty($filtros[$_cmp]['options']))
				{
					$filtros[$_cmp]['options'] = $this->$modelClass->getOptions($_cmp);
				}
			}
		}
		$this->viewVars['filtros'] 	= $filtros;

		// ferramentas da lista
		$f = isset($this->viewVars['ferramentas']) ? $this->viewVars['ferramentas'] : array();
		/*if (!isset($f['editar']))
		{
			$f['editar']['tit'] 	= 'Editar';
			$f['editar']['link'] 	= $this->viewVars['base'].strtolower($this->module).'/'.strtolower($this->controller).'/editar/*id*';
		}*/
		if (!isset($f['excluir']))
		{
			$f['excluir']['tit'] 	= 'Excluir';
			$f['excluir']['link'] 	= $this->viewVars['base'].strtolower($this->module).'/'.
				strtolower($this->controller).'/excluir/id:*id*';
			$f['excluir']['title'] 	= 'Clique aqui para excluir este registro';
		}
		$this->viewVars['ferramentas'] = $f;
	}

	/**
	 * Salva o formulário postado no banco de dados
	 * 
	 * @param	array	Formulário com o nome data
	 * @return	void
	 */
	public function salvar()
	{
		$modelClass = $this->modelClass;
		$this->viewVars['urlRetorno'] = isset($_POST['urlRetorno']) ? $_POST['urlRetorno'] : '';

		if (isset($_POST['marcador']) && !empty($_POST['marcador']))
		{
			if (isset($_POST['cx']))
			{
				foreach($_POST['cx'] as $_ids => $_ok)
				{
					
				}
				$this->viewVars['dados'] = $_POST['cx'];
				$msg = 'Os Registros foram aplicados com sucesso ...';
			} else
			{
				$this->viewVars['msgErro'] = 'Nenhum registro foi marcado !!!';
			}
		} else
		{
			if (!$this->$modelClass->save($this->data))
			{
				$this->viewVars['msgErro'] = $this->$modelClass->erro;
			} else
			{
				$this->viewVars['msgOk'] = 'Os Registros foram salvos com sucesso !!!';
				$this->viewVars['dados'] = $this->data;
			}
			if (!empty($this->viewVars['urlRetorno']))
			{
				$msg = 'O Registro foi salvo com sucesso ...';
				if (isset($this->data['1'])) $msg = 'Os Registros foram salvos com sucesso ...';
				$this->setMsgFlash($msg,'msgFlashOk');
				header('Location: '.$this->viewVars['urlRetorno']); die();
			}
		}
	}

	/**
	 * Exibe a tela de exclusão de um registro
	 *
	 * @return void
	 */
	public function excluir()
	{
		$modelClass = $this->modelClass;
		if ($this->$modelClass->exclude($this->params))
		{
			$this->setMsgFlash('O Registro foi excluído com sucesso ...','msgFlashErro');
			$this->redirect(strtolower($this->module),strtolower($this->controller),'lista');
		} else
		{
			$msg = !empty($this->$modelClass->erro) ? $this->$modelClass->erro : 'Erro ao tentar excluir registro !!!';
			$this->setMsgFlash($msg,'msgFlashErro');
			$this->redirect(strtolower($this->module),strtolower($this->controller),'lista');
		}
	}

	/**
	 * Exibe a tela de exclusão de um registro
	 *
	 * @return void
	 */
	public function set_filtro()
	{
		if (isset($_POST['filtro']))
		{
			unset($_SESSION['Pagi']);
			$l = 0;
			foreach($_POST['filtro'] as $_cmp => $_vlr)
			{
				if (strlen($_vlr)==0)
				{
					unset($_SESSION['Filtros'][$this->module][$this->controller][$_cmp]);
				} else
				{
					$_SESSION['Filtros'][$this->module][$this->controller][$_cmp] = $_vlr;
				}
				$l++;
			}
			if (!count($_SESSION['Filtros'][$this->module][$this->controller]))
			{
				unset($_SESSION['Filtros'][$this->module][$this->controller]);
			}
		}
		$this->viewVars['urlRetorno'] = isset($_POST['urlRetorno']) ? $_POST['urlRetorno'] : $_SERVER['REQUEST_URI'];
		$this->redirect(strtolower($this->module.'/'.$this->controller.'/lista'));
	}

	/**
	 * Retorna uma lista para combobox
	 * - A linha 0, do resultado, conterá os nomes dos campos
	 * - O resultado será impresso com os valores de cada campo
	 * - A pesquisa sempre será pelo método LIKE
	 * - Por segurança, o limite da pesquisa não vai passar 20
	 * - Todos os parâmetros serão passados pela url, na nomenclatura parâmetro:valor
	 * 
	 * exemplo de uso:
	 * http://localhost/phpgrid/sistema/cidades/get_options/cmps:Cidade.id,Cidade.nome,Cidade.uf/ord:Cidade.nome,Cidade.uf/Cidade.nome:maria
	 * - será retornado uma lista com os campos id, nome e uf do model Cidade, aonde o campo nome possui o texto "maria"
	 * 
	 * @param	Campos que serão listados
	 * @param	Campos que serão filtrados, seguindo a seguinte nomenclauro: Model.campo:valor
	 * @param	Página, se nenhuma foi informada o padrão é '1'
	 * @param	Campos que irão ordernar a lista (usado para Order by), com a nomenclautura: ord:Model.campo1,Model.campo2
	 * @param	debug	se ligado irá imprimir br no final de cada linha, e ainda o sql_dump
	 * @return	string
	 */
	public function get_options()
	{
		$this->layout		= 'ajax';
		$modelClass 		= $this->modelClass;
		$params				= array();
		$params['fields']	= explode(',',$this->viewVars['params']['cmps']);
		$params['where']	= array();
		$params['pag']		= isset($this->viewVars['params']['pag']) ? $this->viewVars['params']['pag'] : 1;
		$params['order']	= isset($this->viewVars['params']['ord']) ? explode(',',$this->viewVars['params']['ord']) : null;
		$params['pag']		= isset($this->viewVars['params']['pag']) ? $this->viewVars['params']['pag'] : 1;
		
		// pegando a última página
		if ($params['pag']=='*')
		{
			$params['pag']=1;
			//debug($params['order']);
		}
		
		foreach($this->viewVars['params'] as $_cmp => $_vlr)
		{
			if ($_cmp!='cmps')
			if (!in_array($_cmp,array('cmps','pag','ord','dir'))) $params['where'][$_cmp] = 'LIKE '.rawurldecode($_vlr);
		}
		if (empty($params['where'])) unset($params['where']);
		$this->viewVars['data'] = $this->$modelClass->find('list',$params);
		$this->viewVars['debug'] = isset($this->viewVars['debug']) ? $this->viewVars['debug'] : false;
	}
}
