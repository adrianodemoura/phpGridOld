<?php
/**
 * Class Pacientes
 * 
 * @package			Pacientes
 * @subpackage		Autohemoterapia.Controller
 */
appUses('Controller','AutohemoApp');
class PacientesController extends AutohemoAppController {
	/**
	 * Model Controle
	 * 
	 * @var		array
	 * @access 	public
	 */
	public $Model = array('Paciente');
}
