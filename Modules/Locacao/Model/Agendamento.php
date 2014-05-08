<?php
/**
 * Class Agendamento
 * 
 * @package		Locacao
 * @package		Locacao.Model
 */
appUses('Model','LocacaoApp');
class Agendamento extends LocacaoApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'agendamentos';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * Propriedade de cada campo da tabela usuários
	 * 
	 * @var		array
	 * @acess	public
	 */
	public $esquema = array
	(
		'id'			=> array
		(
			'tit'		=> 'Id',
		),
		'obs'		=> array
		(
			'tit'		=> 'Obs',
			'notEmpty' 	=> true,
			'pesquisar'	=> '&',
		),
		'data'			=> array
		(
			'tit'		=> 'Data',
			'mascara'	=> '99/99/9999 99:99:99',
		),
		'sala_id'			=> array
		(
			'tit'			=> 'Sala',
			'notEmpty'		=> true,
			'belongsTo' 	=> array
			(
				'Sala'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','titulo'),
					'order'	=> array('titulo'),
					'ajax'	=> 'locacao/salas/get_options/',
					'txtPesquisa' => 'Digite a sala para pesquisar ...',
				),
			),
		),
		'usuario_id'		=> array
		(
			'tit'			=> 'Usuário',
			'notEmpty'		=> true,
			'belongsTo' 	=> array
			(
				'Sistema.Usuario'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'sistema/usuarios/get_options/',
					'txtPesquisa' => 'Digite o nome do usuário para pesquisar ...',
				),
			),
		),
	);
}
