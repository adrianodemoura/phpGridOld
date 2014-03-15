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
		$opcs['class'] 	= (isset($opcs['class'])) 	? $opcs['class'] 	: 'lista_input in_'.strtolower($a['2']);
		$idDiv			= (isset($opcs['idDiv'])) 	? $opcs['idDiv'] 	: null;
		if (!empty($idDiv)) unset($opcs['idDiv']);

		if (isset($opcs['options'])) $opcs['type'] = 'select';

		// se o campo é belongsTo
		if (isset($e['belongsTo']))
		{
			foreach($e['belongsTo'] as $_mod => $_arrProp)
			{
				if (isset($_arrProp['ajax']))
				{
					$fields = array();
					$cmpPes	= '';
					foreach($_arrProp['fields'] as $_cmp)
					{
						array_push($fields,$_mod.'.'.$_cmp);
						$cmpPes = $_cmp;
					}
					$aj				= explode('_',$cmp);
					$opcs['type'] 	= 'ajax';
					$ajax['value'] 	= '';
					$ajax['cmp']	= 'ajax'.$this->domId($cmp,'id');
					$ajax['url']	= $this->base.$_arrProp['ajax'].'cmps:'.implode(',',$fields).'/ord:'.$_mod.'.'.implode(',',$_arrProp['order']).'/'.$_mod.'.'.$cmpPes.':';
					foreach($linha[$_mod] as $_cmp => $_vlr) $ajax['value'] =$_vlr;
				}
			}
		}

		// se é ediçãoOff entõa é disabled
		if (isset($e['edicaoOff']) && $e['edicaoOff']==true)
		{
			$opcs['type'] = 'text';
			unset($opcs['options']);
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
			case 'ajax':
				$input = "<input ";
				$opcs['type'] = 'hidden';
				foreach($opcs as $_tag => $_vlr) $input .= " $_tag='$_vlr'";
				$input .= " />";
				$vlr = '';
				$input .= "<div id='".$ajax['cmp']."' style='float: left;'>".$ajax['value']."</div>";
				$input .= "<img src='".$this->base."img/bt_ajax.png' class='bt_lista_ajax' style='float: right;'
							onclick='
								$(\"#ajaxCmp\").val(\"".$opcs['id']."\"); 
								$(\"#ajaxDest\").val(\"".$ajax['url']."\"); 
								showAjaxForm();' />";
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
		if (!empty($idDiv)) $div .= " id='$idDiv'";
		$div .= ">$input</div>\n";
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
				array_push($this->head,$_arq);
				break;
			case 'js':
				$_arq = htmlentities('<script type="text/javascript" src="'.$this->base.'js/'.$arq.'.js"></script>');
				array_push($this->head,$_arq);
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
}
