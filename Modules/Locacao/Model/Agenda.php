<?php
/**
 * Class Agendamento
 * 
 * @package		Locacao
 * @package		Locacao.Model
 */
appUses('Model','LocacaoApp');
class Agenda extends LocacaoApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'agendas';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * chave que NÃO pode repetir
	 * - Esta chave será será testada no método validade
	 *
	 * @var 	array
	 * @access  public
	 */
	public $uniqueKey	= array
	(
		'0' => array
		(
			'msg' 		=> 'A sala {Sala.titulo} já foi agendada por {Usuario.nome} nesta mesmo dia e horário !!!',
			'fields' 	=> array('data','sala_id'),
		),
	);

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
		'motivo'		=> array
		(
			'tit'		=> 'Motivo',
			'notEmpty' 	=> true,
			'pesquisar'	=> '&',
		),
		'data'			=> array
		(
			'tit'		=> 'Data',
			'mascara'	=> '99/99/9999 99:99:99',
			'mascEdit'	=> array('d','m','y','h','i'),
			'multMinu'	=> 30,
		),
		'sala_id'			=> array
		(
			'tit'			=> 'Sala',
			'notEmpty'		=> true,
			'filtro'		=> true,
			'emptyFiltro'	=> '-- Salas --',
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
			'tit'			=> 'Solicitante',
			'notEmpty'		=> true,
			'filtro'		=> true,
			'emptyFiltro'	=> '-- Solicitantes --',
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
		'criado'		=> array
		(
			'tit'		=> 'Criada',
		),
		'modificado'		=> array
		(
			'tit'		=> 'Modificada',
		),
	);
}
