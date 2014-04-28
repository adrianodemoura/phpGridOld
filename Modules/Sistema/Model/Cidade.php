<?php
/**
 * Class Cidade
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
/**
 * Include files
 */
require_once(APP.'Modules/Sistema/Model/SistemaAppModel.php');
class Cidade extends SistemaAppModel {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'cidades';

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
	public $alias		= 'Cidade';

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
			'edicaoOff'=>true,
			'pesquisar'=>true,
		),
		'uf'		=> array
		(
			'tit'	=> 'Uf',
			'filtro'=> true,
			'optionsFunc'=>'getUfs',
			'edicaoOff'=>true,
		)
	);

	/**
	 * Executa código antes da de excluir uma cidade no banco
	 * - Nenhuma cidade pode ser excluída
	 * 
	 * @return boolean
	 */
	public function getUfs()
	{
		$arr = array();
		$sql = 'SELECT DISTINCT uf FROM sis_cidades ORDER BY uf';
		$res = $this->query($sql);
		foreach($res as $_l => $_a)  $arr[$_a['uf']] = $_a['uf'];
		return $arr;
	}

	/**
	 * Executa código antes da de excluir uma cidade no banco
	 * - Nenhuma cidade pode ser excluída
	 * 
	 * @return boolean
	 */
	public function beforeExclude()
	{
		$this->erro = 'Nenhuma Cidade pode ser excluída !!!';
		return false;
		//return parent::beforeExclude();
	}
}
