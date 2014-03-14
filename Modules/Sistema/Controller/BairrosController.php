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

	/**
	 * Configura o filtro da lista
	 * 
	 * @return	void
	 */
	public function set_filtro()
	{
		$regAntiga 	= isset($_SESSION['Filtros']['Sistema']['Bairros']['regional_id']) 
			? $_SESSION['Filtros']['Sistema']['Bairros']['regional_id'] 
			: null;
		$regAtual	= isset($_POST['filtro']['regional_id']) 
			? $_POST['filtro']['regional_id'] 
			: null;

		// limpando territórios pela regional
		if ($regAntiga!=$regAtual)
		{
			unset($_SESSION['Filtros']['Sistema']['Bairros']['territorio']);
			unset($_SESSION['Filtros']['Sistema']['Bairros']['cidade_id']);
			unset($_POST['filtro']['territorio']);
			unset($_POST['filtro']['cidade_id']);
		}
		parent::set_filtro();
	}
	
}
