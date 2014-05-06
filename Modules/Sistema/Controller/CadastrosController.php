<?php
/**
 * Class Cadastros
 * 
 * @package			Sistema
 * @subpackage		Sistema.Controller
 */
//include_once(APP.'Modules/Sistema/Controller/SistemaAppController.php');
appUses('controller','SistemaApp');
class CadastrosController extends SistemaAppController {
	/**
	 * Model Usuário
	 * 
	 * @var		array
	 */
	public $Model = array('Cadastro');
}
