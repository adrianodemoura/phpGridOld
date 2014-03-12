<?php
/**
 * Class Bairros
 * 
 * @package			Sistema
 * @subpackage		Sistema.Controller
 */
/**
 * Include files
 */
include_once(APP.'Modules/Sistema/Controller/SistemaAppController.php');
class BairrosController extends SistemaAppController {
	/**
	 * Model Usuário
	 * 
	 * @var		array
	 */
	public $Model = array('Bairro');

	/**
	 * 
	 */
	public function lista()
	{
		$ter = array();
		$res = $this->Bairro->query('SELECT DISTINCT territorio FROM bairros ORDER BY territorio');
		foreach ($res as $_l => $_a)  $ter[$_a['territorio']] = $_a['territorio'];

		$filtros['territorio']['empty'] 	= '-- Todos --';
		$filtros['territorio']['options'] 	= $ter;
		$this->viewVars['filtros'] 	= $filtros;
		parent::lista();
	}
}
