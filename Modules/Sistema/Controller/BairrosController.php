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
}
