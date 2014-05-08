<?php
/**
 * Class Salas
 * 
 * @package		Locacao
 * @package		Locacao.Model
 */
appUses('Model','LocacaoApp');
class Sala extends LocacaoApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'salas';

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
		'titulo'		=> array
		(
			'tit'		=> 'Nome',
			'notEmpty' 	=> true,
			'pesquisar'	=> '&',
		),
		'ativo'=> array
		(
			'tit'		=> 'Ativo',
			'filtro'	=> true,
			'options'	=> array('1'=>'Sim','0'=>'Não'),
			'emptyFiltro'	=> '-- Ativos --',
		),
	);

	/**
	 * Executa código antes de salvar uma nova sala
	 *
	 * @return void
	 */
	public function beforeSave()
	{
		foreach($this->data as $_l => $_arrMods)
		{
			if (isset($_arrMods['Sala']['numero']))
			{
				if (empty($_arrMods['Sala']['numero']))
				{
					$this->data[$_l]['Sala']['numero'] = '0';
				}
			}
		}
		return parent::beforeSave();
	}


}
