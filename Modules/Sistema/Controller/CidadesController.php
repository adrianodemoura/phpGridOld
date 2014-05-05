<?php
/**
 * Class Cidades
 * 
 * @package			Sistema
 * @subpackage		Sistema.Controller
 */
/**
 * Include files
 */
include_once(APP.'Modules/Sistema/Controller/SistemaAppController.php');
class CidadesController extends SistemaAppController {
	/**
	 * Model Usuário
	 * 
	 * @var		array
	 */
	public $Model = array('Cidade');

	/**
	 * Exibe a lista de cidades
	 *
	 * @return void
	 */
	public function listar()
	{
		parent::lista();
		$this->viewVars['botoesLista']['0'] = array();
		$this->viewVars['botoesLista']['1'] = array();
	}
}
