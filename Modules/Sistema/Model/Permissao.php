<?php
/**
 * Class Permissao
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
require_once(APP.'Modules/Sistema/Model/SistemaAppModel.php');
class Permissao extends SistemaAppModel {
	/**
	 * Nome da tabela
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'permissoes';

	/**
	 * Chave primária
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
		'id'		=> array
		(
			'tit'	=> 'Id',
		),
		'modulo'		=> array
		(
			'tit'	=> 'Módulo'
		),
		'controller'=> array
		(
			'tit'	=> 'Cadastro'
		),
		'perfil_id'=> array
		(
			'tit'		=> 'Perfil',
			'belongsTo' 	=> array
			(
				'Perfil'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'sistema/perfis/get_options/',
					'txtPesquisa' => 'Digite o nome do perfil para pesquisar ...',
				),
			),
		)
	);
}
