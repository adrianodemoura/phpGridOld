<?php
/**
 * Class Model
 * 
 * @package			Core
 * @subpackage		Core.Model
 */
class Model {
	/**
	 * Prefixo no nome da tabela
	 * 
	 * @var		string
	 * @access	public
	 */
	public $prefixo		= '';

	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	public
	 */
	public $name		= '';

	/**
	 * Alias para a tabela
	 * 
	 * @var		string
	 * @access	public
	 */
	public $alias		= '';

	/**
	 * Banco de Dados
	 * 
	 * @var		string
	 * @access	public
	 */
	public $database	= 'default';

	/**
	 * Driver do banco de dados
	 */
	public $driver		= '';

	/**
	 * Nome da tabela
	 * 
	 * @var		string
	 * @access	public
	 */
	public $tabela 		= '';

	/**
	 * Chave primária da tabela
	 * 
	 * @var		array
	 * @access	public
	 */
	public $primaryKey	= array();

	/**
	 * chave que NÃO pode repetir
	 * - Esta chave será será testada no método validade
	 *
	 * @var 	array
	 * @access  public
	 */
	public $uniqueKey	= array();

	/**
	 * Historic of the sqls
	 * 
	 * @var		array
	 */
	public $sqls 		= array();

	/**
	 * Dados do model
	 * 
	 * @var		array
	 * @access	public
	 */
	public $data 		= array();

	/**
	 * Não retornar belongsTo , relacionamento 1 para n
	 * Este parâmetro é usado no método find
	 * 
	 * @var		boolean
	 * @access	public
	 */
	public $belongsToOff = false;

	/**
	 * Relacionamento Habtm (HasAndBelongsToMany), n:n
	 * 
	 * @var		array
	 * @access	public
	 */
	//public $habtm		= array();

	/**
	 * Não retornar habtm HasAndBelongsToMany, relacionamento n para n
	 * Este parâmetro é usado no método find
	 * 
	 * @var		boolean
	 * @access	public
	 */
	//public $habtmOff 	= false;

	/**
	 * Matriz com as propriedades de cada campo do model corrente
	 * As propriedades do esquema podem ser:
	 * - tit			Título do campo
	 * - default		Determina o valor padrão do campo
	 * - notempty		Não valores em branco para o campo
	 * - unique			Não aceita valores duplicados para o campo
	 * - filtro			Se o campo faz parte do filtro do cadastro
	 * - emptyFiltro	Mensagem para o comboBox do filtro do campo
	 * - type			Tipo do campo, pode ser numeric, float, varchar, text, date, datetime
	 * - mascEdit		Tipo de máscara usado para edição, exemplo: d/m/Y i:m:s
	 * - multMinu		Múltiplo dos minutos, os minutos podem ser editados a cada 5 minutos, 10 minutos
	 * - edicaoOff		Se verdadeiro o campo não será editável nos formulários de manutenção
	 * - mascara		Máscara do campo
	 * - upperOff		Por padrão, todos os campos são salvos em maiúsculo, mas com este parâmetro não.
	 * - pesquisar		Se o campo pode ser pesquisado, os valores são [=|&] o padrão é "=", valor "&" executará uma pesquisa LIKE
	 * - options		Valores possíveis para o campo, no formato [valor][label]. exemplo: array(1=>'Sim',2=>'Não')
	 * - belongsTo		Se o campo tem relacionamento 1:n
	 * 
	 * Sobre o belongsTo:
	 * O parâmetro belongsTo, possuis algumas opções obrigatórias, como mostrado no exemplo abaixo.
	 * exemplo:
	 * 'cidade_id'	=> array
	 * (
	 *		'tit'		=> 'Cidade',
	 *		'belongsTo' 	=> array
	 *		(
	 *			'Cidade'	=> array // nome do outro model
	 *			(
	 *				'key'	=> 'id',	// chave de relacionamento do outor model
	 *				'fields'=> array('id','nome','uf'), // campos de exibição do relacionamento
	 *				'order'	=> array('nome','uf'), // ordem do relacionamento
	 *				'ajax'	=> 'sistema/cidades/get_options/', // busca via ajax do relacionamento
	 *				'txtPesquisa' => 'Digite o nome da cidade para pesquisar ...', // texto de pesquisa
	 *			),
	 *		),
	 *	)
	 * 
	 * @var		array
	 * @access	public
	 */
	public $esquema 	= array();

	/**
	 * Matriz com as propriedades de cada campo de belongsTo ou Habtm
	 * 
	 * segue o mesmo exemplo de esquema.
	 * 
	 * @var		array
	 * @access	public
	 */
	public $outrosEsquemas = array();

	/**
	 * Formato da date e hora do banco de dados
	 * 
	 * @var		string
	 * @access	public
	 */
	public $dateFormatBD	= 'Y-m-d H:i:s';

	/**
	 * Formato da date e hora para a view
	 * 
	 * @var		string
	 * @access	public
	 */
	public $dateFormat		= 'd/m/Y H:i:s';

	/**
	 * Erros, quando na execusão de alguma sql
	 *
	 * @var		array
	 * @access	public
	 */
	public $erros			= array();

	/**
	 * Executa start do Obejeto Model
	 * 
	 * @return	void
	 */
	public function __construct()
	{
		$this->name = get_class($this);
		if (empty($this->alias)) $this->alias = $this->name;
	}

