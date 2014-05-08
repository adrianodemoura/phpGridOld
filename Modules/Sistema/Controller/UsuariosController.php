<?php
/**
 * Class Usuários
 * 
 * @package			Sistema
 * @subpackage		Sistema.Controller
 */
appUses('Controller','SistemaApp');
class UsuariosController extends SistemaAppController {
	/**
	 * Model Usuário
	 * 
	 * @var		array
	 */
	public $Model = array('Usuario');

	/**
	 * Exibe a tela principal do cadastro de usuários
	 * 
	 * @return	void
	 */
	public function beforeIndex()
	{
		$this->viewVars['tituloPagina'] 	= 'Cadastro de Usuários';
		$this->viewVars['tituloController'] = 'Usuários';
		parent::beforeIndex();
	}

	/**
	 * Exibe a página principal do sistema
	 * - Aqui serão exibidos os módulos e seus cadastros
	 * - Os Cadastros mais acessados pelo usuário, serão listado primeiro
	 * 
	 * @return	void
	 */
	public function index()
	{
		$this->viewVars['tituloPagina'] 	= 'Página Inicial';
		parent::index();
	}

	/**
	 * Exibe a tela de lista do cadastro de usuários
	 * 
	 * @return	void
	 */
	public function listar()
	{
		$this->viewVars['fields'] = array('Usuario.ativo','Usuario.nome','Usuario.email','Usuario.celular'
		,'Usuario.acessos'
		,'Usuario.senha'
		,'Usuario.trocar_senha'
		,'Usuario.ultimo_ip'
		,'Usuario.cidade_id');
		if (!in_array($_SESSION['Usuario']['perfil'],array('ADMINISTRADOR','GERENTE')))
		{
			$this->filtros['Usuario.id'] = $_SESSION['Usuario']['id'];
		}
		parent::listar();
	}

	/**
	 * Renderes start page of this controller
	 * 
	 * @return	void
	 */
	public function login()
	{
		$this->viewVars['tituloPagina'] 	= SISTEMA.' - login';
		$this->layout						= 'publico';
		if (isset($_SESSION['Usuario']['id'])) $this->redirect('sistema','usuarios','info');
		if (!empty($this->data['Usuario']['senha']))
		{
			$data = $this->Usuario->autentica($this->data['Usuario']['email'],$this->data['Usuario']['senha']);
			if (count($data))
			{
				$msg = 'Usuário autenticado com sucesso !!!';
				$_SESSION['Usuario']['id'] 				= $data['0']['Usuario']['id'];
				$_SESSION['Usuario']['email'] 			= $data['0']['Usuario']['email'];
				$_SESSION['Usuario']['nome'] 			= $data['0']['Usuario']['nome'];
				$_SESSION['Usuario']['ultimo_ip'] 		= $data['0']['Usuario']['ultimo_ip'];
				$_SESSION['Usuario']['perfil'] 			= $data['0']['Perfil']['0']['nome'];
				$_SESSION['Usuario']['perfil_id'] 		= $data['0']['Perfil']['0']['id'];
				$novaData['0']['Usuario']['id'] 		= $data['0']['Usuario']['id'];
				$novaData['0']['Usuario']['acessos'] 	= ($data['0']['Usuario']['acessos']+1);
				$novaData['0']['Usuario']['ultimo_ip'] 	= (strlen($_SERVER['SERVER_ADDR'])>4) ? $_SERVER['SERVER_ADDR'] : $_SERVER['REMOTE_ADDR'];
				if ($novaData['0']['Usuario']['ultimo_ip']=='::1') $novaData['0']['Usuario']['ultimo_ip'] = '127.0.0.1';

				// salvando meus perfis na sessão
				$meusPerfis = array();
				foreach($data['0']['Perfil'] as $_l => $_arrCmps)
				{
					$meusPerfis[$_arrCmps['id']] = $_arrCmps['nome'];
				}
				if (empty($meusPerfis)) $meusPerfis['0'] = 'VISITATNE';
				$_SESSION['Perfis'] = $meusPerfis;

				// atualizando usuário
				if (!$this->Usuario->save($novaData))
				{
					debug($novaData);
					die('Erro ao atualizar Usuários');
				} else
				{	// recuperando as configuraçõe de sessão
					require_once('Model/Configuracao.php');
					$Conf = new Configuracao();
					$data = $Conf->find('all');
					if (isset($data['0']['Configuracao']['sql_dump']))
					{
						$_SESSION['sql_dump'] = $data['0']['Configuracao']['sql_dump'];
					}
				}

				$this->setMsgFlash('Usuário autenticado com sucesso !!!','msgFlashOk');
				$this->redirect('sistema','usuarios','info');
			} else
			{
				$msg = 'Usuário inválido !!!';
				$this->setMsgFlash('Usuário inválido','msgFlashErro');
				if (!empty($this->Usuario->erros))
				{
					$this->viewVars['erroBanco'] = $this->Usuario->erros['0'];
				}
			}
		}
	}

