<?php
/**
 * Class Aplicacoes
 * 
 * @package			Aplicacoes
 * @subpackage		Autohemo.Controller
 */
appUses('Controller','AutohemoApp');
class AplicacoesController extends AutohemoAppController {
	/**
	 * Model
	 * 
	 * @var		array
	 * @access 	public
	 */
	public $Model = array('Aplicacao');

	/**
	 * Exibe a lista
	 *
	 * - Usuário com perfil acima de 2, só enxerga os deles mesmo
	 * 
	 * @return 	void
	 */
	public function listar()
	{
		if ($_SESSION['Usuario']['perfil_id']>2)
		{
			$this->filtros['Aplicacao.usuario_id'] = $_SESSION['Usuario']['id'];
		}
		parent::listar();
	}
}
