<?php
/**
 * Class SistemaApp
 * 
 * Classe Pai de todos os controllers do módulo sistema
 * 
 * @package		Sistema
 * @package		Sistema.Controller
 */
/**
 * Include files
 */
include_once(CORE.'Controller/Controller.php');
class SistemaAppController extends Controller {
	/**
	 * Executa código antes de action solicitada
	 * 
	 * @return	void
	 */
	public function beforeIndex()
	{
		$menu['Usuarios']['tit'] 		= 'Usuários';
		$menu['Usuarios']['link'] 		= $this->base.'sistema/usuarios';
		$menu['Cidades']['tit'] 		= 'Cidades';
		$menu['Cidades']['link'] 		= $this->base.'sistema/cidades';
		$menu['Perfis']['tit'] 			= 'Perfis';
		$menu['Perfis']['link'] 		= $this->base.'sistema/perfis';
		$menu['Modulos']['tit'] 		= 'Módulos';
		$menu['Modulos']['link'] 		= $this->base.'sistema/modulos';
		$menu['Configuracoes']['tit'] 	= 'Configurações';
		$menu['Configuracoes']['link'] 	= $this->base.'sistema/configuracoes';

		$this->viewVars['linksMenu'] 	= $menu;
		parent::beforeIndex();
	}
}
