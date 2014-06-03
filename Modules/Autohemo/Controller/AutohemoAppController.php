<?php
/**
 * Class AutohemoAppController
 * 
 * Classe Pai de todos os controllers do módulo Autohemo
 * 
 * @package		Autohemo
 * @package		Autohemo.Controller
 */
appUses('Controller','');
class AutohemoAppController extends Controller {
	/**
	 * Executa código depois da action e antes da renderização da view
	 *
	 * @return 	void
	 */
	public function beforeRender()
	{
		$this->viewVars['tituloModule'] = 'Controle Autohemoterapia';
		parent::beforeRender();
	}
}
