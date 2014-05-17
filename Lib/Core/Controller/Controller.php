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
	 * Dados do formulário data
	 * 
	 * @var		array
	 * @access	public
	 */
	public $data		= array();

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
	 * Start do controller
	 * 
	 * @return	void
	 */
	public function __construct()
	{
		// variávies obrigatórias para a view
		$this->viewVars['base'] 			= '';
		$this->viewVars['aqui'] 			= '';
		$this->viewVars['module'] 			= '';
		$this->viewVars['controller'] 		= '';
		$this->viewVars['action'] 			= '';
		$this->viewVars['tituloPagina'] 	= '';
		$this->viewVars['tituloModule'] 	= '';
		$this->viewVars['tituloController'] = '';
		$this->viewVars['tituloAction'] 	= '';
		$this->viewVars['tempoOn'] 			= 20;
		$this->viewVars['primaryKey'] 		= array();
		$this->viewVars['modulos'] 			= array();
		$this->viewVars['cadastros'] 		= array();
		$this->viewVars['permissoes'] 		= array();
		$this->viewVars['params'] 			= array();
		$this->viewVars['paginacao'] 		= array();
		$this->viewVars['msgFlash'] 		= array();
		$this->viewVars['head'] 			= array();
		$this->viewVars['onRead'] 			= array();
		$this->viewVars['esquema'] 			= array();
		
		// configurando o base
		$base = '';
		$base = isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:'http';
		$base .= '://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
		$base = str_replace('webroot/','',$base);
		$base = str_replace('index.php','',$base);
		$this->viewVars['base'] = $base;

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
	public static function setMsgFlash($texto='', $class='')
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
	 * - Os parâmetros pag (página), ord (ordem), dir(direção) são OBRIGATÓRIOS, 
	 * caso contrário o sistema vai criá-los automaticamente e redirecionár.
	 * - A página fica na sessão.
	 *
	 * @param 	modelClass
	 * @param	params
	 * @param	título da página
	 * @param	Parâmetros da página, ordem, direção e página
	 * @param	Filtros
	 * @param	Campos que vão compor a lista
	 * @param	Url Retorno, usada quando o formulário é postado
	 * @param	Opções para marcadores
	 * @param	Botões que vão no início da lista (novo, salvarTodos)
	 * @param	Ferramentas da lista, será repetido em cada linha 	
	 * @return 	void
	 */
	public function listar()
	{
		$modelClass = $this->modelClass;
		$params 	= array();
		
		if (empty($modelClass))
		{
			$this->setMsgFlash('Este cadastro não pode ser listado','msgFlashErro');
			$this->redirect(strtolower($this->module),'usuarios','erros');
		}

		$this->viewVars['tituloPagina'] = !empty($this->viewVars['tituloPagina']) 
			? $this->viewVars['tituloPagina'] 
			: 'Cadastro de '.$this->viewVars['cadastros'][strtoupper($this->controller)]['tit'];

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
			$this->redirect(strtolower($this->module),strtolower($this->controller),'listar',$params);
		}

		// salvando a página na sessão
		$_SESSION['Pagi'][$this->module][$this->controller]['pag'] = $this->params['pag'];

		// configurando filtro da página
		$params['where'] = isset($this->filtros) ? $this->filtros : array();

		// configurando os parâmetros dos filtros na sessão
		if (isset($_SESSION['Filtros'][$this->module][$this->controller]))
		{
			foreach($_SESSION['Filtros'][$this->module][$this->controller] as $_mod => $_arrCmps)
			{
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					if (strlen($_vlr)>0) $params['where'][$_mod.'.'.$_cmp] = $_vlr;
				}
			}
		}
		// configurando o filtro pelos parâmetros de pesquisa
		if (isset($this->params['pes']))
		{
			$pes = explode(',',$this->params['pes']);
			foreach($pes as $_l => $_pes)
			{
				if (strpos($_pes,'='))
				{
					$a = explode('=',$_pes);
					$params['where'][$modelClass.'.'.strtolower($a['0'])] = urldecode($a['1']);
				}
				if (strpos($_pes,'&'))
				{
					$a = explode('&',$_pes);
					$params['where'][$modelClass.'.'.strtolower($a['0']).' LIKE'] = urldecode($a['1']);
				}
			}
		}

		// recuperando os parâmetros da GET
		$params['pag'] 		= isset($this->params['pag']) ? $this->params['pag'] : 1;
		$params['pag']		= empty($params['pag']) ? 1 : $params['pag'];
		$params['order'] 	= isset($this->params['ord']) ? array($this->params['ord']) : array();
		$params['direc'] 	= isset($this->params['dir']) ? $this->params['dir'] : 'ASC';

		// recuperando o data
		$this->data = $this->$modelClass->find('all',$params);

		// configurando a url de retorno
		$this->viewVars['urlRetorno'] = isset($this->viewVars['urlRetorno']) ? $this->viewVars['urlRetorno'] : $this->viewVars['aqui'];

		// se foi feita uma pesquisa, e não achou nada retorna pra cá mesmo exibindo o erro
		if (isset($this->params['pes']))
		{
			if(!count($this->data))
			{
				$retorno = $this->viewVars['urlRetorno'];
				$retorno = substr($retorno,0,strpos($retorno,'/pes:'));
				$this->setMsgFlash('A pesquisa retornou vazio !!!','msgFlashErro');
				header('location: '.$retorno);
				die();
			}
		}
		
		// configurando os campos a serem exibidos
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
			if (empty($fields))
			{
				foreach($this->$modelClass->esquema as $_cmp => $_arrProp)
				{
					if (!in_array($_cmp,$this->$modelClass->primaryKey))
					{
						array_push($fields, $modelClass.'.'.$_cmp);
					}
				}
			}
			$this->viewVars['fields'] = $fields;
		}

		// botões da lista
		if (!isset($this->viewVars['botoesLista']['0']) && $this->pode('incluir'))
		{
			$this->viewVars['botoesLista']['0']['value'] 	= 'Novo';
			$this->viewVars['botoesLista']['0']['id']		= 'btNovo';
			$this->viewVars['botoesLista']['0']['type']		= 'button';
			$this->viewVars['botoesLista']['0']['class'] 	= 'btn btn-primary';
			$this->viewVars['botoesLista']['0']['onclick']	= '$("#novo").fadeIn(); $("#dir1").fadeOut(); $("#dir11").fadeOut(); $("#dir2").fadeOut();';
		}
		
		if (!isset($this->viewVars['botoesLista']['1']) && $this->pode('alterar'))
		{
			$this->viewVars['botoesLista']['1']['value'] 	= 'Salvar Todos';
			$this->viewVars['botoesLista']['1']['id']		= 'btSalvarT';
			$this->viewVars['botoesLista']['1']['type']		= 'button';
			$this->viewVars['botoesLista']['1']['class'] 	= 'btn btn-success';
			$this->viewVars['botoesLista']['1']['onclick']	= '$("#formLista").submit();';
		}

		// opções Excluir para os marcadores
		$m = isset($this->viewVars['marcadores']) ? $this->viewVars['marcadores'] : array();
		if (!isset($m['Excluir']) && $this->pode('excluir'))
		{
			$m['Excluir'] = $this->base.strtolower($this->module.'/'.$this->controller.'/excluir/');
		}
		// opções Exportar para os marcadores
		if (!isset($m['Exportar']) && $this->pode('exportar'))
		{
			$m['Exportar'] = $this->base.strtolower($this->module.'/'.$this->controller.'/exportar/');
		}
		$this->viewVars['marcadores'] = $m;

		// configurando os filtros
		$filtros = array();
		foreach($this->$modelClass->esquema as $_cmp => $_arrProp)
		{
			if (isset($_arrProp['filtro']) && $_arrProp['filtro']==true)
			{
				$alias = !empty($this->$modelClass->alias) ? $this->$modelClass->alias : $modelClass ;
				$filtros[$alias.'.'.$_cmp]['emptyFiltro'] 	= isset($_arrProp['emptyFiltro']) ? $_arrProp['emptyFiltro'] : '-- todos --';
				$filtros[$alias.'.'.$_cmp]['options'] 		= isset($_arrProp['options']) ? $_arrProp['options'] : array();
				if (empty($filtros[$alias.'.'.$_cmp]['options']))
				{
					$filtros[$alias.'.'.$_cmp]['options'] = $this->$modelClass->getOptions($_cmp);
				}
			}
			// testando habtm
			if ($_arrProp['type']=='habtm')
			{
				$this->viewVars['temHabtm'] = true;
			}
		}
		$this->viewVars['filtros'] 	= $filtros;

		// ferramentas da lista
		$f = isset($this->viewVars['ferramentas']) ? $this->viewVars['ferramentas'] : array();
		if (!isset($f['excluir']) && $this->pode('excluir'))
		{
			$link = $this->viewVars['base'].strtolower($this->module).'/'.strtolower($this->controller).'/excluir/id:*id*';
			$f['excluir']['tit'] 	= 'Excluir';
			$f['excluir']['link'] 	= $link;
			$f['excluir']['title'] 	= 'Clique aqui para excluir este registro';
			$f['excluir']['onclick']= "return confirm('Você tem certeza em excluir este registro ???')";
		}
		$this->viewVars['ferramentas'] = $f;

		// verificando se o módulo_controller_action possui CSS e JS próprio
		$arq = strtolower($this->module.'_'.$this->controller.'_listar');
		if (file_exists('./css/'.$arq.'.css'))
		{
			$link = htmlentities('<link rel="stylesheet" type="text/css" href="'.$this->viewVars['base'].'css/'
				.strtolower($this->module).'_'
				.strtolower($this->controller).'_listar.css" />');
			array_push($this->viewVars['head'],$link);
		}
		if (file_exists('./js/'.$arq.'.js'))
		{
			$link = htmlentities('<script type="text/javascript" src="'.$this->viewVars['base'].'js/'
				.strtolower($this->module).'_'
				.strtolower($this->controller).'_listar.js"></script>');
			array_push($this->viewVars['head'],$link);
		}

		// ferramentas para o layout
		$this->viewVars['ferramentasLayout']['2']['permissao']	= 'imprimir';
		$this->viewVars['ferramentasLayout']['2']['title'] 		= 'Relatórios';
		$this->viewVars['ferramentasLayout']['2']['icone'] 		= $this->viewVars['base'].'img/bt_relatorios.png';
		$this->viewVars['ferramentasLayout']['2']['onclick'] 	= 'document.location.href="'.$this->viewVars['base'].strtolower($this->module).'/'.strtolower($this->controller).'/relatorios"';

		$this->viewVars['ferramentasLayout']['3']['permissao']	= 'exportar';
		$this->viewVars['ferramentasLayout']['3']['title'] 		= 'Clique aqui para exportar todo o cadastro com filtros';
		$this->viewVars['ferramentasLayout']['3']['icone'] 		= $this->viewVars['base'].'img/bt_exportar.png';
		$this->viewVars['ferramentasLayout']['3']['onclick'] 	= 'document.location.href="'.$this->viewVars['base'].strtolower($this->module).'/'.strtolower($this->controller).'/exportar"';

		// se é administrador, recupera as permissões do cadastro corrente
		/*if ($_SESSION['Usuario']['perfil_id']==1)
		{
			$sql = "SELECT p.visualizar, p.incluir, p.alterar, p.excluir, 
					p.imprimir, p.pesquisar, p.exportar, p.perfil_id
					FROM sis_permissoes p
					INNER JOIN sis_modulos 		m ON m.id = p.modulo_id
					INNER JOIN sis_cadastros 	c ON c.id = p.cadastro_id
					WHERE m.nome='".strtoupper($this->module)."' AND c.cadastro='".strtoupper($this->controller)."'";
			$_permissoes = $this->$modelClass->query($sql);
			foreach($_permissoes as $_l => $_arrCmps)
			{
				$idPerfil = $_arrCmps['perfil_id'];
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					if ($_cmp!='perfil_id')
						$this->viewVars['permissoes']['acao'][$idPerfil][$_cmp] = $_vlr;
				}
			}
		}*/

		// verifica erros da lista
		if (isset($_SESSION['errosLista']))
		{
			$this->viewVars['erros'] = $_SESSION['errosLista'];
			unset($_SESSION['errosLista']);
		}
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
				debug($_POST['cx']);
				debug($_POST['marcador']);
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
				$this->viewVars['erros'] = $this->$modelClass->erros;
				$_SESSION['errosLista'] = $this->$modelClass->erros;
				$msg = 'Erro ao tentar atualizar registro !!!';
				if (isset($this->data['1'])) $msg = 'Erros ao tentar atualizar registros !!!';
				$this->setMsgFlash($msg,'msgFlashErro');
			} else
			{
				$msg = 'O Registro foi salvo com sucesso ...';
				if (isset($this->data['1'])) $msg = 'Os Registros foram salvos com sucesso ...';
				$this->setMsgFlash($msg,'msgFlashOk');
				$this->data = $this->$modelClass->data;
				$this->viewVars['msgOk'] = $msg;
				$this->viewVars['dados'] = $this->data;
			}
			if (!empty($this->viewVars['urlRetorno']))
			{
				header('Location: '.$this->viewVars['urlRetorno']); die();
			}
		}
	}

	/**
	 * Exibe a tela de exclusão de um registro
	 *
	 * @param  array Matriz com os campos e valores a serem excluído no model corrente
	 * @return void
	 */
	public function excluir()
	{
		$modelClass = $this->modelClass;
		$data		= array();
		$l 			= 0;
		foreach($this->params as $_cmp => $_vlr)
		{
			$data[$l][$modelClass][$_cmp] = $_vlr;
			$l++;
		}
		if (empty($data))
		{
			foreach($_POST['cx'] as $_cmps => $_vlr)
			{
				$a = explode('=', $_cmps);
				$data[$l][$modelClass][$a['0']] = $a['1'];
				$l++;
			}
		}

		if (!empty($data))
		{
			if ($this->$modelClass->exclude($data))
			{
				$msg = 'O Registro foi excluído com sucesso ...';
				if (isset($_POST['cx'])) $msg = 'Os registros foram excluídos com sucesso';
				$this->setMsgFlash($msg,'msgFlashErro');
				if (!isset($this->redirectOff)) $this->redirect(strtolower($this->module),strtolower($this->controller),'listar');
			} else
			{
				$msg = !empty($this->$modelClass->erro) ? $this->$modelClass->erro : 'Erro ao tentar excluir registro !!!';
				$this->setMsgFlash($msg,'msgFlashErro');
				if (!isset($this->redirectOff)) $this->redirect(strtolower($this->module),strtolower($this->controller),'listar');
			}
		} else
		{
			$this->setMsgFlash('Nenhum registro foi marcado para exclusão !!!','msgFlashErro');
			if (!isset($this->redirectOff)) $this->redirect(strtolower($this->module),strtolower($this->controller),'listar');
		}
	}

	/**
	 * Configura o filtro para a lista
	 *
	 * @return void
	 */
	public function set_filtro()
	{
		if (isset($_POST['filtro']))
		{
			unset($_SESSION['Pagi']);
			$l = 0;
			foreach($_POST['filtro'] as $_mod => $_arrCmps)
			{
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					if (strlen($_vlr)==0)
					{
						unset($_SESSION['Filtros'][$this->module][$this->controller][$_mod][$_cmp]);
					} else
					{
						$_SESSION['Filtros'][$this->module][$this->controller][$_mod][$_cmp] = $_vlr;
					}
					$l++;
				}
			}
			if (!count($_SESSION['Filtros'][$this->module][$this->controller][$_mod]))
			{
				unset($_SESSION['Filtros'][$this->module][$this->controller][$_mod]);
			}
		}
		$this->viewVars['urlRetorno'] = isset($_POST['urlRetorno']) ? $_POST['urlRetorno'] : $_SERVER['REQUEST_URI'];
		$this->redirect(strtolower($this->module.'/'.$this->controller.'/listar'));
		//debug($_POST['filtro']);
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
		$this->layout	= 'ajax';
		$modelClass 	= $this->modelClass;
		$fields			= isset($this->viewVars['params']['cmps']) 		
							? $this->viewVars['params']['cmps'] : null;
		$fields			= isset($this->viewVars['params']['fields']) 	
							? $this->viewVars['params']['fields'] : $fields;
		$ordem			= isset($this->viewVars['params']['ord']) 	
							? $this->viewVars['params']['ord'] : null;

		// se fields está vaziio, pega o id e o displayField
		if (empty($fields))
		{
			foreach ($this->$modelClass->primaryKey as $_l => $_cmp) 
			{
				if (!empty($fields)) $fields .= ', ';
				$fields .= $modelClass.'.'.$_cmp;
			}
			$fields .= ', '.$this->$modelClass->getDisplayField();
		}

		// se não tem ordem, pega o display field
		if (empty($ordem))
		{
			$this->viewVars['params']['ord'] = $this->$modelClass->getDisplayField();
		}

		// parametros
		$params				= array();
		$params['fields']	= explode(',',$fields);
		$params['where']	= array();
		$params['pag']		= isset($this->viewVars['params']['pag']) ? $this->viewVars['params']['pag'] : 1;
		$params['order']	= isset($this->viewVars['params']['ord']) ? explode(',',$this->viewVars['params']['ord']) : null;
		$params['direc']	= isset($this->viewVars['params']['dir']) ? explode(',',$this->viewVars['params']['dir']) : 'asc';
		$params['pag']		= isset($this->viewVars['params']['pag']) ? $this->viewVars['params']['pag'] : 1;

		// pegando a última página
		if ($params['pag']=='*')
		{
			$params['pag'] 		= 1;
			array_unshift($params['order'],$this->$modelClass->name.'.id');
			$params['direc'] 	= 'DESC';
		}

		// dando um loope nos parâmetros
		foreach($this->viewVars['params'] as $_cmp => $_vlr)
		{
			if ($_cmp!='cmps')
			{
				if (!in_array($_cmp,array('cmps','fields','pag','ord','dir')))
				{
					$params['where'][$_cmp.' LIKE'] = rawurldecode($_vlr);
				}
			}
		}
		if (empty($params['where'])) unset($params['where']);

		$this->viewVars['data'] 	= $this->$modelClass->find('list',$params);
		$this->viewVars['debug'] 	= isset($this->viewVars['debug']) ? $this->viewVars['debug'] : false;
	}

	/**
	 * Retorna o valor da permissão de uma determinada ação
	 * 
	 * @param	string	$acao	Nome da ação, que pode ser visualizar, incluir, alterar, excluir, imprimir e pesquisar
	 * @return	int		$pode	Valor da permissão, 1 para SIM e 0 para NÃO
	 */
	public function pode($acao='')
	{
		if (in_array($_SESSION['Usuario']['perfil'],array('ADMINISTRADOR'))) return 1;
		$minhasPermissoes = $this->viewVars['minhasPermissoes'];
		$pode = (isset($minhasPermissoes[$acao])) ? $minhasPermissoes[$acao] : 0;
		return $pode;
	}

	/**
	 * Executa a exportação do cadastro ativo
	 *
	 * - Leva em consideração os Filtros da sessão
	 *
	 * @return void
	 */
	public function exportar()
	{
		$modelClass		= $this->modelClass;
		$alias 			= isset($this->modelClass) ? $this->modelClass : $modelClass;
		$this->layout 	= 'csv';
		$params			= array();

		// configurando os parâmetros dos filtros na sessão
		if (isset($_SESSION['Filtros'][$this->module][$this->controller][$alias]))
		{
			foreach($_SESSION['Filtros'][$this->module][$this->controller][$alias] as $_cmp => $_vlr)
			{
				if (strlen($_vlr)>0) $params['where'][$_cmp] = $_vlr;
			}
		}
		// configurando os parâmetros dos filtros pelos marcadores
		if (isset($_POST['marcador']))
		{
			if (!empty($_POST['marcador']))
			{
				if (isset($_POST['cx']))
				{
					$ids = array();
					$cmp = '';
					foreach($_POST['cx'] as $_ids => $_ok)
					{
						$a = explode('=', $_ids);
						$cmp = $a['0'];
						array_push($ids, $a['1']);
					}
					$params['where'][$modelClass.'.'.$cmp.' IN'] = $ids;
				} else
				{
					$this->setMsgFlash('Nenhum registro foi marcado para exportação !!!','msgFlashErro');
					$this->redirect(strtolower($this->module),strtolower($this->controller),'listar');
				}
			}
		}

		// populando a view com o resultado da exportação
		$this->data = $this->$modelClass->find('all',$params);
	}

	/**
	 * Exibe a pagina de apresentação dos relatórios
	 *
	 * @return 	void
	 */
	public function relatorios()
	{
		$this->viewVars['tituloAction'] = !empty($this->viewVars['tituloAction']) ? $this->viewVars['tituloAction'] : 'Relatórios';
		$relatorios = isset($this->viewVars['relatorios']) ? $this->viewVars['relatorios'] : array();
		$this->viewVars['relatorios'] = $relatorios;
	}
}
