<?php
/**
 * Class Paciente
 * 
 * @package		Paciente
 * @package		Controlesa.Model
 */
appUses('Model','AutohemoApp');
class Paciente extends AutohemoApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'pacientes';

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
			'pesquisar'		=> '&'
		),
		'email'	=> array
		(
			'tit'			=> 'e-mail',
			'upperOff'		=> true,
			'pesquisar'		=> '&',
			'unique'		=> true,
		),
		'cidade_id'			=> array
		(
			'tit'			=> 'Cidade',
			'belongsTo' 	=> array
			(
				'Sistema.Cidade'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome','uf'),
					'order'	=> array('nome','uf'),
					'ajax'	=> 'sistema/cidades/get_options/',
					'txtPesquisa' => 'Digite o nome da cidade para pesquisar ...',
				),
			),
		),
	);
}
