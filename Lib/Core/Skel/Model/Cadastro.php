<?php
/**
 * Class {cadastro}
 * 
 * @package		{modulo}
 * @package		{modulo}.Model
 */
appUses('Model','{modulo}App');
class {cadastro} extends {modulo}App {
	/**
	 * Nome da tabela
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= '{tabela}';

	/**
	 * Chave primária do model
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * Propriedade de cada campo da tabela
	 * 
	 * @var		array
	 * @acess	public
	 */
	public $esquema = array
	(
		'id'			=> array
		(
			'tit'		=> 'Id',
		)
	);
}
