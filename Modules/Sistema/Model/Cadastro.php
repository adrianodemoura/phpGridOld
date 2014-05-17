<?php
/**
 * Class Cadastro
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
appUses('Model','SistemaApp');
class Cadastro extends SistemaApp {
	/**
	 * Nome da tabela de bairros
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'cadastros';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * Campo principal
	 * 
	 * @var		array
	 * @access	public
	 */
	public $displayField 	= 'nome';

	/**
	 * Propriedade de cada campo da tabela usuários
	 * 
	 * @var		array
	 * @acess	public
	 */
	public $esquema = array
	(
		'id'		=> array
		(
			'tit'	=> 'Id',
		),
		'nome'		=> array
		(
			'tit'		=> 'Cadastro',
			'notEmpty'	=> true,
			'pesquisar'	=> '&'
		),
		'titulo'	=> array
		(
			'tit'		=> 'Título',
			'notEmpty'	=> true,
			'upperOff'	=> true,
			'pesquisar'	=> '&',
		),
		'modulo_id'		=> array
		(
			'tit'	=> 'Módulo',
			'filtro'=> true,
			'emptyFiltro'	=> '-- Escolha um Módulo --',
			'belongsTo'	=> array
			(
				'Modulo'			=> array
				(
					'key'			=> 'id',
					'fields'		=> array('id','titulo'),
					'order'			=> array('titulo')
				),
			)
		),
		'ativo'=> array
		(
			'tit'		=> 'Ativo',
			'filtro'	=> true,
			'options'	=> array('1'=>'Sim','0'=>'Não'),
			'emptyFiltro'	=> '-- Ativos --',
		),
		'criado'			=> array
		(
			'tit'			=> 'Criado',
		),
		'modificado'		=> array
		(
			'tit'			=> 'Modificado',
		)
	);

	/**
	 * Executa código depois do método save
	 *
	 * - Para cada novo cadastro, uma nova permissão para o perfil GERENTE é incluída
	 *
	 * @return 	void
	 */
	public function afterSave()
	{
		if (!empty($this->ultimoId))
		{
			appUses('Model','Permissao');
			$p = new Permissao();
			$d = array();
			$d['0']['Permissao']['modulo_id'] 	= $this->data['0']['Cadastro']['modulo_id'];
			$d['0']['Permissao']['cadastro_id'] = $this->ultimoId;
			$d['0']['Permissao']['perfil_id'] 	= 2;
			$d['0']['Permissao']['visualizar'] 	= 1;
			$r = $p->save($d);
			if (!$r)
			{
				die('erro ao incluir novas permissões !!!');
			}
		}
		parent::afterSave();
	}
}
