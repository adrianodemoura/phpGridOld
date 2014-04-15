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
	 * 
	 */
	public function beforeRender()
	{
		if (isset($_SESSION['Usuario']))
		{
			$menu['BAIRROS']['tit'] 		= 'Bairros';
			$menu['BAIRROS']['link'] 		= $this->base.'sistema/bairros';
			$menu['CIDADES']['tit'] 		= 'Cidades';
			$menu['CIDADES']['link'] 		= $this->base.'sistema/cidades';
			$menu['CONFIGURACOES']['tit'] 	= 'Configurações';
			$menu['CONFIGURACOES']['link'] 	= $this->base.'sistema/configuracoes';
			$menu['MODULOS']['tit'] 		= 'Módulos';
			$menu['MODULOS']['link'] 		= $this->base.'sistema/modulos';
			$menu['PERFIS']['tit'] 			= 'Perfis';
			$menu['PERFIS']['link'] 		= $this->base.'sistema/perfis';
			$menu['USUARIOS']['tit'] 		= 'Usuários';
			$menu['USUARIOS']['link'] 		= $this->base.'sistema/usuarios';

			include_once('Model/Permissao.php');
			$Permissao = new Permissao();
			$opcs = array();
			$opcs['distinct'] 				= true;
			$opcs['where']['visualizar'] 	= 1;
			$opcs['where']['modulo']		= 'SISTEMA';
			if ($_SESSION['Usuario']['perfil']!='ADMINISTRADOR') $opcs['where']['perfil_id']		= $_SESSION['Usuario']['perfil_id'];
			$opcs['where']['controller IN'] = array('BAIRROS','CIDADES','CONFIGURACOES','MODULOS','PERFIS','USUARIOS');
			$opcs['fields'] 				= array('visualizar','controller');
			$_data = $Permissao->find('list',$opcs);
			$data  = array();
			foreach($_data as $_l => $_arrMod)
			{
				$data[$_arrMod['Permissao']['controller']] = $_arrMod['Permissao']['visualizar'];
			}
			$modelClass = $this->modelClass;
			foreach($Permissao->sqls as $_l => $_sql) array_push($this->$modelClass->sqls,$_sql);

			if ($_SESSION['Usuario']['perfil']!='ADMINISTRADOR')
			{

				foreach($menu as $_cad => $_arrProp)
				{
					if (!isset($data[$_cad])) unset($menu[$_cad]);
				}
			}

			$this->viewVars['linksMenu'] 	= $menu;
		}
		parent::beforeRender();
	}
}