	/**
	 * Inicia conexão com o banco de dados
	 * 
	 * @return	void
	 */
	public function open()
	{
		if (!isset($this->db))
		{
			include_once(APP.'Config/database.php');
			$dbConfig 			= new Database_Config($this->database);
			$banco				= $dbConfig->default;
			$driver 			= $banco['driver'];
			$driver				= ucfirst(strtolower($driver));
			$charset 			= isset($banco['charset']) ? $banco['charset'] : 'utf8';
			$this->driver 		= $driver;
			$this->dateFormatBD = isset($banco['dateFormatBD']) ? $banco['dateFormatBD'] : $this->dateFormatBD;
			$this->dateFormat 	= isset($banco['dateFormat'])   ? $banco['dateFormat']   : $this->dateFormat;
			$params				= array();
			switch($driver)
			{
				case 'Mysql':
				case 'MariaDB':
					$dsn = "mysql:host=".$banco['host'].";dbname=".$banco['database'].";charset=".$charset;
					if ($banco['persistent']==true) $params['PDO::ATTR_PERSISTENT'] = true;
					break;
			}
			try
			{
				$this->db = new PDO($dsn,$banco['user'],$banco['password'],$params);
			} catch (PDOException $e) 
			{
				switch($e->getCode())
				{
					case '1049':
					case '1045':
						header('Location: '.getBase().'sistema/usuarios/instala_bd');
						die();
						break;
				}
				die("<center>!!</center>");
			}
			//foreach(PDO::getAvailableDrivers() as $d) echo $d.'<br />';
			$this->setEsquema();
		}
	}

	/**
	 * Executa código antes do método validade
	 * 
	 * @return	boolean		Verdadeiro de deve continuar, Falso se não.
	 */
	public function beforeValidate()
	{
		$this->db->beginTransaction();
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		array_push($this->sqls,array('sql'=>'BEGIN;','ts'=>0.0001,'li'=>1));
		return true;
	}

	/**
	 * Executa código depois do método validade
	 *
	 * @return void
	 */
	public function afterValidate()
	{
	}

	/**
	 * Execute SQL code in database
	 * 
	 * @param	$sql	string	Strin SQL
	 * @return	$data	mixed	O resultado pode ser verdadeiro, falso ou uma array de uma resposta select
	 */
	public function query($sql='')
	{
		$this->open();
		$_data 	= $this->db->query($sql);
		$erro 	= $this->db->errorInfo();
		$l 		= 0;
		$data 	= array();
		$ini	= microtime(true);
		if (empty($erro['2']))
		{
			try
			{
				$linhas = $_data->fetchAll(PDO::FETCH_NAMED);
			} catch (Exception $e) 
			{
				$linhas = null;
			}
			if (is_array($linhas))
			{
				$l = 0;
				foreach($linhas as $_l => $_arrCmps)
				{
					foreach($_arrCmps as $_cmp => $_vlr)
					if (is_array($_vlr))
					{
						foreach($_vlr as $_l2 => $_vlr2)
						{
							$cmp = $_cmp;
							if ($_l2) $cmp = $_cmp.$_l2;
							$data[$_l][$cmp] = $_vlr2;
						}
					} else
					{
						$data[$_l][$_cmp] = $_vlr;
					}
					$l++;
				}
			}
		} else
		{
			array_push($this->erros, $erro['2']);
		}

		$ts = round(microtime(true)-$_SERVER['REQUEST_TIME'],4);
		array_push($this->sqls,array('sql'=>$sql,'ts'=>$ts,'li'=>$l));
		return $data;
	}

	/**
	 * End of the Object Model
	 * 
	 * @return	void
	 */
	public function __destruct()
	{
		if (isset($this->db))
		{
			//$this->db->close();
		}
	}

	/**
	 * Exclui um registro no banco de dados
	 *
	 * @params array 	$params Parâmetros da exclusão, params['tabela'], params['where']
	 */
	public function exclude($data=array())
	{
		$this->data = $data;

		if (!$this->beforeExclude()) return false;

		$sqls = array();
		foreach($data as $_l => $_arrMods)
		{
			$where = '';
			foreach($_arrMods[$this->name] as $_cmp => $_vlr)
			{
				if (!empty($where)) $where .= ' AND ';
				if (is_numeric($_vlr))
				{
					$where .= "$_cmp=$_vlr";
				} else
				{
					$where .= "$_cmp='$_vlr'";
				}
			}
			$sql = 'DELETE FROM '.$this->prefixo.$this->tabela.' WHERE '.$where;
			array_push($sqls, $sql);
		}

		try
		{
			$this->open();
			$this->db->beginTransaction();
			array_push($this->sqls,array('sql'=>'BEGIN;','ts'=>0.0001));
			foreach($sqls as $_l => $_sql)
			{
				$ini = microtime(true);
				$this->db->exec($_sql);
				$ts = microtime(true);
				$ts = round(($ts-$ini),6);
				array_push($this->sqls,array('sql'=>$_sql,'ts'=>$ts));
			}
			array_push($this->sqls,array('sql'=>'END;','ts'=>0.0001));
			$this->db->commit();
			$this->afterExclude();
			return true;
		} catch(PDOException $e)
		{
			$this->db->rollBack();
			$this->erro = $e->getMessage();
			return false;
		}
	}

	/**
	 * Retorno o campo principal do model, o segundo campo do esquema é considerado como tal campo.
	 *
	 * @return 	string 	$field Nome do campo
	 */
	public function getDisplayField()
	{
		$field 	= '';
		$l 		= 0;
		foreach($this->esquema as $_cmp => $_arrProp)
		{
			if ($l)
			{
				//$field = strtolower($this->name.'_'.$_cmp);
				$field = $this->name.'.'.$_cmp;
				break;
			}
			$l++;
		}
		return $field;
	}

