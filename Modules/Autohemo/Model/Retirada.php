<?php
/**
 * Class Retirada
 * 
 * @package		Retirada
 * @package		Autohemo.model
 */
appUses('Model','AutohemoApp');
class Retirada extends AutohemoApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'retiradas';

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
		'data'		=> array
		(
			'tit'			=> 'dtRetirada',
			'notEmpty'		=> true,
			'mascEdit'		=> array('d','m','y','h','i'),
			'multMinu'		=> 5,
			'default' 		=> 'agora',
		),
		'reti_qtd'			=> array
		(
			'tit'			=> 'qtd. Retirada',
			'options'		=> array('2.5'=>'2,5ml', '5.00'=>'5ml', '10.00'=>'10ml', '15.00'=>'15ml', '20.00'=>'20ml')
		),
		'usuario_id'		=> array
		(
			'tit'			=> 'Paciente',
			'filtro'		=> true,
			'emptyFiltro'	=> '-- Todos os Pacientes --',
			'belongsTo' 	=> array
			(
				'Sistema.Usuario'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'sistema/usuarios/get_options/',
					'txtPesquisa' => 'Digite o nome do paciente para pesquisar ...',
				),
			),
		),
		'local_id'			=> array
		(
			'tit'			=> 'Local',
			'filtro'		=> true,
			'emptyFiltro'	=> '-- Todos os Locais de Retirada --',
			'belongsTo' 	=> array
			(
				'Local'		=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'autohemo/locais/get_options/',
					'txtPesquisa' => 'Digite o nome do local para pesquisar ...',
				),
			),
		),
		'Aplicacao'		=> array
		(
			'tit'			=> 'Aplicações',
			'type' 			=> 'habtm',
			'table'			=> 'hem_retiradas_aplicacoes',
			'key'			=> array('retirada_id'),
			'tableFk'		=> 'hem_aplicacoes',
			'keyFk'			=> array('aplicacao_id'),
			'modFk'			=> 'Aplicacao',
			'optionsFk'		=> array
			(
				'cadastro'	=> 'autohemo/aplicacoes',
				'key'		=> 'Aplicacao.apli_qtd',
				'fields'	=> 'Aplicacao.id,Aplicacao.apli_qtd',
				'ord'		=> 'Aplicacao.apli_qtd',
			),
		)
	);
}
