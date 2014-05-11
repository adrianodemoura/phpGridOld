<?php
/**
 * Class {modulo}
 * 
 * Classe Pai de todos os controllers do módulo {modulo}
 * 
 * @package		{modulo}
 * @package		{modulo}.Controller
 */
appUses('Controller','');
class {modulo}AppController extends Controller {
	/**
	 * Executa código antes da renderização da view
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
		$this->viewVars['tituloModule'] = '{titulo_modulo}';
		parent::beforeRender();
	}
}
