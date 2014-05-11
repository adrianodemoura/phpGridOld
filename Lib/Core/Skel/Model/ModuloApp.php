<?php
/**
 * Class {modulo}AppModel do módulo
 * 
 * Classe Pai de todos os Models do módulo
 * 
 * @package			{modulo}
 * @subpackage		{modulo}.Model
 */
appUses('Model','Model');
class {modulo}App extends Model {
	/**
	 * Prefixo para as tabelas do módulo
	 * 
	 * @var		string
	 * @access	public
	 */
	public $prefixo = '{prefixo}_';
}