	/**
	 * Execute a search in database
	 * 
	 * @param	string	$tipo	Tipos list|all|neighbors
	 * @param	mixed	$params	Opções para a busca, tabela, fields, where, order, inner, página, total de registro por página.
	 * @return	mixed	$data	Results
	 */
	public function find($tipo='all',$params=array())
	{
		$params = $this->beforeFind($params);

		$sql 	= '';
		$sqlC	= '';
		$join 	= array();
		$tabela = isset($params['tabela']) 	? $params['tabela'] : $this->prefixo.$this->tabela;
		$fields = isset($params['fields']) 	? $params['fields'] : array();
		$where	= isset($params['where']) 	? $params['where'] 	: array();
		$order	= isset($params['order']) 	? $params['order'] 	: array();
		$direc	= isset($params['direc']) 	? $params['direc'] 	: 'asc';
		$pag	= isset($params['pag']) 	? $params['pag'] 	: 0;
		$pagT	= isset($params['pagT']) 	? $params['pagT'] 	: 20;
		$distinct = isset($params['distinct']) ? $params['distinct'] : null;
		$ali1	= $this->name;
		$cHabtm = array();

		// verifica o nome de cada campo
		if (!empty($fields))
		{
			foreach($fields as $_l => $_cmp)
			{
				if (!strpos($_cmp,'.'))
				{
					unset($fields[$_l]);
					$tipo = $this->esquema[$this->name][$_cmp]['type'];
					if (!in_array($tipo, array('habtm','virtual')))
					{
						array_unshift($fields,$this->name.'.'.$_cmp);
					}
				}
			}
		}

		// verificando os campos
		switch($tipo)
		{
			case 'all':
				if (empty($fields))
				{
					foreach($this->esquema as $_cmp => $_arrProp)
					{
						if (!in_array($_arrProp['type'], array('habtm','virtual')))
						{
							array_push($fields,$this->name.'.'.$_cmp);
						}
						if ($_arrProp['type']=='habtm') array_push($cHabtm, $_cmp);
					}
				}
				break;
			case 'first':
				if (empty($fields))
				{
					$l = 0;
					foreach($this->esquema as $_cmp => $_arrProp)
					{
						if (!in_array($_arrProp['type'], array('habtm','virtual')))
						{
							array_push($fields,$this->name.'.'.$_cmp);
							$l++;
						}
						if ($_arrProp['type']=='habtm') array_push($cHabtm, $_cmp);
					}
				}
				break;
			case 'list':
				if (empty($fields))
				{
					foreach($this->primaryKey as $_cmp)
					{
						array_push($fields,$this->name.'.'.$_cmp);
					}
					$l = 0;
					foreach($this->esquema as $_cmp => $_arrProp)
					{
						if ($l>0) break;
						if (!in_array($_cmp,$this->primaryKey))
						{
							if (!in_array($_arrProp['type'], array('habtm','virtual')))
							{
								array_push($fields,$this->name.'.'.$_cmp);
								$l++;
							}
						}
					}
				}
				break;
		}

		// início da sql
		$cmps 		= '';
		$join 		= array();
		$belongs 	= array();
		$cmpsBelongs= array();
		$cmpsBelongsFunc= array();
		foreach($fields as $_l => $_cmp)
		{
			$nome = strpos($_cmp,'.') ? explode('.',$_cmp) : ucfirst(strtolower($_cmp));
			if (is_array($nome)) $nome = $nome['1'];
			if ($_l) $cmps .= ', ';
			$cmps .= $_cmp.' AS '.str_replace('.','_',$_cmp);
			$a = explode('.',$_cmp);
			$c = isset($a['1']) ? $a['1'] : $_cmp;

			// se é pra pegar todos os campos, pega relacionamentos também
			if ($tipo=='all')
			{
				if (isset($this->esquema[$c]['optionsFunc']) && !isset($this->esquema[$c]['belongsTo']['ajax']))
				{
					array_push($cmpsBelongsFunc, $c);
				}

				// belongsTo
				if (isset($this->esquema[$c]['belongsTo']))
				{
					foreach($this->esquema[$c]['belongsTo'] as $_model => $_arrProp)
					{
						if (strpos($_model,'.'))
						{
							$a 		= explode('.',$_model);
							$_model = $a['1'];
							set_include_path(get_include_path() . PATH_SEPARATOR . APP.'Modules/'.$a['0'].'/');
						}
						require_once('Model/'.$_model.'.php');
						$belo 	= new $_model();
						if (isset($belo->esquema)) $this->outrosEsquemas[$_model] = $belo->esquema;
						$tabB	= $belo->prefixo.$belo->tabela;
						$aliB	= $_model;
						$keyB	= (isset($_arrProp['key'])) ? $_arrProp['key'] : 'id';
						$aliA 	= (isset($this->alias)) ? $this->alias : $this->name;
						$cmpA 	= $c;
						$jSel	= "LEFT JOIN $tabB $aliB ON $aliB.$keyB = $aliA.$cmpA";
						foreach($_arrProp['fields'] as $_l2 => $_cmp2)
						{
							$cmps .= ', '.$_model.'.'.$_cmp2.' AS '.$_model.'_'.$_cmp2;
						}
						array_push($join,$jSel);
						if (!isset($this->esquema[$c]['belongsTo'][$_model]['ajax']))
						{
							array_push($cmpsBelongs,$c);
						}
					}
				}

				// habtm

			}
		}

		// iniciando a sql
		$sql  .= "SELECT ";
		if (!empty($distinct)) $sql .= ' DISTINCT ';
		$sql .= $cmps." FROM $tabela ".$ali1;
		$sqlC .= "SELECT COUNT(1) as tot FROM $tabela ".$ali1;

		// join
		if (!empty($join))
		{
			foreach($join as $_l => $j) $sql .= ' '.$j;
		}

		// where
		if (count($where))
		{
			$sql   .= " WHERE ";
			$sqlC  .= " WHERE ";
			$l 		= 0;
			foreach($where as $_cmp => $_vlr)
			{
				if ($l)
				{
					$sql  .= " AND ";
					$sqlC .= " AND ";
				}
				$b = explode(' ',$_cmp);
				$b['1'] = isset($b['1']) ? $b['1'] : null;
				switch(strtoupper($b['1']))
				{
					case '<>':
						$_cmp = trim(str_replace('<>','',$_cmp));
						$sql .= $_cmp." <> ".$_vlr;
						break;
					case 'IN':
						$_cmp = trim(str_replace('IN','',$_cmp));
						$sql .= $_cmp." IN ('".implode("','",$_vlr)."') ";
						break;
					case 'BETWEEN':
						$_cmp = trim(str_replace('BETWEEN','',$_cmp));
						$sql .= $_cmp.' BETWEEN ('.$_vlr.')';
						break;
					case 'NOT':
						$_cmp = trim(str_replace('NOT IN','',$_cmp));
						$sql .= $_cmp.' NOT IN '.$_vlr;
						break;
					case 'LIKE':
						$_cmp = trim(str_replace('LIKE','',$_cmp));
						$sql .= $_cmp." LIKE '%$_vlr%'";
						$sqlC .= $_cmp." LIKE '%$_vlr%'";
						break;
					default:
						if (is_numeric($_vlr))
						{
							$sql .= $_cmp."=$_vlr";
							$sqlC .= $_cmp."=$_vlr";
						} else
						{
							$sql .= $_cmp."='$_vlr'";
							$sqlC .= $_cmp."='$_vlr'";
						}
						break;
				}
				$l++;
			}
		}

		// configurando o order by
		if (count($order))
		{
			$l 		= 0;
			$sql   .= " ORDER BY ";
			if (is_array($order))
			{
				$l = 0;
				foreach($order as $_cmp)
				{
					if ($l) $sql .= ' AND ';
					$a = explode('_', $_cmp);
					if (count($a)>2)
					{
						$_cmp = ucfirst($a['1']).'_'.$a['2'];
						unset($a);
					} else 
					{
						$_cmp = ucfirst($_cmp);
					}
					$sql .= $_cmp;
					$l++;
				}
			} else $sql .= $order;
			$sql .= ' '.strtoupper($direc);
		}

		// verificando a página
		if (!empty($pag))
		{
			switch($this->driver)
			{
				default:
					$sql .= ' LIMIT '.(($pag*$pagT)-$pagT).','.$pagT;
			}
			// descobrindo o total
			$data = $this->query($sqlC);
			$this->pag['tot'] 	= $data['0']['tot'];
			$this->pag['pag'] 	= $pag;
			$this->pag['pagU'] 	= round($data['0']['tot']/$pagT)+1;
		}

		$_data = $this->query($sql);
		$data	= array();
		foreach($_data as $_l => $_arrCmps)
		{
			foreach($_arrCmps as $_cmp => $_vlr)
			{
				$c = explode('_',$_cmp);
				if (isset($c['2'])) $c['1'] .= '_'.$c['2'];
				if (!isset($data[$_l][$c['0']])) $data[$_l][$c['0']] = array();
				$data[$_l][$c['0']][$c['1']] = $_vlr;
			}
			
			// incrementando habtm, caso poussua
			if ($tipo=='all' && !empty($cHabtm))
			{
				foreach($cHabtm as $_Habtm)
				{
					$p 			= $this->esquema[$_Habtm];
					$_mod 		= isset($p['modFk']) ? $this->getModel($p['modFk']) : $_Habtm;
					$tabEsquerda= $p['tableFk'];
					$tabLiga	= $p['table'];
					$arrTab		= explode('_',$tabLiga);
					$cmpEsquerda= $p['key'];
					
					$tabDireita = $arrTab['2'];
					$cmpDireita = $p['keyFk'];
					$sql = ' SELECT * FROM '.$tabEsquerda.' '.$_mod;
					$sql .= ' INNER JOIN '.$tabLiga.' t1 ON ';
					$l = 0;
					foreach($cmpDireita as $_cmp)
					{
						if ($l) $sqlHabtm .= ' AND ';
						$arrCmp = explode('_',$_cmp);
						$sql .= $_mod.'.'.$arrCmp['1'].'= t1.'.$_cmp;
						$l++;
					}
					$l = 0;
					foreach($cmpEsquerda as $_cmp)
					{
						if ($l) $sql .= ' AND ';
						$vlrCmpEsquerda = $_arrCmps[ucfirst($_cmp)];
						$sql .= ' WHERE t1.'.$_cmp.'='.$vlrCmpEsquerda;
						$l++;
					}
					$dataHbtm = $this->query($sql);
					foreach($dataHbtm as $_lHa => $_arrCmHa)
					{
						$data[$_l][$_mod][$_lHa] = $_arrCmHa;
					}
				}
			}
		}

		// recuperando options para belongsTo
		if (!empty($cmpsBelongs))
		{
			foreach($cmpsBelongs as $_l => $_cmp)
			{
				$this->esquema[$_cmp]['options'] = $this->getOptions($_cmp);
			}
		}
		// recuperando options para belongsToFunc
		if (!empty($cmpsBelongsFunc))
		{
			foreach($cmpsBelongsFunc as $_l => $_cmp)
			{
				$func = $this->esquema[$_cmp]['optionsFunc'];
				$this->esquema[$_cmp]['options'] = $this->$func();
			}
		}

		return $this->afterFind($data);
	}

