<?php
/**
 * Class ControlesaApp
 * 
 * Classe Pai de todos os controllers do módulo Autohemoterapia
 * 
 * @package		Controlesa
 * @package		Autohemoterapia.Controller
 */
appUses('Controller','');
class AutohemoAppController extends Controller {
	/**
	 * 
	 */
	public function beforeRender()
	{
		$this->viewVars['tituloModule'] = 'Controle Autohemoterapia';
		parent::beforeRender();
	}
}
