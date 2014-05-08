<?php
/**
 * Class Modulo
 * 
 * @package		Sistema
 * @package		Sistema.Model
 */
/**
 * Include files
 */
appUses('Model','SistemaApp');
class Modulo extends SistemaApp {
	/**
	 * Nome da tabela de cidades
	 * 
	 * @var		string	
	 * @access	public
	 */
	public $tabela		= 'modulos';

	/**
	 * Chave primária do model usuários
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey 	= array('id');

	/**
	 * Nickname para a tabela usuarios
	 * 
	 * @var		string
	 * @access	public
	 */
	public $alias		= 'Modulo';

	/**
	 * Propriedade de cada campo da tabela usuários
	 * 
	 * @var		array
	 * @acess	public
	 */
	public $esquema = array
	(
		'id'			=> array
		(
			'tit'		=> 'Id',
		),
		'nome'			=> array
		(
			'tit'		=> 'Nome',
			'notEmpty'	=> true,
			'pesquisar'	=> '&',
		),
		'titulo'		=> array
		(
			'tit'		=> 'Título',
			'notEmpty'	=> true,
			'upperOff' 	=> true,
		),
		'ativo'=> array
		(
			'tit'		=> 'Ativo',
			'options'	=> array('1'=>'Sim','0'=>'Não')
		),
	);

	/**
	 * Executa código antes da de salvar no banco
	 * 
	 * - Módulo sistema sempre ativo
	 * 
	 * @return boolean
	 */
	public function beforeSave()
	{
		foreach($this->data as $_l => $_arrMods)
		{
			// sistema sempre ativo
			if (isset($_arrMods['Modulo']['ativo']) && isset($_arrMods['Modulo']['id']))
			{
				if ($_arrMods['Modulo']['id']=='1') $this->data[$_l]['Modulo']['ativo'] = 1;
			}
		}
		return parent::beforeSave();
	}

	/**
	 * Executa código antes de excluir um módulo no banco
	 *
	 * - Módulo SISTEMA não pode ser excluído
	 * 
	 * @return boolean
	 */
	public function beforeExclude()
	{
		if (isset($this->data['0'][$this->name]['id']) && $this->data['0'][$this->name]['id']==1)
		{
			$this->erro = 'O Módulo SISTEMA não pode ser excluído !!!';
			return false;
		}
		return true;
	}

	/**
	 * Executa código depois do método save
	 *
	 * - Executa a sql de inserção do módulo
	 *
	 * @return 	void
	 */
	public function afterSave()
	{
		if (!empty($this->ultimoId))
		{
			$modulo = ucfirst(strtolower($this->data['0']['Modulo']['nome']));
			$arq 	= APP.'Modules/'.$modulo.'/Model/Sql/'.$modulo.'.sql';
			if (file_exists($arq))
			{
				$handle  = fopen($arq,"r");
				$texto   = fread($handle, filesize($arq));
				$sqls	 = explode(";",$texto);
				fclose($handle);

				// executando sql a sql
				foreach($sqls as $_l => $sql)
				{
					if (trim($sql))
					{

						$this->query($sql);
					}
				}
			}
		}
		parent::afterSave();
	}

	/**
	 * Executa código depois do método delete
	 *
	 * @return 	void
	 */
	public function afterExclude()
	{
		$sql = 'DELETE FROM sis_cadastros WHERE modulo_id='.$this->data['0']['Modulo']['id'].';';
		$sql .= 'DELETE FROM sis_permissoes WHERE modulo_id='.$this->data['0']['Modulo']['id'];
		$this->query($sql);
		parent::afterExclude();
	}
}
