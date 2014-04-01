<?php
/**
 * Class Configurações
 * 
 * Aqui ficam todas as configurações do sistema
 * 
 * @package			Sistema
 * @subpackage		Sistema.Controller
 */
/**
 * Include files
 */
include_once(APP.'Modules/Sistema/Controller/SistemaAppController.php');
class ConfiguracoesController extends SistemaAppController {
	/**
	 * Model Usuário
	 * 
	 * @var		array
	 */
	public $Model = array('Configuracao');

	/**
	 * Configurando a página
	 *
	 * @return void
	 */
	public function beforeIndex()
	{
		$this->viewVars['tituloPagina'] 	= 'Configurações do Sistema '.SISTEMA;
		$this->viewVars['tituloController'] = 'Configurações';
		parent::beforeIndex();
	}

	/**
	 * Exibe a lista de configuração
	 *
	 * @return void
	 */
	public function lista()
	{
		parent::lista();
		$this->viewVars['botoesLista']['0'] = array();
		$this->viewVars['botoesLista']['1'] = array();
		$this->viewVars['marcadores'] = null;
	}

	/**
	 * Salva o registro de configração no banco de dados
	 * 
	 * - Altera o valor da sessão sql_dump
	 * 
	 * @return	void
	 */
	public function salvar()
	{
		if (isset($this->data['1']['Configuracao']['sql_dump']))
		{
			$_SESSION['sql_dump'] = $this->data['1']['Configuracao']['sql_dump'];
		}
		parent::salvar();
	}
}