	/**
	 * Retorna um lista conforme as opções de campo belongsTo ou Habtm
	 *
	 * @params 	$cmp 	string 	$nome do campo
	 * @params 	$linha 	array 	primeira linha de lista
	 * @return array
	 */
	public function getOptions($cmp='')
	{
		$options 	= array();
		$tipo 		= isset($this->esquema[$cmp]['belongsTo']) ? 'belongsTo' : null;
		$tipo 		= isset($this->esquema[$cmp]['hbatm']) ? 'hbatm' : $tipo;
		switch($tipo)
		{
			case 'belongsTo':
				foreach($this->esquema[$cmp]['belongsTo'] as $_mod => $_arrProp)
				{
					if (strpos($_mod,'.'))
					{
						$a 		= explode('.',$_mod);
						$_mod	= $a['1'];
						set_include_path(get_include_path() . PATH_SEPARATOR . APP.'Modules/'.$a['0'].'/');
					}
					$alias 	= $_mod;
					$cmps 	= $_arrProp['fields'];
					$ordem 	= $_arrProp['order'];
					if (isset($_arrProp['where']))
					{
						$where = '';
						foreach($_arrProp['where'] as $_cmp => $_vlr)
						{
							if (!empty($where)) $where .= ' AND ';
							if (is_numeric($_vlr))
								$where .= "$_cmp=".$_vlr;
							else
								$where .= "$_cmp='$_vlr'";
						}
					}
					$limite = isset($_arrProp['limit']) ? $_arrProp['limit'] : null;

					require_once('Model/'.$_mod.'.php');
					$belo 	= new $_mod();
					$tabela = $belo->prefixo.$belo->tabela;
					$sql 	= "SELECT ".implode(',', $cmps)." FROM $tabela as $alias";
					if (!empty($where)) $sql .= " WHERE ".$where;
					$sql .= " ORDER BY ".implode(',', $ordem);
					if (!empty($limite)) $sql .= ' LIMIT '.$limite;

					$res = $this->query($sql);
					foreach ($res as $_l => $_a)
					{
						$l = 0;
						$c1='';
						$c2='';
						foreach($cmps as $_l2 => $_cmp)
						{
							if (!$l) $c1 = $_a[$_cmp]; else $c2 = $_a[$_cmp];
							$l++;
						}
						$options[$c1] = $c2;
					}
				}
				break;
		}
		return $options;
	}


