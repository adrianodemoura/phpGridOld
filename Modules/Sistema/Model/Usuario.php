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
	 * Propriedade de cada campo da tabela usuários
	 * 
	 * @var		array
	 * @acess	public
	 */
	public $esquema 	= array
	(
		'id'		=> array
		(
			'tit'	=> 'Id',
		),
		'ativo'=> array
		(
			'tit'		=> 'Ativo',
			'options'	=> array('1'=>'Sim','0'=>'Não')
		),
		'nome'		=> array
		(
			'tit'	=> 'Nome',
		),
		'acessos'	=> array
		(
			'tit'	=> 'Acessos',
			'type'	=> 'numeric',
			'edicaoOff'	=> true
		),
		'email'	=> array
		(
			'tit'	=> 'e-mail',
			'upperOff'	=> true,
		),
		'celular'	=> array
		(
			'tit'	=> 'Celular',
			'mascara'=> '(##)####-####',
		),
		'trocar_senha'=> array
		(
			'tit'		=> 'Trocar Senha',
			'options'	=> array('1'=>'Sim','0'=>'Não')
		),
		'cidade_id'	=> array
		(
			'tit'	=> 'Cidade',
		),
		'ultimo_ip'	=> array
		(
			'tit'	=> 'Último IP',
			'edicaoOff'=>true
		),
		'senha'		=> array
		(
			'tit'	=> 'Senha',
			'type'	=> 'password',
		),
		'ultimo_acesso'	=> array
		(
			'tit'		=> 'Último Acesso',
		),
	);

	/**
	 * Relacionamento 1:n
	 * 
	 * @var		array
	 * @access	public
	 */
	public $belongsTo	= array
	(
		'Cidade'			=> array
		(
			'foreignKey'	=> 'cidade_id',
			'key'			=> 'id',
			'fields'		=> array('nome','uf'),
			'order'			=> array('nome')
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
		$sql = "SELECT u.* FROM usuarios u WHERE u.email='$e' AND u.senha='$s'";
		$data = $this->query($sql);
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
		if (isset($this->data['id']) && $this->data['id']==1)
		{
			$this->erro = 'O Usuário Administrador não pode ser excluído !!!';
			return false;
		}
		return parent::beforeExclude();
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
			if (isset($_arrMods['Usuario']['acessos']))
			{
				//unset($this->data[$_l]['Usuario']['acessos']);
			}
		}
		return parent::beforeSave();
	}
}
