<?php
/**
 * Class Local
 * 
 * @package		Local
 * @package		Autohemo.Model
 */
appUses('Model','AutohemoApp');
class Local extends AutohemoApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'locais';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * Propriedade de cada campo da tabela salas
	 * 
	 * @var		array
	 * @acess	public
	 */
	public $esquema = array
	(
		'id'				=> array
		(
			'tit'			=> 'Id',
		),
		'nome'				=> array
		(
			'tit'			=> 'Nome',
			'notEmpty'		=> true,
			'pesquisar'		=> '&',
			'upperOff'		=> true,
		)
	);
}
