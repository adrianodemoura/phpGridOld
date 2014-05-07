<?php
/**
 * Class Salas
 * 
 * @package			Locacao
 * @subpackage		Locacao.Controller
 */
appUses('Controller','LocacaoApp');
class SalasController extends LocacaoAppController {
	/**
	 * Model Usuário
	 * 
	 * @var		array
	 */
	public $Model = array('Sala');
}
