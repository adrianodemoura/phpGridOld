<?php
/**
 * Class Modulo
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
/**
 * Include files
 */
require_once(APP.'Modules/Sistema/Model/SistemaAppModel.php');
class Modulo extends SistemaAppModel {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'modulos';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * Nickname para a tabela usuarios
	 * 
	 * @var		string
	 * @access	public
	 */
	public $alias		= 'Modulo';

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
		'nome'		=> array
		(
			'tit'	=> 'Nome',
		),
		'ativo'=> array
		(
			'tit'		=> 'Ativo',
			'options'	=> array('1'=>'Sim','0'=>'Não')
		),
	);

	/**
	 * Executa código antes da de salvar no banco
	 * 
	 * - Módulo sistema sempre ativo
	 * 
	 * @return boolean
	 */
	public function beforeSave()
	{
		foreach($this->data as $_l => $_arrMods)
		{
			// sistema sempre ativo
			if (isset($_arrMods['Modulo']['ativo']) && isset($_arrMods['Modulo']['id']))
			{
				if ($_arrMods['Modulo']['id']=='1') $this->data[$_l]['Modulo']['ativo'] = 1;
			}
		}
		return parent::beforeSave();
	}

	/**
	 * Executa código antes de excluir um módulo no banco
	 *
	 * - Módulo SISTEMA não pode ser excluído
	 * 
	 * @return boolean
	 */
	public function beforeExclude()
	{
		if (isset($this->data['0'][$this->name]['id']) && $this->data['0'][$this->name]['id']==1)
		{
			$this->erro = 'O Módulo SISTEMA não pode ser excluído !!!';
			return false;
		}
		return true;
	}
}
