<?php
/**
 * Class Modulos
 * 
 * @package			Sistema
 * @subpackage		Sistema.Controller
 */
/**
 * Include files
 */
include_once(APP.'Modules/Sistema/Controller/SistemaAppController.php');
class ModulosController extends SistemaAppController {
	/**
	 * Model Perfil
	 * 
	 * @var		array
	 */
	public $Model = array('Modulo');

	/**
	 * Configurando a página
	 *
	 * @return void
	 */
	public function beforeIndex()
	{
		$this->viewVars['tituloPagina'] 	= 'Módulos do Sistema '.SISTEMA;
		$this->viewVars['tituloController'] = 'Módulos';
		parent::beforeIndex();
	}
}
