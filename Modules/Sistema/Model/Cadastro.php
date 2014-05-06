<?php
/**
 * Class Cadastro
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
appUses('Model','SistemaApp');
class Cadastro extends SistemaApp {
	/**
	 * Nome da tabela de bairros
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'cadastros';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * Campo principal
	 * 
	 * @var		array
	 * @access	public
	 */
	public $displayField 	= 'cadastro';

	/**
	 * Nickname para a tabela usuarios
	 * 
	 * @var		string
	 * @access	public
	 */
	public $alias		= 'Cadastro';


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
		'cadastro'	=> array
		(
			'tit'		=> 'Cadastro',
			'notEmpty'	=> true,
		),
		'titulo'	=> array
		(
			'tit'		=> 'Título',
			'notEmpty'	=> true,
			'upperOff'	=> true,
		),
		'modulo_id'		=> array
		(
			'tit'	=> 'Módulo',
			'filtro'=> true,
			'emptyFiltro'	=> '-- Escolha um Módulo --',
			'belongsTo'	=> array
			(
				'Modulo'			=> array
				(
					'key'			=> 'id',
					'fields'		=> array('id','nome'),
					'order'			=> array('nome')
				),
			)
		),
		'ativo'=> array
		(
			'tit'		=> 'Ativo',
			'filtro'	=> true,
			'options'	=> array('1'=>'Sim','0'=>'Não')
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
