<?php
/**
 * Class Aplicacao
 * 
 * @package		Aplicacao
 * @package		Autohemo.Model
 */
appUses('Model','AutohemoApp');
class Aplicacao extends AutohemoApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'aplicacoes';

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
		'apli_qtd'			=> array
		(
			'tit'			=> 'qtd.Aplicada',
			'options'		=> array('2.50'=>'2,5ml', '5.00'=>'5ml', '10.00'=>'10ml', '15.00'=>'15ml', '20.00'=>'20ml')
		),
		'data'				=> array
		(
			'tit' 			=> 'dt.Aplicação',
			'notEmpty'		=> true,
			'mascEdit'		=> array('d','m','y','h','i'),
			'multMinu'		=> 5,
		),
		'local_id'			=> array
		(
			'tit'			=> 'Local',
			'filtro'		=> true,
			'notEmpty'		=> true,
			'emptyFiltro'	=> '-- Todos os Locais de Aplicação --',
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
		'usuario_id'			=> array
		(
			'tit'			=> 'Paciente',
			'filtro'		=> true,
			'notEmpty'		=> true,
			'emptyFiltro'	=> '-- Todos os Pacientes --',
			'belongsTo' 	=> array
			(
				'Sistema.Usuario'		=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome'),
					'order'	=> array('nome'),
					'ajax'	=> 'sistema/usuarios/get_options/',
					'txtPesquisa' => 'Digite o nome do Paciente para pesquisar ...',
				),
			),
		),
	);

	/**
	 * Executa código depois do método delete
	 *
	 * @return 	void
	 */
	public function afterExclude()
	{
		foreach($this->data as $_l => $_arrMods);
		{
			$id = isset($_arrMods['Aplicacao']['id']) ? $_arrMods['Aplicacao']['id'] : null;
			if ($id)
			{
				$sql = 'DELETE FROM hem_retiradas_aplicacoes WHERE aplicacao_id='.$id;
				$this->query($sql);
			}
		}
	}
}
