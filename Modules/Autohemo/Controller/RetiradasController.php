<?php
/**
 * Class Retiradas
 * 
 * @package			Retiradas
 * @subpackage		Autohemo.Controller
 */
appUses('Controller','AutohemoApp');
class RetiradasController extends AutohemoAppController {
	/**
	 * Model Controle
	 * 
	 * @var		array
	 */
	public $Model = array('Retirada');

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
			$this->filtros['Retirada.usuario_id'] = $_SESSION['Usuario']['id'];
		}
		$this->viewVars['fields'] = array('Retirada.data'
		,'Retirada.reti_qtd'
		,'Retirada.usuario_id'
		,'Retirada.local_id'
		,'Retirada.Aplicacao');
		parent::listar();
	}
}
