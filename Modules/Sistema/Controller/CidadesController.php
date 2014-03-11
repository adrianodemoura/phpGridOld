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
	public function lista()
	{
		$est = array();
		$res = $this->Cidade->query('SELECT DISTINCT uf FROM cidades ORDER BY uf');
		foreach ($res as $_l => $_a)  $est[$_a['uf']] = $_a['uf'];
		$filtros['uf']['empty'] 	= '-- Todos Estados --';
		$filtros['uf']['options'] 	= $est;
		$this->viewVars['filtros'] 	= $filtros;
		parent::lista();
	}
}
