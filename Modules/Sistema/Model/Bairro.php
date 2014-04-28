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
			'tit'		=> 'Nome',
			'notEmpty'	=> true,
			'pesquisar'	=> '&'
		),
		'regional_id'		=> array
		(
			'tit'	=> 'Regional',
			'filtro'=> true,
			//'edicaoOff'		=> true,
			'emptyFiltro'	=> '-- Escolha uma Regional --',
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
		'territorio_id'	=> array
		(
			'tit'			=> 'Território',
			'filtro'		=> true,
			//'edicaoOff'		=> true,
			'emptyFiltro'	=> '-- Escolha um Território --',
			'belongsTo' 	=> array
			(
				'Territorio'=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'sistema/territorios/get_options/',
					'txtPesquisa' => 'Digite o nome do território para pesquisar ...',
				),
			),
		),
		'cidade_id'			=> array
		(
			'tit'			=> 'Cidade',
			//'edicaoOff'		=> true,
			'belongsTo' 	=> array
			(
				'Cidade'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome','uf'),
					'order'	=> array('nome','uf'),
					'ajax'	=> 'sistema/cidades/get_options/',
					'txtPesquisa' => 'Digite o nome da cidade para pesquisar ...',
				),
			),
		),
		'criado'			=> array
		(
			'tit'			=> 'Criado',
		),
		'modificado'		=> array
		(
			'tit'			=> 'Modificado',
		)
	);
}
