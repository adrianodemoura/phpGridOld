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
	 * @return	$input	string
	 */
	public function getInput($cmp='', $opcs=array(), $e=array())
	{
		$e['type'] 		= isset($e['type']) ? $e['type'] :  'text';
		$opcs['name']  	= $this->domId($cmp,'name');
		$opcs['id']  	= $this->domId($cmp,'id');
		$opcs['type']	= isset($opcs['type']) ? $opcs['type'] : $e['type'];
		$a = explode('.',$cmp);

		if (isset($e['edicaoOff']) && $e['edicaoOff']==true) $opcs['disabled'] = 'disabled';
		if (isset($e['options'])) $opcs['options'] = $e['options'];
		if ($e['type']=='password') $opcs['value'] = '';

		$opcs['type'] 	= (isset($opcs['type']))  	? $opcs['type'] 	: 'text';
		$opcs['class'] 	= (isset($opcs['class'])) 	? $opcs['class'] 	: 'lista_input in_'.strtolower($a['2']);
		$idDiv			= (isset($opcs['idDiv'])) 	? $opcs['idDiv'] 	: null;
		if (!empty($idDiv)) unset($opcs['idDiv']);
		
		if (isset($opcs['options']))
		{
			$opcs['type'] = 'select';
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
}