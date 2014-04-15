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

			if (isset($data['BAIRROS']))
			{
				$menu['Bairros']['tit'] 		= 'Bairros';
				$menu['Bairros']['link'] 		= $this->base.'sistema/bairros';
			}
			if (isset($data['CIDADES']))
			{
				$menu['Cidades']['tit'] 		= 'Cidades';
				$menu['Cidades']['link'] 		= $this->base.'sistema/cidades';
			}
			if (isset($data['CONFIGURACOES']))
			{
				$menu['Configuracoes']['tit'] 	= 'Configurações';
				$menu['Configuracoes']['link'] 	= $this->base.'sistema/configuracoes';
			}
			if (isset($data['MODULOS']))
			{
				$menu['Modulos']['tit'] 		= 'Módulos';
				$menu['Modulos']['link'] 		= $this->base.'sistema/modulos';
			}
			if (isset($data['PERFIS']))
			{
				$menu['Perfis']['tit'] 			= 'Perfis';
				$menu['Perfis']['link'] 		= $this->base.'sistema/perfis';
			}
			if (isset($data['USUARIOS']))
			{
				$menu['Usuarios']['tit'] 		= 'Usuários';
				$menu['Usuarios']['link'] 		= $this->base.'sistema/usuarios';
			}

			$this->viewVars['linksMenu'] 	= $menu;
		}
		parent::beforeRender();
	}
}
