<?php
/**
 * Class Bairro
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
/**
 * Include files
 */
require_once(APP.'Modules/Sistema/Model/SistemaAppModel.php');
class Bairro extends SistemaAppModel {
	/**
	 * Nome da tabela de bairros
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'bairros';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * Nickname para a tabela usuarios
	 * 
	 * @var		string
	 * @access	public
	 */
	public $alias		= 'Bairro';

	/**
	 * Propriedade de cada campo da tabela usuários
	 * 
	 * @var		array
	 * @acess	public
	 */
	public $esquema = array
	(
		'id'		=> array
		(
			'tit'	=> 'Id',
		),
		'nome'		=> array
		(
			'tit'	=> 'Nome',
		),
		'regional_id'		=> array
		(
			'tit'	=> 'RegionalId',
			'filtro'=> true,
			'belongsTo'	=> array
			(
				'Regional'			=> array
				(
					'key'			=> 'id',
					'fields'		=> array('id','nome'),
					'order'			=> array('nome')
				),
			)
		),
		'territorio'	=> array
		(
			'tit'			=> 'Território',
			'filtro'		=> true,
			'optionsFunc' 	=> 'getTerritorios',
			'optionsCache'	=> true,
		),
		'cidade_id'			=> array
		(
			'tit'			=> 'CidadeId',
			'filtro'		=> false,
			'belongsTo' 	=> array
			(
				'Cidade'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','uf','nome'),
					'order'	=> array('nome','uf'),
					'cache'	=> true,
					'ajax'	=> 'sistema/cidades/get_options/',
				),
			),
		)
	);

	/**
	 * Retorna uma lista de territŕoios
	 *
	 * @param integer 	$idReg 	Id da regional
	 * @return array
	 */
	public function getTerritorios($id_reg=0)
	{
		$arr = array();
		$sql = 'SELECT DISTINCT territorio FROM bairros';
		if (!empty($id_reg)) $sql .= ' WHERE regional_id='.$id_reg;
		$sql .= ' ORDER BY territorio';
		$res = $this->query($sql);
		foreach($res as $_l => $_a)  $arr[$_a['territorio']] = $_a['territorio'];
		return $arr;
	}
}
