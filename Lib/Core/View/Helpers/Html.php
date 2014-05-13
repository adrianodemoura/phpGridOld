<?php
/**
 * Classe Html
 * 
 * @package		Core
 * @subpackage	Core.View.Helper
 */
class Html {
	/**
	 * Url da Base
	 * 
	 * @var		string
	 * @access	public
	 */
	public $base		= '';

	/**
	 * Nome do controlador
	 * 
	 * @var		string
	 * @access	public
	 */
	public $controller 	= '';

	/**
	 * Implementação para o cabeçalho head da página
	 * 
	 * @var		array
	 * @access	public
	 */
	public $head		= array();

	/**
	 * Retorno o ID ou Name de um campo de formulário
	 * 
	 * @param	string	$cmp	Nome do Campo
	 * @param	string	$tipo	Tipo id ou name
	 */
	public function domId($cmp='',$tipo='id')
	{
		$id = '';
		$na = 'data';
		$a  = explode('.',$cmp);
		foreach($a as $_l => $_n)
		{
			$na	.= "[$_n]";
			//$id .= str_replace('_id','Id',ucfirst("$_n"));
			$id .= ucfirst("$_n");
		}
		if ($tipo=='id') return $id; else return $na;
	}

	/**
	 * Retorna o element de um formulário
	 * 
	 * @param	string	$cmp	Nome do campo
	 * @param	array	$e		Esquema do campo, que veio do model
	 * @param	array	$linha	Linha completa do registro
	 * @return	$input	string
	 */
	public function getInput($cmp='', $e=array(), $linha=array())
	{
		$propDiv 		= isset($e['input']['div']) 	? $e['input']['div'] : null;
		if (!empty($propDiv)) unset($e['input']['div']);
		$e['type'] 		= isset($e['type'])  ? $e['type'] :  'text';
		$opcs			= isset($e['input']) ? $e['input'] : array();
		$opcs['name']  	= $this->domId($cmp,'name');
		$opcs['id']  	= $this->domId($cmp,'id');
		$opcs['type']	= isset($opcs['type'])  ? $opcs['type']  : $e['type'];
		$opcs['value']	= isset($opcs['value']) ? $opcs['value'] : '';
		$opcs['value']	= isset($e['value']) ? $e['value'] : $opcs['value'];
		$a = explode('.',$cmp);

		$a['2'] = isset($a['2']) ? $a['2'] : $cmp;

		if (isset($e['edicaoOff']) && $e['edicaoOff']==true) $opcs['disabled'] = 'disabled';
		if (isset($e['options'])) $opcs['options'] = $e['options'];
		if ($e['type']=='password') $opcs['value'] = '';

		$opcs['type'] 	= (isset($opcs['type']))  	? $opcs['type'] 	: 'text';
		$opcs['class'] 	= (isset($opcs['class'])) 	? $opcs['class'] 	: 'in_'.strtolower($a['2']). ' lista_input';
		//$idDiv			= (isset($opcs['idDiv'])) 	? $opcs['idDiv'] 	: null;
		//if (!empty($idDiv)) unset($opcs['idDiv']);

		if (isset($opcs['options'])) $opcs['type'] = 'select';

		// se o campo é belongsTo
		if (isset($e['belongsTo']))
		{
			foreach($e['belongsTo'] as $_mod => $_arrProp)
			{
				if (strpos($_mod,'.'))
				{
					$aa 	= explode('.',$_mod);
					$_mod	= $aa['1'];
				}
				if (isset($_arrProp['ajax']))
				{
					$fields = array();
					$cmpPes	= '';
					$l = 0;
					foreach($_arrProp['fields'] as $_cmp)
					{
						array_push($fields,$_mod.'.'.$_cmp);
						if ($l==1) $cmpPes = $_cmp;
						$l++;
					}
					$aj				= explode('_',$cmp);
					$opcs['type'] 	= 'ajax';
					$ajax['value'] 	= '';
					$ajax['cmp']	= 'ajax'.$this->domId($cmp,'id');
					$ajax['url']	= $this->base.$_arrProp['ajax'].'cmps:'.implode(',',$fields);
					$ajax['titPesq']= isset($_arrProp['txtPesquisa']) ? $_arrProp['txtPesquisa'] : '';
					$ajax['url']	.= '/ord:';
					foreach($_arrProp['order'] as $_l => $_cmpO)
					{
						if ($_l) $ajax['url'] .= ',';
						$ajax['url'] .= $_mod.'.'.$_cmpO;
					}
					$ajax['url']	.= '/'.$_mod.'.'.$cmpPes.':';
					$l = 0;
					if (!empty($linha))
					{
						foreach($linha[$_mod] as $_cmp => $_vlr)
						{
							if ($l==1) $ajax['value'] =$_vlr;
							if ($l>1)
							{
								$ajax['value'] .= '/'.$_vlr;
							}
							$l++;
						}
					}
				}
			}
		}

		// se é ediçãoOff entõa é disabled
		if (isset($e['edicaoOff']) && $e['edicaoOff']==true)
		{
			$opcs['type'] = 'text';
			if (isset($opcs['options']))
			{
				$opcs['value'] = $opcs['options'][$opcs['value']];
				unset($opcs['options']);
			} else
			{
				if (isset($a['2']))
				{
					$b = explode('_',$a['2']);
					$b['0'] = ucfirst($b['0']);
					if (isset($linha[$b['0']]))
					{
						$l = 0;
						foreach($linha[$b['0']] as $_cmp => $_vlr)
						{
							if ($l>1) $opcs['value'] .= '/'.$_vlr;
							if ($l==1) $opcs['value'] = $_vlr;
							$l++;
						}
					}
				}
			}
		}

		switch($opcs['type'])
		{
			case 'select':
				$input = '<select name="'.$opcs['name'].'" id="'.$opcs['id'].'" class="'.$opcs['class'].'">';
				foreach($opcs['options'] as $_vlr => $_show)
				{
					$input .= '<option ';
					if ($_vlr==$opcs['value']) $input .= ' selected="selected"';
					$input .= 'value="'.$_vlr.'">'.$_show.'</option>';
				}
				$input .= '</select>';
				break;
			case 'date':
				$mas = isset($e['mascEdit']) ? $e['mascEdit'] : array('d','m','y');
				$_vd = explode('/',$opcs['value']);
				$dia = 1;
				$mes = 1;
				$ano = date('Y');
				$dia = !empty($_vd['0']) ? $_vd['0'] : $dia;
				$mes = !empty($_vd['1']) ? $_vd['1'] : $mes;
				$ano = !empty($_vd['2']) ? $_vd['2'] : $ano;
				$input = '';
				
				if (in_array('d',$mas)) // dia
				{
					$input .= '<select name="'.$opcs['name'].'[dia]" id="'.$opcs['id'].'dia" class="'.$opcs['class'].'">'."\n";
					for($i=1; $i<32; $i++)
					{
						$i = substr('00'.$i,strlen('00'.$i)-2,2);
						$input .= '<option ';
						if ($i==$dia) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
					}
					$input .= '</select>'."\n";
				}
				if (in_array('m',$mas)) // mes
				{
					$input .= '<select name="'.$opcs['name'].'[mes]" id="'.$opcs['id'].'mes" class="'.$opcs['class'].'">'."\n";
					for($i=1; $i<13; $i++)
					{
						$i = substr('00'.$i,strlen('00'.$i)-2,2);
						$input .= '<option ';
						if ($i==$mes) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
					}
					$input .= '</select>'."\n";
				}
				if (in_array('y',$mas)) // ano
				{
					$input .= '<select name="'.$opcs['name'].'[ano]" id="'.$opcs['id'].'ano" class="'.$opcs['class'].'">'."\n";
					for($i=date('Y')+1; $i>date('Y')-100; $i--)
					{
						$input .= '<option ';
						if ($i==$ano) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
					}
					$input .= '</select>'."\n";
				}
				break;
			case 'datetime':
				$mas = isset($e['mascEdit']) ? $e['mascEdit'] : array('d','m','y','h','i','s');
				$_vd = explode('/',$opcs['value']);
				$_vh = explode(':',substr($opcs['value'],11,strlen($opcs['value'])));
				$dia = 1;
				$mes = 1;
				$ano = date('Y');
				$hor = 0;
				$min = 0;
				$seg = 0;
				$dia = !empty($_vd['0']) ? $_vd['0'] : $dia;
				$mes = !empty($_vd['1']) ? $_vd['1'] : $mes;
				$ano = !empty($_vd['2']) ? $_vd['2'] : $ano;
				$hor = !empty($_vh['0']) ? $_vh['0'] : $hor;
				$min = !empty($_vh['1']) ? $_vh['1'] : $min;
				$seg = !empty($_vh['2']) ? $_vh['2'] : $seg;
				$input = '';

				if (in_array('d',$mas)) // dia
				{
					$input .= '<select name="'.$opcs['name'].'[dia]" id="'.$opcs['id'].'dia" class="'.$opcs['class'].'">'."\n";
					for($i=1; $i<32; $i++)
					{
						$i = substr('00'.$i,strlen('00'.$i)-2,2);
						$input .= '<option ';
						if ($i==$dia) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
					}
					$input .= '</select>'."\n";
				}
				if (in_array('m',$mas)) // mes
				{
					$input .= '<select name="'.$opcs['name'].'[mes]" id="'.$opcs['id'].'mes" class="'.$opcs['class'].'">'."\n";
					for($i=1; $i<13; $i++)
					{
						$i = substr('00'.$i,strlen('00'.$i)-2,2);
						$input .= '<option ';
						if ($i==$mes) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
					}
					$input .= '</select>'."\n";
				}
				if (in_array('y',$mas)) // ano
				{
					$input .= '<select name="'.$opcs['name'].'[ano]" id="'.$opcs['id'].'ano" class="'.$opcs['class'].'">'."\n";
					for($i=date('Y')+1; $i>date('Y')-100; $i--)
					{
						$input .= '<option ';
						if ($i==$ano) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
					}
					$input .= '</select>'."\n";
				}
				if (in_array('h',$mas)) // hora
				{
					$input .= '<select name="'.$opcs['name'].'[hor]" id="'.$opcs['id'].'hor" class="'.$opcs['class'].'">'."\n";
					for($i=0; $i<24; $i++)
					{
						$i = substr('00'.$i,strlen('00'.$i)-2,2);
						$input .= '<option ';
						if ($i==$hor) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
					}
					$input .= '</select>'."\n";
				}
				if (in_array('i',$mas)) // minutos
				{
					$input .= '<select name="'.$opcs['name'].'[min]" id="'.$opcs['id'].'min" class="'.$opcs['class'].'">'."\n";
					for($i=0; $i<60; $i++)
					{
						$i = substr('00'.$i,strlen('00'.$i)-2,2);
						$input .= '<option ';
						if ($i==$min) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
						if (isset($e['multMinu'])) for($o=0; $o<($e['multMinu']-1); $o++) $i++;
					}
					$input .= '</select>'."\n";
				}
				if (in_array('s',$mas)) // segundos
				{
					$input .= '<select name="'.$opcs['name'].'[seg]" id="'.$opcs['id'].'seg" class="'.$opcs['class'].'">'."\n";
					for($i=0; $i<60; $i++)
					{
						$i = substr('00'.$i,strlen('00'.$i)-2,2);
						$input .= '<option ';
						if ($i==$seg) $input .= ' selected="selected" ';
						$input .= 'value="'.$i.'">'.$i.'</option>';
						if (isset($e['multSeg'])) for($o=0; $o<($e['multSeg']-1); $o++) $i++;
					}
					$input .= '</select>'."\n";
				}
				break;
			case 'ajax':
				$input = "<input ";
				$opcs['type'] = 'hidden';
				foreach($opcs as $_tag => $_vlr) if (!is_array($_vlr)) $input .= " $_tag='$_vlr'";
				$input .= " />";
				if (!empty($opcs['value']) && isset($opcs['options'])) $ajax['value'] = $opcs['options'][$opcs['value']];
				$input .= "<img src='".$this->base."img/bt_ajax.png' class='bt_lista_ajax'
							onclick='
								$(\"#ajaxTit\").html(\"".$ajax['titPesq']."\");
								$(\"#ajaxCmp\").val(\"".$opcs['id']."\"); 
								$(\"#ajaxDest\").val(\"".$ajax['url']."\"); 
								showAjaxForm();' />";
				$input .= "<div id='".$ajax['cmp']."' class='ajaxDiv".$this->domId($a['2'])." div_ajax'>".$ajax['value']."&nbsp;&nbsp;&nbsp;";
				$input .= "</div>";
				break;
			case 'habtm':
				$input = "<img src='".$this->base."img/bt_ajax.png' class='bt_lista_ajax'";
				$input .= " onclick='showHabtmForm(\"habtm".$opcs['id']."\");' />";
				$input .= "<div id='habtm".$opcs['id']."' class='divHabtm'>";
				if (!empty($opcs['value']))
				{
					$t = 0;
					foreach($opcs['value'] as $_l => $_arrCmps)
					{
						$id 	= $_arrCmps['id'];
						$key 	= $_arrCmps[$e['key']['0']];
						$keyFK	= $_arrCmps[$e['keyFk']['0']];
						$t++;

						if ($_l) $input .= ', ';
						$input .= '<input type="hidden"';
						$input .= ' name="'.$opcs['name'].'['.$_l.']"';
						$input .= ' id="'.$opcs['id'].$_l.'"';
						$input .= ' class="'.$opcs['class'].'"';
						$input .= ' value="'.$key.'.'.$keyFK.'"';
						$input .= " />";

						$l = 0;
						foreach($_arrCmps as $_cmp => $_vlr)
						{
							if (!in_array($_cmp, array('id')))
							{
								$input .= "<span>$_vlr</span>";
								$l++;
								if ($l>0) break;
							}
						}
					}
				}
				//for($i=0; $i<$t; $i++) $input .= '&nbsp;';	$input .= "&nbsp;&nbsp;";
				$input .= "</div>";
				break;
			default:
				$input = "<input ";
				$tam = isset($e['length']) 	? $e['length'] : 0;
				$tam = isset($e['mascara']) ? strlen($e['mascara']) : $tam;
				if ($tam>0)
				{
					$opcs['maxlength'] = $tam;
				}
				foreach($opcs as $_tag => $_vlr)
				{
					$input .= " $_tag='$_vlr'";
				}
				$input .= " />";
		}
		$div = "<div";
		if (!empty($propDiv))
		{
			foreach($propDiv as $_tag => $_vlr)
			{
				$div .= " $_tag='$_vlr'";
			}
		}
		/*$div .= ">";
		$div .= $input;
		$div .= "</div>\n";*/
		$div = $input;
		return $div;
	}

	/**
	 * Retorna um valor mascarado
	 * 
	 * Créditos para Rafael Clares (http://blog.clares.com.br/php-mascara-cnpj-cpf-data-e-qualquer-outra-coisa/)
	 * 
	 * @param	string	$vlr		Valor do campo a ser mascarado
	 * @param	array	$prop		Propriedades do campo, oriundas do model
	 * @return	string	$mascarado 	Valor mascarado
	 */
	public function getMascara($vlr='', $prop=array())
	{
		$mascarado	= $vlr;
		$mascara	= isset($prop['mascara']) ? $prop['mascara'] : '';
		$prop['type']= isset($prop['type']) ? $prop['type'] : 'text';
		if (!empty($mascara))
		{
			$k 			= 0;
			$mascarado 	= '';
			for($i = 0; $i<=strlen($mascara)-1; $i++)
			{
				if (in_array($mascara[$i],array('#','9')))
				{
					if(isset($vlr[$k])) $mascarado .= $vlr[$k++];
				}  else
				{
					if(isset($mascara[$i])) $mascarado .= $mascara[$i];
				}
			}
		}
		// caso possua options
		if (isset($prop['options'])) $mascarado = $prop['options'][$vlr];

		switch($prop['type'])
		{
			case 'password':
				$mascarado = '';
				break;
		}

		return $mascarado;
	}

	/**
	 * Incrementa o cabeçalho head
	 * 
	 * @param	string	$tipo	Tipo CSS ou JS
	 * @param	string	$arq	Nome do arquivo css ou JS
	 */
	public function setHead($tipo='', $arq='')
	{
		switch($tipo)
		{
			case 'css':
				$_arq = htmlentities('<link rel="stylesheet" type="text/css" href="'.$this->base.'css/'.$arq.'.css" />');
				array_unshift($this->head,$_arq);
				break;
			case 'js':
				$_arq = htmlentities('<script type="text/javascript" src="'.$this->base.'js/'.$arq.'.js"></script>');
				array_unshift($this->head,$_arq);
				break;
		}
	}

	/**
	 * Retorna o primeiro campo de retorno de um relacionamento
	 * 
	 * @param	array	$p	Configuração do relacionamento, veja mais no model como é configurado.
	 * @return	string	$field	Nome do campo
	 */
	public function getFieldRel($p=array())
	{
		$field = '';
		foreach($p as $_rel => $_arrProp)
		{
			foreach($_arrProp['fields'] as $_l => $_cmp) if (empty($field) && !in_array($_cmp,array('id'))) $field = $_rel.'_'.$_cmp;
		}
		return $field;
	}

	/**
	 * Retorna o valor da permissão da ação desejada
	 * 
	 * @param	array	$minhasPermissoes	Atributo do objeto View com as devidas permissões do módulo/cadastro para teste
	 * @param	string	$acao	Ação a ser testada: visualizar, incluir, alterar, excluir, imprimir e pesquisar
	 * @return	int		1 se sim, 0 se não
	 */
	public function pode($acao='', $minhasPermissoes=array())
	{
		if ($_SESSION['Usuario']['perfil']=='ADMINISTRADOR') return true;
		if (isset($minhasPermissoes[$acao]))
		{
			if ($minhasPermissoes[$acao]==1) return true;
		}
		return false;
	}
}
