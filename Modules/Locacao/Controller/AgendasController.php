<?php
/**
 * Class Agendamentos
 * 
 * @package			Locacao
 * @subpackage		Locacao.Controller
 */
appUses('Controller','LocacaoApp');
class AgendasController extends LocacaoAppController {
	/**
	 * Model Usuário
	 * 
	 * @var		array
	 */
	public $Model = array('Agenda');
}
