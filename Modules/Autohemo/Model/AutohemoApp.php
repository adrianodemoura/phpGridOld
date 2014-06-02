<?php
/**
 * Class ControlesaApp 
 * 
 * Classe Pai de todos os Models do módulo Autohemoterapia
 * 
 * @package			Autohemo
 * @subpackage		Autohemoterapia.Model
 */
appUses('Model','Model');
class AutohemoApp extends Model {
	/**
	 * Prefixo para as tabelas do módulo controlesa
	 * 
	 * @var		string
	 * @access	public
	 */
	public $prefixo = 'hem_';
}
