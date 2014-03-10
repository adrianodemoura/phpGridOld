<?php
/**
 * Class Model
 * 
 * @package		Core
 * @package		Core.Model
 */
class Util extends Model {
	/**
	 * Executa a importação de um arquivo Sql.\n
	 * O banco de dados selecionado será o banco "default".
	 * 
	 * @param	string	$dir	Diretório aonde se encontro o arquivo
	 * @param	string	$arq	Nome do arquivo SQL a ser importado, sem a extenção.
	 * @param	string	array	Chave a ser subscrita na sql
	 * @return 	boolean
	 */
	/*public function getInstalaSql($dir='',$arq='',$repo=array())
	{
		// arquivo
		$arq = $dir.$arq.'.sql';

		// instala todas as tabelas do saae
		if (!file_exists($arq))
		{
			$this->erro = 'Não foi possível localicar o arquivo '.$arq;
			exit(utf8_decode('<center>não foi possível localizar o arquivo '.$arq.'</center>'));
			return false;
		}
		$handle  = fopen($arq,"r");
		$texto   = fread($handle, filesize($arq));
		if (count($repo))
		{
			foreach($repo as $_a => $_b)
			{
				$texto = str_replace($_a,$_b,$texto);
			}
		}
		
		$sqls	 = explode(";",$texto);
		fclose($handle);
		foreach($sqls as $sql) // executando sql a sql
		{
			if (trim($sql))
			{
				try
				{
					//$this->query($sql, $cachequeries=false);
					$this->query($sql);
				} catch (exception $ex)
				{
					die('erro ao executar: '.$sql.'<br />'.$ex->getMessage());
				}
			}
		}

		// descobrindo os arquivos CSV
		$arrCsv 	= array();
		$ponteiro	= opendir($dir);
		while ($nome_itens = readdir($ponteiro))
		{
			$arrNome = explode('.',$nome_itens);
			if (isset($arrNome['1']) && strtolower($arrNome['1'])=='csv') array_unshift($arrCsv,$arrNome['0']);
		}

		// atualiza outras tabelas vias CSV
		foreach($arrCsv as $tabela)
		{
			$this->setPopulaTabela($dir.$tabela.'.csv',$tabela,$bd);
		}

		return true;
	}*/

	/**
	 * Popula uma tabela do banco com seu aquivo CSV
	 * 
	 * @parameter 	$arq	string	Caminho completo com o nome do arquivo
	 * @parameter	$tabela	string	Nome da tabela a ser populada
	 * @return		boolean
	 */
	public function setPopulaTabela($arq='',$tabela='')
	{
		// mandando bala se o csv existe
		if (file_exists($arq))
		{
			$handle  	= fopen($arq,"r");
			$l 			= 0;
			$campos 	= '';
			$cmps	 	= array();
			$valores 	= '';

			// executando linha a linha
			while ($linha = fgetcsv($handle, 2048, ";"))
			{
				if (!$l)
				{
					$i = 0;
					$t = count($linha);
					foreach($linha as $campo)
					{
						$campos .= $campo;
						$i++;
						if ($i!=$t) $campos .= ',';
					}
					// montand os campos da tabela
					$arr_campos = explode(',',$campos);
				} else
				{
					$valores  = '';
					$i = 0;
					$t = count($linha);
					foreach($linha as $valor)
					{
						if ($arr_campos[$i]=='criado' || $arr_campos[$i]=='modificado') $valor = date("Y-m-d H:i:s");
						$valores .= "'".str_replace("'","\'",$valor)."'";
						$i++;
						if ($i!=$t) $valores .= ',';
					}
					$sql = 'INSERT INTO '.$tabela.' ('.$campos.') VALUES ('.$valores.')';
					try
					{
						$this->query($sql);
					} catch (exception $ex)
					{
						die('erro ao executar: '.$sql.'<br />'.$ex->getMessage());
					}
				}
				$l++;
			}
			fclose($handle);
			return true;
		} else return false;
	}
}
