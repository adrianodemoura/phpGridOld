<?php
/**
 * Class ControlesaApp
 * 
 * Classe Pai de todos os controllers do módulo locação
 * 
 * @package		Controlesa
 * @package		Controlesa.Controller
 */
appUses('Controller','');
class ControlesaAppController extends Controller {
	/**
	 * 
	 */
	public function beforeRender()
	{
		$this->viewVars['tituloModule'] = 'Controle de Sangue';
		parent::beforeRender();
	}
}
