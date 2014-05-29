<?php
/**
 * Class Pacientes
 * 
 * @package			Pacientes
 * @subpackage		Controlesa.Controller
 */
appUses('Controller','ControlesaApp');
class PacientesController extends ControlesaAppController {
	/**
	 * Model Controle
	 * 
	 * @var		array
	 */
	public $Model = array('Paciente');
}