	/**
	 * Atualiza as propriedades de cada campo da tabela
	 * 
	 * @param	string	$tabela	Nome da tabela
	 * @return	void
	 */
	public function setEsquema($tabela='')
	{
		$this->open();
		$tabela = !empty($tabela) ? $tabela : $this->prefixo.$this->tabela;
		// completando o esquema
		switch($this->driver)
		{
			case 'Mysql':
			case 'MariaDB':
				appUses('cache','Memcache');
				$Cache = new Memcache();
				$chave = 'describe'.$tabela;
				$_data = $Cache->read($chave);
				if (!$_data)
				{
					$_data = $this->query('DESCRIBE '.$tabela);
					$Cache->write($chave,$_data);
				}
				foreach($_data as $_l => $_arrProp)
				{
					$field 	= $_arrProp['Field'];
					$type  	= $_arrProp['Type'];
					$null	= $_arrProp['Null'];
					$default= $_arrProp['Default'];
					$key	= $_arrProp['Key'];
					$_t = substr($type,strpos($type,'('),strlen($type));
					if (strpos($type,'(')) $type = substr($type,0,strpos($type,'('));
					$length= str_replace('(','',$_t);
					$length= str_replace(')','',$length);
					if (in_array($type,array('varchar')))
					{
						$type = 'text';
					}
					if (in_array($type,array('int','float','tinyint')))
					{
						$type = 'numeric';
					}
					$this->esquema[$field]['tit'] 	= !isset($this->esquema[$field]['tit']) ? ucfirst(str_replace('_','',$field)) : $this->esquema[$field]['tit'];
					$this->esquema[$field]['type'] 	= !isset($this->esquema[$field]['type']) ? $type : $this->esquema[$field]['type'];
					$this->esquema[$field]['length']= !isset($this->esquema[$field]['length']) ? $length : $this->esquema[$field]['length'];
					$this->esquema[$field]['null']	= !isset($this->esquema[$field]['null']) ? $null : $this->esquema[$field]['null'];
					if (!empty($default))
						$this->esquema[$field]['default']= !isset($this->esquema[$field]['default']) ? $default : $this->esquema[$field]['default'];
					if (!empty($key))
					{
						$this->esquema[$field]['key']= !isset($this->esquema[$field]['key']) ? $key : $this->esquema[$field]['key'];
						if ($key=='PRI' && !in_array($field,$this->primaryKey)) array_push($this->primaryKey,$field);
					}
					
					if (in_array($field,array('modificado','criado')))
					{
						$this->esquema[$field]['edicaoOff'] = true;
					}

				}
				break;
		}
	}

	/**
	 * Executa código antes da exclusão de um registro no banco de dados
	 * 
	 * @param	array	$data	Atributo do Model contendo os valores do model, seguindo o modelo data[campo][valor]
	 * @return	boolean	Verdadeiro se o método salvar deve continuar
	 */
	public function beforeExclude()
	{
		return true;
	}

