<?php
/**
 * Class Configurações
 * 
 * Aqui ficam todas as configurações do sistema
 * 
 * @package			Sistema
 * @subpackage		Sistema.Controller
 */
appUses('controller','SistemaApp');
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
	public function listar()
	{
		parent::listar();
		$this->viewVars['botoesLista']['0'] = array();
		$this->viewVars['marcadores'] 		= null;
		unset($this->viewVars['paginacao']);
		unset($this->viewVars['filtros']);
		unset($this->viewVars['ferramentas']);
	}
}
