<?php
/**
 * Class Usuários
 * 
 * @package			Sistema
 * @subpackage		Sistema.Controller
 */
/**
 * Include files
 */
include_once(APP.'Modules/Sistema/Controller/SistemaAppController.php');
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
		$this->viewVars['tituloPagina'] 	= 'Informações de Usuário';
		$this->viewVars['tituloController'] = 'Usuários';
		parent::beforeIndex();
	}

	/**
	 * Exibe a tela de lista do cadastro de usuários
	 * 
	 * @return	void
	 */
	public function lista()
	{
		$this->viewVars['fields'] = array('Usuario.ativo','Usuario.nome','Usuario.email','Usuario.celular'
		,'Usuario.acessos'
		,'Usuario.senha'
		,'Usuario.trocar_senha'
		,'Usuario.ultimo_ip'
		,'Usuario.cidade_id');
		parent::lista();
	}

	/**
	 * Renderes start page of this controller
	 * 
	 * @return	void
	 */
	public function login()
	{
		$this->viewVars['tituloPagina'] 	= 'Página Inicial';
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
					$_SESSION['sql_dump'] = $data['0']['Configuracao']['sql_dump'];
				}

				$this->setMsgFlash('Usuário autenticado com sucesso !!!','msgFlashOk');
				$this->redirect('sistema','usuarios','info');
			} else
			{
				$msg = 'Usuário inválido !!!';
				$this->setMsgFlash('Usuário inválido','msgFlashErro');
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
	 * Exibe a tela de instalação do módulo sistema
	 * 
	 * @return	void
	 */
	public function instalacao()
	{
		error_reporting(E_WARNING);
		ini_set('display_errors', 0);

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
		
		$sql = 'SELECT id FROM sis_permissoes';
		$sql .= ' WHERE modulo="'.$modulo.'"';
		$sql .= ' AND controller="'.$controller.'"';
		$sql .= ' AND perfil_id='.$perfilId;
		$data = $this->Usuario->query($sql);
		$id = isset($data['0']['id']) ? $data['0']['id'] : 0;
		if ($id>0)
		{
			$sql = 'UPDATE sis_permissoes';
			$sql .= ' SET '.$permissao.'='.$vlr;
			$sql .= ' WHERE id='.$id;
		} else
		{
			$sql = 'INSERT INTO sis_permissoes';
			$sql .= ' (modulo,controller,'.$permissao.',perfil_id)';
			$sql .= ' VALUE';
			$sql .= ' ("'.$modulo.'","'.$controller.'",'.$vlr.','.$perfilId.')';
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
		$_SESSION['Usuario']['perfil'] = $_SESSION['Perfis'][$idPerfil];
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}
}
