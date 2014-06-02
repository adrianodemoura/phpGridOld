<?php
/**
 * Class Controle
 * 
 * @package		Controle
 * @package		Autohemo.model
 */
appUses('Model','AutohemoApp');
class Controle extends AutohemoApp {
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
					'ajax'	=> 'autohemo/pacientes/get_options/',
					'txtPesquisa' => 'Digite o nome do paciente para pesquisar ...',
				),
			),
		),

		'retirada_qtd'		=> array
		(
			'tit'			=> 'qtd. Retirada',
			'options'		=> array('5'=>'5ml', '10'=>'10ml', '15'=>'15ml', '20'=>'20ml')
		),
		'retirada_loc'		=> array
		(
			'tit'			=> 'Local da Retirada',
			'options'		=> array
			(
				'braço direito'		=> 'Braço Direito', 
				'braço esquerdo'	=> 'Braço Esquerdo', 
			),
		),

		'local_qtd'		=> array
		(
			'tit'			=> 'qtd. Aplicada',
			'options'		=> array('5'=>'5ml', '10'=>'10ml', '15'=>'15ml', '20'=>'20ml')
		),
		'local_apl'	=> array
		(
			'tit'			=> 'Local de Aplicação',
			'upperOff'		=> true,
			'options'		=> array
			(
				'braço direito'		=> 'Braço Direito', 
				'braço esquerdo'	=> 'Braço Esquerdo', 

				'nádega direito'	=> 'Nádega Direita', 
				'nádega esquerda'	=> 'Nádega Esquerda', 

				'coxa direito'		=> 'Coxa Direita', 
				'coxa esquerda'		=> 'Coxa Esquerda', 
			)
		),
	);
}
