<?php
/**
 * Class Agendamentos
 * 
 * @package			Locacao
 * @subpackage		Locacao.Controller
 */
appUses('Controller','LocacaoApp');
class AgendamentosController extends LocacaoAppController {
	/**
	 * Model Usuário
	 * 
	 * @var		array
	 */
	public $Model = array('Agendamento');
}