	/**
	 * Exibe a tela de informações sobre o usuário logado
	 * 
	 * @return	void
	 */
	public function info()
	{
		$this->viewVars['tituloAction'] = 'Informações do Usuário';
		$opcs = array();
		$opcs['where']['Usuario.id'] = $_SESSION['Usuario']['id'];
		$this->data = $this->Usuario->find('all',$opcs);
		unset($this->data['0']['Usuario']['senha']);
		$this->Usuario->outrosEsquemas['Cidade']['nome']['tit'] = 'Cidade';
		$this->data['0']['Usuario']['ultimo_ip'] = $_SESSION['Usuario']['ultimo_ip'];
		$perfis = $this->data['0']['Perfil'];
		unset($this->data['0']['Perfil']);
		$meusPerfis = '';
		foreach($perfis as $_l => $_arrCmps)
		{
			if ($_l) $meusPerfis .= ', ';
			$meusPerfis .= $_arrCmps['nome'];
		}
		$this->data['0']['Usuario']['Perfis'] = $meusPerfis;
	}

	/**
	 * Exibe a tela de instalação do banco de dados
	 *
	 * @return void
	 */
	public function instala_bd()
	{
		$this->layout 	= 'publico';
		$modelClass 	= $this->modelClass;
		include_once(APP.'Config/database.php');
		$dbConfig = new Database_Config($this->$modelClass->database);
		$this->viewVars['banco'] = $dbConfig->default;
	}

	/**
	 * Exibe a tela de instalação do módulo sistema
	 * 
	 * @return	void
	 */
	public function instala_tb()
	{
		error_reporting(E_WARNING);
		ini_set('display_errors', 1);

		$this->layout = 'publico';
		$sql = 'SELECT id from sis_usuarios where id=1';
		$data = $this->Usuario->query($sql);
		if (count($data))
		{
			$this->setMsgFlash('O Sistema básico já foi instalado !!!','msgFlashErro');
			$this->redirect('sistema','usuarios','login');
		} else
		{
			$arq = APP.'Modules/Sistema/Model/Sql/Sistema.sql';
			if (file_exists($arq))
			{
				$handle  = fopen($arq,"r");
				$texto   = fread($handle, filesize($arq));
				$sqls	 = explode(";",$texto);
				fclose($handle);
				$this->viewVars['msg'] = 'O Sistema Básico foi instalado com sucesso ...';

				// executando sql a sql
				foreach($sqls as $sql)
				{
					if (trim($sql))
					{
						$res = $this->Usuario->query($sql);
					}
				}
				
				// importando csv
				include_once('Model/Util.php');
				$Util = new Util();

				$tabs = array('sis_cidades','sis_territorios','sis_bairros');
				foreach($tabs as $_l => $_tabela)
				{
					$arq = APP.'Modules/Sistema/Model/Sql/'.$_tabela.'.csv';
					if (file_exists($arq))
					{
						if (!$Util->setPopulaTabela($arq,$_tabela)) die('erro ao importar '.$_tabela);
					}
				}
				$this->setMsgFlash($this->viewVars['msg'],'msgFlashOk');
				$this->redirect('sistema','usuarios','login');
			} else
			{
				$this->viewVars['msgErro'] = 'O arquivo '.$arq.', não foi localizado ...';
			}
		}
	}

	/**
	 * Executa o log-off no sistema
	 *
	 * @return	void
	 */
	public function sair()
	{
		session_destroy();
		$this->redirect('sistema','usuarios','login');
	}

	/**
	 * Exibe a tela pra re-enviar a senha
	 * 
	 * @return	boid 
	 */
	public function esqueci_a_senha()
	{
		$this->viewVars['tituloPagina'] 	= 'Senha';
		$this->layout						= 'publico';
	}

	/**
	 * Exibe a tela pra criar um novo registro
	 * 
	 * @return	void
	 */
	public function registro()
	{
		$this->viewVars['tituloPagina'] 	= 'Registro';
		$this->layout						= 'publico';
	}

	/**
	 * Exibe a tela de erros do sistema
	 * 
	 * @return	void
	 */
	public function erros()
	{
		$this->viewVars['txt'] = isset($_SESSION['sistemaErro']['txt']) ? $_SESSION['sistemaErro']['txt'] : '';
		$this->viewVars['tip'] = isset($_SESSION['sistemaErro']['tip']) ? $_SESSION['sistemaErro']['tip'] : '';
		unset($_SESSION['sistemaErro']);
	}

	/**
	 * Exibe a tela de Acesso Negado
	 * 
	 * @param	chaveMCA negada (ModuloControllerAction)
	 * @return	void
	 */
	public function acesso_negado()
	{
		$this->viewVars['mvcRetorno'] = isset($_SESSION['acessoNegado']) ? $_SESSION['acessoNegado'] : null;
	}

