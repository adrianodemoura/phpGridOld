<?php
/**
 * Class Controle
 * 
 * @package		Controle
 * @package		ControlesaModel
 */
appUses('Model','ControlesaApp');
class Controle extends ControlesaApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'controles';

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
		'dt_aplicacao'		=> array
		(
			'tit'			=> 'dtAplicação',
			'notEmpty'		=> true,
			'mascEdit'		=> array('d','m','y','h','i'),
			'multMinu'		=> 5,
		),
		'qt_retirada'		=> array
		(
			'tit'			=> 'Retirada',
		),
		'local_aplicado'	=> array
		(
			'tit'			=> 'Local',
		),
		'paciente_id'		=> array
		(
			'tit'			=> 'Paciente',
			'filtro'		=> true,
			'emptyFiltro'	=> '-- Todos os Pacientes --',
			'belongsTo' 	=> array
			(
				'Paciente'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'controlesa/pacientes/get_options/',
					'txtPesquisa' => 'Digite o nome do paciente para pesquisar ...',
				),
			),
		),
	);
}
