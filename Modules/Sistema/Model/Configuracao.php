<?php
/**
 * Class Configuracao
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
/**
 * Include files
 */
require_once('Model/SistemaAppModel.php');
class Configuracao extends SistemaAppModel {
	/**
	 * Nome da tabela
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'configuracoes';

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
	public $alias		= 'Configuracao';

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
		'empresa'	=> array
		(
			'tit'	=> 'Empresa',
		),
		'email'		=> array
		(
			'tit'	=> 'e-mail',
			'upperOff'=> true,
		),
		'cep'		=> array
		(
			'tit'	=> 'Cep',
			'mascara'=> '##.###-###',
		),
		'tel1'		=> array
		(
			'tit'	=> 'Telefone1',
			'mascara'=> '(##)####-####',
		),
		'tel2'		=> array
		(
			'tit'	=> 'Telefone2',
			'mascara'=> '(##)####-####',
		),
		'celular'		=> array
		(
			'tit'	=> 'Celular',
			'mascara'=> '(##)####-####',
		),
		'sql_dump'=> array
		(
			'tit'		=> 'SqlDump',
			'options'	=> array('1'=>'Sim','0'=>'Não')
		)
	);

	/**
	 * Executa código antes de excluir a configuração no banco 
	 *
	 * - A configuração não pode ser excluída só alterada
	 * 
	 * @return boolean
	 */
	public function beforeExclude()
	{
		if (isset($this->data['0'][$this->name]['id']) && $this->data['0'][$this->name]['id']==1)
		{
			$this->erro = 'A Configuração não pode ser excluída !!!';
			return false;
		}
		return true;
	}
}