	/**
	 * Executa código antes do método save
	 * 
	 * - Remove a máscara de cada campo
	 * - Se o model possui os campos criado e modificado, então atualiza seus valores
	 * - Transforma tudo em maiúsculo, salvo se o campo possui a propriedade upperOff
	 * 
	 * @param	array	$data	Atributo do Model contendo os valores do model, seguindo o modelo data[linha][Model][campo][valor]
	 * @return	boolean	Verdadeiro se o método salvar deve continuar
	 */
	public function beforeSave()
	{
		$_data 	= array();
		$c		= isset($this->esquema['criado']) ? true : false;
		$m		= isset($this->esquema['modificado']) ? true : false;
		$id 	= false;
		foreach($this->data as $_l => $_arrMods)
		{
			foreach($_arrMods as $_mod => $_arrCmps)
			{
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					$v = $_vlr;
					$p = $this->esquema[$_cmp];
					if (!is_array($_vlr))
					{
						// tudo maiúsculo
						if (!isset($p['upperOff']) && in_array($p['type'],array('text','varchar'))) $v = mb_strtoupper($v,'UTF8');

						// removendo a máscara
						if (isset($p['mascara']))
						{
							$v = str_replace(array('-','_','(',')','\\','.'),'',$v);
						}

						// se é do tipo data no formato string
						if (in_array($p['type'], array('date','datetime')))
						{
							if (!empty($v))
							{
								$n1	= explode('/', substr($v,0,10));
								$n2 = substr($v,11,strlen($v));
								if (empty($n2)) $n2 = date('H:i:s');
								$v  = $n1['2'].'-'.$n1['1'].'-'.$n1['0'].' '.$n2;
								$v 	= date($this->dateFormatBD, strtotime($v));
							}
						}

						// testando a primaryKey
						if (in_array($_cmp, $this->primaryKey))
						{
							$id = true;
						}
					} else
					{
						// se é do tipo data no formato array
						if (in_array($p['type'], array('date','datetime')))
						{
							if (!empty($v))
							{
								$v 	= $this->getData($v,$_cmp);
							}
						}
					}
					$_data[$_l][$_mod][$_cmp] = $v;
				}
			}
			// campo criado
			if ($c)
			{
				$_data[$_l][$_mod]['criado'] = date($this->dateFormatBD);
				if ($id) unset($_data[$_l][$_mod]['criado']);
			}
			// campo modificado
			if ($m) $_data[$_l][$_mod]['modificado'] = date($this->dateFormatBD);
		}
		$this->data = $_data;
		return true;
	}

	/**
	 * Executa o código antes do método find
	 * 
	 * @param	array	$queryData	Parâmetros da operação find, tal como where, table, fields, order e etc.
	 * @return	array	$params		Parâmetros possivelmente alterados
	 */
	public function beforeFind($params=array())
	{
		return $params;
	}

	/**
	 * Executa código depois do método find
	 * 
	 * @return	void
	 */
	public function afterFind($data = array()) 
	{
		$resultado = array();
		foreach($data as $_l => $_arrMods)
		{
			foreach($_arrMods as $_mod => $_arrCmps)
			{
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					$v = $_vlr;
					if (!is_array($_vlr))
					{
						$p = isset($this->esquema[$_cmp]) ? $this->esquema[$_cmp] : array();
						$t = isset($p['type']) ? $p['type'] : 'text';
						switch($t)
						{
							case 'datetime':
								if ($v=='0000-00-00 00:00:00')
									$v = '';
								else
									$v = date($this->dateFormat,strtotime($v));
								break;
						}
					}
					$resultado[$_l][$_mod][$_cmp] = $v;
				}
			}
		}
		return $resultado;
	}

	/**
	 * Executa código depois do método save
	 *
	 * @return 	void
	 */
	public function afterSave()
	{
	}

	/**
	 * Executa código depois do método delete
	 *
	 * @return 	void
	 */
	public function afterExclude()
	{
	}

	/**
	 * Retorna uma matriz com os módulos em que o perfil tem acesso
	 *
	 * @param 	integer 	$idPerfil 	Id do Perfil a pesquisar
	 * @return 	array 		$modulos
	 */
	public function getMeusModulos($idPerfil=1)
	{
		appUses('cache','Memcache');
		$Cache 	= new Memcache();
		$chave 	= 'modulos'.$idPerfil;
		$data 	= $Cache->read($chave);
		if (!$data)
		{
			if ($idPerfil>1)
			{
				$sql = "SELECT DISTINCT m.id, m.nome, m.titulo
					FROM sis_modulos m
					INNER JOIN sis_permissoes p 
					ON  p.modulo_id = m.id 
					AND p.visualizar = 1
					AND p.perfil_id =".$idPerfil." ORDER BY m.nome";
			} else
			{
				$sql = "SELECT DISTINCT m.id, m.nome, m.titulo
					FROM sis_modulos m ORDER BY m.nome";
			}
			$data = $this->query($sql);
			$Cache->write($chave,$data);
		}

		return $data;
	}

	/**
	 * Retorna uma matriz com os cadastros do perfil
	 *
	 * @param 	integer 	$idPerfil 	Id do Perfil a pesquisar
	 * @param 	string 		$modulo 	Nome do Módulo
	 * @return 	array 		$cadastros array(n=>array(cadastro,titulo))
	 */
	public function getMeusCadastros($idPerfil=1, $modulo='')
	{
		/*appUses('cache','Memcache');
		$Cache 	= new Memcache();
		$chave 	= 'cadastros'.$idPerfil.$modulo;
		$data 	= $Cache->read($chave);
		if (!$data)*/
		{
			if ($idPerfil>1)
			{
				$sql = "SELECT DISTINCT c.nome, c.titulo
					FROM sis_permissoes p
					INNER JOIN sis_cadastros c ON c.id = p.cadastro_id
					INNER JOIN sis_modulos m ON m.id = c.modulo_id
					WHERE p.perfil_id=".$idPerfil." 
					AND m.nome='".strtoupper($modulo)."' AND p.visualizar=1 AND c.ativo=1
					ORDER BY c.nome";
			} else
			{
				$sql = "SELECT DISTINCT c.nome, c.titulo 
						FROM sis_cadastros c
						INNER JOIN sis_modulos m ON m.id = c.modulo_id
						WHERE c.ativo=1 AND m.nome='".strtoupper($modulo)."'
						ORDER BY c.nome";
			}
			$data = $this->query($sql);
			//$Cache->write($chave,$data);
		}
		return $data;
	}

	/**
	 * Retorna a data do formato array para o formato string
	 *
	 * @param 	$v 		data no formato array
	 * @param 	$cmp 	nome do campo
	 * @return 	$v 		data no formato string
	 */
	public function getData($v=array(), $cmp='')
	{
		$_v = $v['dia'].'/'.$v['mes'].'/'.$v['ano'];
		$p 	= $this->esquema[$cmp];
		if ($p['type']=='datetime')
		{
			$v['hor'] = isset($v['hor']) ? $v['hor'] : 0;
			$v['min'] = isset($v['min']) ? $v['min'] : 0;
			$v['seg'] = isset($v['seg']) ? $v['seg'] : 0;
			$_v .= ' '.$v['hor'].':'.$v['min'].':'.$v['seg'];
		}
		$v = $_v;
		$n1	= explode('/', substr($v,0,10));
		$n2 = substr($v,11,strlen($v));
		if (empty($n2)) $n2 = date('H:i:s');
		$v  = $n1['2'].'-'.$n1['1'].'-'.$n1['0'].' '.$n2;
		$v 	= date($this->dateFormatBD, strtotime($v));
		return $v;
	}

	/**
	 * Salva um registro ou conjunto de registros no banco de dados.
	 * As querys serão executadas dentro de uma transação, certifique-se de que o banco de dados possui o devido suporte.
	 * Outros métodos serão executados dentro deste método, conforme sequência abaixo:
	 *  - validate
	 * 	- - beforeValidate
	 * 	- - afterValidate
	 * 	- beforeSave
	 * 	- afterSave
	 * 
	 * @param	array	$data	Matriz com os dados a serem salvos
	 * @return	boolean	Retorna verdadeiro em caso de sucesso
	 */
	public function save($data=array())
	{
		$this->open();
		$sqls	= array();
		$sqlTi 	= 'INSERT';
		$sqlUp 	= '';
		$sqlIn 	= '';
		$where 	= '';
		$lCm	= 0;
		$lPk	= 0;
		$sHabtm = array(); // sqls habtm
		$this->data = $data;

		// identificando os valores dos campos ids
		foreach($this->data as $_l => $_arrMods)
		{
			foreach($this->primaryKey as $_l2 => $_cmp)
			{
				if (isset($_arrMods[$this->name][$_cmp]))
				{
					$this->ids[$_l][$_cmp] = $_arrMods[$this->name][$_cmp];
				}
			}
		}

		if (!$this->validate()) return false;

		if (!$this->beforeSave()) return false;

		// dando um loop na data pra criar cada sql
		foreach($this->data as $_l => $_arrMods)
		{
			foreach($_arrMods as $_l2 => $_arrCmps)
			{
				$sqlInC	= array();
				$sqlInV	= array();
				$sqlUp 	= '';
				$where 	= '';
				$id 	= null;
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					$tipo = (isset($this->esquema[$_cmp]['type'])) ? $this->esquema[$_cmp]['type'] : 'text';
					if ($lCm>0 && !empty($sqlUp)) $sqlUp .= ", ";
					if (!empty($_vlr)) $sqlTi = (in_array($_cmp,$this->primaryKey)) ? 'UPDATE' : $sqlTi;
					if ((in_array($_cmp,$this->primaryKey))) $id = $_vlr;

					switch($tipo)
					{
						case 'numero':
						case 'numeric':
						case 'float':
						case 'integer':
						case 'int':
							$_sqlUp = "$_cmp=$_vlr";
							array_push($sqlInC,$_cmp);
							array_push($sqlInV,$_vlr);
							break;
						case 'virtual':
							break;
						case 'habtm':
							$tabHabtm 	= $this->esquema[$_cmp]['table'];
							$cmpKey 	= $this->esquema[$_cmp]['key']['0'];
							$cmpKeyFk	= $this->esquema[$_cmp]['keyFk']['0'];
							array_push($sHabtm,'DELETE FROM '.$tabHabtm.' WHERE '.$cmpKey.'='.$id);
							foreach($_vlr as $_l3 => $_vlr3)
							{
								$v = explode('.', $_vlr3);
								if (!empty($v['0']) && !empty($v['1']))
								{
									array_push($sHabtm, 'INSERT INTO '.
										$tabHabtm.'('.$cmpKey.','.$cmpKeyFk.') values ('.$v['0'].','.$v['1'].');');
								}
							}
							break;
						default:
							$_sqlUp = "$_cmp='$_vlr'";
							array_push($sqlInC,$_cmp);
							array_push($sqlInV,"'$_vlr'");
					}
					$lCm++;
					if (in_array($_cmp,$this->primaryKey))
					{
						if (!empty($where)) $where .= ' AND ';
						$where .= $_sqlUp;
					} else
					{
						$sqlUp .= $_sqlUp;
					}
				}
			}
			switch($sqlTi)
			{
				case 'UPDATE':
					$sqls[$_l] = 'UPDATE '.$this->prefixo.$this->tabela.' SET '.$sqlUp.' WHERE '.$where.';';
					break;
				case 'INSERT':
					$sqls[$_l] = 'INSERT INTO '.$this->prefixo.$this->tabela.' ('.implode(',',$sqlInC).') VALUES ('.implode(',',$sqlInV).');';
					break;
			}
		}

		// iniciando a transação
		$lE = 0; // linha erro
		foreach($sqls as $_l => $_sql)
		{
			$lE = $_l;
			$ini = microtime(true);
			try
			{
				$d = $this->query($_sql);
			} catch (PDOException $e)
			{
				$this->erros[$lE] = $e->getMessage();
			}
		}
		if ($sqlTi=='INSERT') $this->ultimoId = $this->db->lastInsertId();

		// salvando habtm
		if (!empty($sHabtm))
		{
			foreach($sHabtm as $_l => $_sql)
			{
				$res = $this->query($_sql);
			}
		}

		// depois de salvar
		$this->afterSave();

		if (empty($this->erros))
		{
			$this->db->commit();
			array_push($this->sqls,array('sql'=>'COMMIT;','ts'=>0.0001,'li'=>1));
			return true;
		} else
		{
			$this->db->rollBack();
			array_push($this->sqls,array('sql'=>'ROLLBACK;','ts'=>0.0001,'li'=>1));
			return false;
		}
	}

	/**
	 * Retorna a mensagem, renderizada com o conteúdo do data
	 *
	 * @param 	string 	$msg 	Mensagem a ser renderizada
	 * @param 	array 	$data 	Dados do model
	 @ @param 	string 	$_msg 	$mensagem renderizada
	 */
	public function getMsgModel($msg='',$data=array())
	{
		$_msg = $msg;
		foreach($data as $_l => $_arrMods)
		{
			foreach($_arrMods as $_mod => $_arrCmps)
			{
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					$_msg = str_replace('{'.$_mod.'.'.$_cmp.'}', $_vlr, $_msg);
				}
			}
		}
		return $_msg;
	}

	/**
	 * Executa a validação de cada campo do model
	 * As validações podem ser: 
	 * - notnull, não aceita valores nulos
	 * - unique, não aceita duplicidades
	 * - uniqueKey, testa a duplicidade de chaves montadas por mais de um campo
	 * 
	 * @return	boolean
	 */
	public function validate()
	{
		if (!$this->beforeValidate()) return false;

		$duplaChaves 	= array();
		$ids 			= array();

		$ids = array();
		foreach($this->data as $_l	=> $_arrMods)
		{
			foreach($_arrMods[$this->name] as $_cmp => $_vlr)
			{
				$tit	= isset($this->esquema[$_cmp]['tit']) 		? $this->esquema[$_cmp]['tit'] 		: $_cmp;
				$empty 	= isset($this->esquema[$_cmp]['notEmpty']) 	? $this->esquema[$_cmp]['notEmpty'] : null;
				$unique	= isset($this->esquema[$_cmp]['unique']) 	? $this->esquema[$_cmp]['unique'] 	: null;
				if (!empty($empty) && empty($_vlr))
				{
					$this->erros[$_l] = 'O Campo '.$tit.' é de preenchimento obrigatório';
				}
				if ($unique)
				{
					$idCor = isset($_arrMods[$this->name]['id']) ? $_arrMods[$this->name]['id'] : null;
					$params['where'][$this->name.'.'.$_cmp] = $_vlr;
					if ($idCor) $params['where'][$this->name.'.id <>'] = $idCor;
					$_repete = $this->find('list',$params);
					$repete = isset($_repete['0'][$this->name]) ? $_repete['0'][$this->name] : array();
					if (!empty($repete))
					{
						$this->erros[$_l] = 'Duplicidade não aceita no campo '.$tit;
					}
				}
			}

			// incrementando os campos uniquekey
			if (!empty($this->uniqueKey))
			{
				foreach($this->uniqueKey as $_l2 => $_arrProp)
				{
					$duplaChaves[$_l]['msg'] = $_arrProp['msg'];
					foreach($_arrProp['fields'] as $_cmp)
					{
						$duplaChaves[$_l][$_cmp] = $_arrMods[$this->name][$_cmp];
					}
				}
			}
		}

		// validando as chaves duplas
		if (count($duplaChaves))
		{
			foreach($duplaChaves as $_l => $_arrCmps)
			{
				$params = array();
				if (isset($this->ids[$_l]))
				{
					foreach($this->ids[$_l] as $_cmp2 => $_vlr2)
					{
						$params['where'][$this->name.'.'.$_cmp2.' <>'] = $_vlr2;
					}
				}
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					if ($_cmp!='msg')
					{
						$p = $this->esquema[$_cmp];
						if (in_array($p['type'], array('date','datetime'))) $_vlr = $this->getData($_vlr,$_cmp);
						$params['where'][$this->name.'.'.$_cmp] = $_vlr;
					}
				}
				$res = $this->find('all',$params);
				//debug($res);
				if (count($res))
				{
					$this->erros[$_l] = $this->getMsgModel($_arrCmps['msg'],$res);
				}
			}
		}

		// se ocorreu algum erro
		if (!empty($this->erros)) return false;
		$this->afterValidate();
		return true;
	}

	/**
	 * Retorna o nome do model, caso seja de outro módulo inclui no path o caminho do módulo
	 *
	 * @param 	string 	$model 	Nome do model, pode ser no formato Modulo.Model, ou simples Model
	 * @return 	string 	$model 	Nome do model
	 */
	public function getModel($model='')
	{
		return $model;
	}
}
