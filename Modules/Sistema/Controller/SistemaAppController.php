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
		$menu['Bairros']['tit'] 		= 'Bairros';
		$menu['Bairros']['link'] 		= $this->base.'sistema/bairros';
		$menu['Cidades']['tit'] 		= 'Cidades';
		$menu['Cidades']['link'] 		= $this->base.'sistema/cidades';
		$menu['Configuracoes']['tit'] 	= 'Configurações';
		$menu['Configuracoes']['link'] 	= $this->base.'sistema/configuracoes';
		$menu['Modulos']['tit'] 		= 'Módulos';
		$menu['Modulos']['link'] 		= $this->base.'sistema/modulos';
		$menu['Perfis']['tit'] 			= 'Perfis';
		$menu['Perfis']['link'] 		= $this->base.'sistema/perfis';
		$menu['Usuarios']['tit'] 		= 'Usuários';
		$menu['Usuarios']['link'] 		= $this->base.'sistema/usuarios';

		$this->viewVars['linksMenu'] 	= $menu;
		parent::beforeIndex();
	}
}
