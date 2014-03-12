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
	 * Exibe a lista de bairros
	 * 
	 * @return	void
	 */
	public function lista()
	{
		$arr = array();
		$res = $this->Bairro->query('SELECT DISTINCT id, nome FROM regionais ORDER BY nome');
		foreach ($res as $_l => $_a)  $arr[$_a['id']] = $_a['nome'];
		$filtros['regional_id']['empty'] 	= '-- Todas as Regionais --';
		$filtros['regional_id']['options'] 	= $arr;

		$arr = array();
		$sql = 'SELECT DISTINCT territorio FROM bairros ';
		if (isset($_SESSION['Filtros']['Sistema']['Bairros']['regional_id']) && !empty($_SESSION['Filtros']['Sistema']['Bairros']['regional_id']))
		{
			$sql .= ' WHERE regional_id='.$_SESSION['Filtros']['Sistema']['Bairros']['regional_id'];
		}
		$sql .= ' ORDER BY territorio';
		$res = $this->Bairro->query($sql);
		foreach ($res as $_l => $_a)  $arr[$_a['territorio']] = $_a['territorio'];
		$filtros['territorio']['empty'] 	= '-- Todos os Territórios --';
		$filtros['territorio']['options'] 	= $arr;

		$arr = array();
		require_once('Model/Cidade.php');
		$Cidade = new Cidade();
		$res = $Cidade->query('SELECT DISTINCT id, nome FROM cidades WHERE id>2300 AND id<2311 ORDER BY nome');
		foreach ($res as $_l => $_a)  $arr[$_a['id']] = $_a['nome'];
		$filtros['cidade_id']['empty'] 	= '-- Todas as Cidades --';
		$filtros['cidade_id']['options'] 	= $arr;

		// filtros
		$this->viewVars['filtros'] 	= $filtros;
		parent::lista();
		//debug($_SESSION['Filtros']);
	}

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
