<?php
/**
 * Class Permissao
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
appUses('Model','SistemaApp');
class Permissao extends SistemaApp {
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
		'modulo_id'=> array
		(
			'tit'		=> 'Módulo',
			'belongsTo' 	=> array
			(
				'Modulo'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'sistema/modulos/get_options/',
					'txtPesquisa' => 'Digite o nome do Módulo para pesquisar ...',
				),
			),
		)
		'cadastro_id'=> array
		(
			'tit'		=> 'Cadastro',
			'belongsTo' 	=> array
			(
				'Cadastro'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'sistema/cadastros/get_options/',
					'txtPesquisa' => 'Digite o nome do Cadastro para pesquisar ...',
				),
			),
		)
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