	/**
	 * Liga ou desliga o sql_dump
	 * 
	 * @return	void
	 */
	public function set_sqldump()
	{
		if (!isset($_SESSION['sqldump']))
		{
			$this->setMsgFlash('Sql Dump Habilitado !!!','msgFlashOk');
			$_SESSION['sqldump'] = true;
		} else
		{
			$this->setMsgFlash('Sql Dump Desabilitado !!!','msgFlashOk');
			unset($_SESSION['sqldump']);
		}
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}

	/**
	 * Liga ou desliga o sql_dump
	 * 
	 * @return	void
	 */
	public function permissoes()
	{
		// se não é administrado não vai ...
		if (!$_SESSION['Usuario']['perfil']=='ADMINISTRADOR')
		{
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}

		$this->viewVars['url'] = $_SERVER['HTTP_REFERER'];
	}

	/**
	 * Configura uma permissão de acesso
	 * 
	 * @param	string	Nome do módulo
	 * @param	string	Nome do controller
	 * @param	string	Nome da permissão (visualizar, incluir, alterar, excluir, imprimir ou pesquisar)
	 * @param	string	Incluir ou Excluir permissão (Ok=incluir,Fa=excluir)
	 */
	public function set_permissao()
	{
		$this->layout 			= 'ajax';
		$this->viewVars['tipo'] = $this->params['tipo'];
		$modulo 				= strtoupper($this->params['modulo']);
		$controller 			= strtoupper($this->params['controller']);
		$arrPermissao			= explode('_',$this->params['permissao']);
		$permissao				= $arrPermissao['0'];
		$perfilId				= $arrPermissao['1'];
		$vlr					= ($this->params['tipo']=='ok') ? 0 : 1;
		
		$sql = "SELECT p.id FROM sis_permissoes p
			INNER JOIN sis_modulos 		m ON m.id = p.modulo_id
			INNER JOIN sis_cadastros 	c ON c.id = p.cadastro_id
			WHERE 
				m.nome='".$modulo."' 
			AND c.cadastro='".$controller."'
			AND perfil_id=".$perfilId;

		$data = $this->Usuario->query($sql);
		$id = isset($data['0']['id']) ? $data['0']['id'] : 0;
		if ($id>0)
		{
			$sql = 'UPDATE sis_permissoes';
			$sql .= ' SET '.$permissao.'='.$vlr;
			$sql .= ' WHERE id='.$id;
		} else
		{
			$sql = "SELECT id FROM sis_modulos WHERE nome='".$modulo."'";
			$res = $this->Usuario($sql);
			$modulo_id = $res['0']['id'];
			$sql = "SELECT id FROM sis_cadastros WHERE cadastro='".$controller."'";
			$res = $this->Usuario($sql);
			$cadastro_id = $res['0']['id'];

			$sql = 'INSERT INTO sis_permissoes';
			$sql .= ' (modulo_id,cadastro_id,'.$permissao.',perfil_id)';
			$sql .= ' VALUE';
			$sql .= ' ("'.$modulo_id.'","'.$cadastro_id.'",'.$vlr.','.$perfilId.')';
		}
		$this->Usuario->query($sql);
	}

	/**
	 * Troca o perfil corrente do usuário logado
	 * 
	 * @param	id do perfil
	 * @return	void
	 */
	public function set_perfil()
	{
		$idPerfil = $this->params['perfil'];
		$this->setMsgFlash('O Perfil foi alterado com sucesso !!!','msgFlashOk');
		$_SESSION['Usuario']['perfil'] 		= $_SESSION['Perfis'][$idPerfil];
		$_SESSION['Usuario']['perfil_id'] 	= $idPerfil;
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}

	/**
	 * Troca o módulo corrente
	 *
	 * @param 	string 	$modulo 	Nome do módulo
	 * @return 	void
	 */
	public function set_modulo()
	{
		$this->layout = 'ajax';
		$url = getBase();
		$url .= $this->data['modulo'];
		debug($url);
		header('Location: '.$url);
	}

	/**
	 * Executa o upload e redimensiona uma image
	 * 
	 * - As novas Imagens serão salvas no diretório webroot/uploads
	 * 
	 * @param	array	$files		Matriz com as propriedades da imagem que sofreu o upload
	 * @param	integer	$red 		Porcentagem de dimensionamento que a nova imagem irá receber
	 * @return	void
	 */
	public function upload()
	{
		if (isset($_POST['red']))
		{
			appUses('component','Imagem');
			$Img = new ImagemComponent();
			if ($Img->Redimensionar($_FILES['img'], $_POST['red']))
			{
				$this->viewVars['name'] = $Img->name;
			} elseif (!empty($Img->erro))
			{
				$this->viewVars['erro'] = $Img->erro;
			}
		}
	}
}
