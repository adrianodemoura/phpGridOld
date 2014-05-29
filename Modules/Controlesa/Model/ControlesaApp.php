<?php
/**
 * Class ControlesaApp do módulo controlesa
 * 
 * Classe Pai de todos os Models do módulo controlesa
 * 
 * @package			Sistema
 * @subpackage		Sistema.Model
 */
appUses('Model','Model');
class ControlesaApp extends Model {
	/**
	 * Prefixo para as tabelas do módulo controlesa
	 * 
	 * @var		string
	 * @access	public
	 */
	public $prefixo = 'con_';
}
