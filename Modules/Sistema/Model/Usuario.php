<?php
/**
 * Class Usuario
 * 
 * @package			Sistema
 * @subpackage		Sistema.Model
 */
/**
 * Include files
 */
require_once('Model/SistemaAppModel.php');
class Usuario extends SistemaAppModel {
	/**
	 * Nome da tabela de usuários
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'usuarios';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey = array('id');

	/**
	 * Nickname para a tabela usuarios
	 * 
	 * @var		string
	 * @access	public
	 */
	public $alias		= 'Usuario';

	/**
	 * Relacionamento Habtm
	 * 
	 * - O nome da tabela de ligação já deve levar o prefixo caso possua, a regra foi definida assim para que haja ligação entre os módulos
	 * 
	 * @var		array
	 * @access	public
	 */
	public $habtm	= array
	(
		'Perfil'	=> array
		(
			'table'	=> 'sis_usuarios_perfis',
			'key'	=> array('usuario_id'),
			'tableFk'=> 'sis_perfis',
			'keyFk'	=> array('perfil_id'),
		)
	);

	/**
	 * Propriedade de cada campo da tabela usuários
	 * 
	 * @var		array
	 * @acess	public
	 */
	public $esquema 	= array
	(
		'id'			=> array
		(
			'tit'		=> 'Id'
		),
		'nome'			=> array
		(
			'tit'		=> 'Nome',
			'notEmpty'	=> true,
			'pesquisar'	=> true
		),
		'ativo'=> array
		(
			'tit'		=> 'Ativo',
			'filtro'	=> true,
			'options'	=> array('1'=>'Sim','0'=>'Não'),
			'emptyFiltro'	=> '-- Ativos --',
		),
		'acessos'		=> array
		(
			'tit'		=> 'Acessos',
			'type'		=> 'numeric',
			'edicaoOff'	=> true
		),
		'email'	=> array
		(
			'tit'		=> 'e-mail',
			'upperOff'	=> true,
		),
		'celular'		=> array
		(
			'tit'		=> 'Celular',
			'mascara'	=> '(##)####-####',
		),
		'trocar_senha'	=> array
		(
			'tit'		=> 'Trocar Senha',
			'options'	=> array('1'=>'Sim','0'=>'Não')
		),
		'cidade_id'		=> array
		(
			'tit'		=> 'Cidade',
			'belongsTo' 	=> array
			(
				'Cidade'	=> array
				(
					'key'	=> 'id',
					'fields'=> array('id','nome','uf'),
					'order'	=> array('nome','uf'),
					'ajax'	=> 'sistema/cidades/get_options/',
					'txtPesquisa' => 'Digite o nome da cidade para pesquisar ...',
				),
			),
		),
		'ultimo_ip'	=> array
		(
			'tit'		=> 'Último IP',
			'edicaoOff'	=>true
		),
		'senha'			=> array
		(
			'tit'		=> 'Senha',
			'type'		=> 'password',
		),
		'ultimo_acesso'	=> array
		(
			'tit'		=> 'Último Acesso',
		),
	);

	/**
	 * Autentica o usuário no banco de dados
	 * 
	 * @param	string	$e	e-mail
	 * @param	string	$s	Senha
	 */
	public function autentica($e='', $s='')
	{
		$s = md5($s.SALT);
		$opcs = array();
		$opcs['where']['email'] = $e;
		$opcs['where']['senha'] = $s;
		$data = $this->find('all',$opcs);
		return $data;
	}

	/**
	 * Executa código antes de excluir um usuário no banco
	 *
	 * - Usuário administrador não pode ser excluído
	 * 
	 * @return boolean
	 */
	public function beforeExclude()
	{
		if (isset($this->data['0'][$this->name]['id']) && $this->data['0'][$this->name]['id']==1)
		{
			$this->erro = 'O Usuário Administrador não pode ser excluído !!!';
			return false;
		}
		return true;
	}

	/**
	 * Executa código antes da de salvar no banco
	 * Caso a senha seja passada, a mesma será encriptada
	 * 
	 * @return boolean
	 */
	public function beforeSave()
	{
		foreach($this->data as $_l => $_arrMods)
		{
			// criptografando a senha
			if (isset($_arrMods['Usuario']['senha']))
			{
				if (!empty($_arrMods['Usuario']['senha']))
				{
					$this->data[$_l]['Usuario']['senha'] = md5($_arrMods['Usuario']['senha'].SALT);
				} else
				{
					unset($this->data[$_l]['Usuario']['senha']);
				}
			}
			// admin sempre ativo
			if (isset($_arrMods['Usuario']['ativo']) && isset($_arrMods['Usuario']['id']))
			{
				if ($_arrMods['Usuario']['id']=='1') $this->data[$_l]['Usuario']['ativo'] = 1;
			}
			// removendo acessos
			if (isset($_arrMods['Usuario']['Acessos']))
			{
				//unset($this->data[$_l]['Usuario']['Acessos']);
			}
		}
		return parent::beforeSave();
	}
}
