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
		'territorio'	=> array
		(
			'tit'	=> 'Território',
		),
		'regional_id'		=> array
		(
			'tit'	=> 'RegionalId',
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
		'cidade_id'	=> array
		(
			'tit'	=> 'CidadeId',
			'belongsTo' => array
			(
				'Cidade'			=> array
				(
					'key'			=> 'id',
					'fields'		=> array('nome','uf'),
					'order'			=> array('nome')
				),
			),
		)
	);

	/**
	 * Relacionamento 1:n
	 * 
	 * @var		array
	 * @access	public
	 */
	/*public $belongsTo	= array
	(
		'Cidade'			=> array
		(
			'foreignKey'	=> 'cidade_id',
			'key'			=> 'id',
			'fields'		=> array('nome','uf'),
			'order'			=> array('nome')
		),
		'Regional'			=> array
		(
			'foreignKey'	=> 'regional_id',
			'key'			=> 'id',
			'fields'		=> array('id','nome'),
			'order'			=> array('nome')
		),
	);*/
}
