<?php
/**
 * Class Controles
 * 
 * @package			Controles
 * @subpackage		Autohemoterapia.Controller
 */
appUses('Controller','AutohemoApp');
class ControlesController extends AutohemoAppController {
	/**
	 * Model Controle
	 * 
	 * @var		array
	 */
	public $Model = array('Controle');

	/**
	 * Exibe a lista de controlle 
	 *
	 * - Usuário com perfil acima de 3, só enxerga os deles mesmo
	 * 
	 * @return 	void
	 */
	public function listar()
	{
		if ($_SESSION['Usuario']['perfil_id']>2)
		{
			$this->filtros['Controle.usuario_id'] = $_SESSION['Usuario']['id'];
		}
		parent::listar();
	}
}
