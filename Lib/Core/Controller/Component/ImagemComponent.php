<?php
/**
 * Classe para trabalhar com Imagnes
 * 
 * @package		Core
 * @subpackage	Core.Component
 */
class ImagemComponent {
	/**
	 * Erros
	 * 
	 * @var		string
	 * @access	public
	 */
	public $erro	= '';

	/**
	 * Nome do arquivo
	 * 
	 * @var 	string
	 * @access	public
	 */
	public $name	= '';

	/**
	 * Retorna o local de nova imagem redimensionada
	 * 
	 * @param	array	$imagem		Propriedades da imagem original
	 * @param	integer	$largura	Largura da nova imagem em pixels
	 * @param	string	$pasta		Diretório aonde a nova imagem será criada
	 * @return	void
	 */
	public function Redimensionar($imagem=null, $largura=50, $pasta='uploads')
	{
		// verifica se o diretório tem escrita
		if (!is_writable($pasta))
		{
			$this->erro = 'O Diretório <b>'.$pasta.'</b> não possui permissão de escrita !!!';
			return false;
		}

		//$name = md5(uniqid(rand(),true));
		$name = $imagem['name'];

		if ($imagem['type']=="image/jpeg") 
		{
			$img = imagecreatefromjpeg($imagem['tmp_name']);
			$name = str_replace('.jpg','',strtolower($name)).'-'.$largura;
		} else if ($imagem['type']=="image/gif")
		{
			$img = imagecreatefromgif($imagem['tmp_name']);
			$name = str_replace('.gif','',strtolower($name)).'-'.$largura;
		} else if ($imagem['type']=="image/png")
		{
			$img = imagecreatefrompng($imagem['tmp_name']);
			$name = str_replace('.png','',strtolower($name)).'-'.$largura;
		}
		$x   = imagesx($img);
		$y   = imagesy($img);
		$autura = ($largura * $y)/$x;

		$nova = imagecreatetruecolor($largura, $autura);
		imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $autura, $x, $y);

		// escrevendo o novo arquivo em sua pasta de destino
		if ($imagem['type']=="image/jpeg")
		{
			$local		="$pasta/$name".".jpg";
			$this->name = "$name".".jpg";
			imagejpeg($nova, $local);
		} else if ($imagem['type']=="image/gif")
		{
			$local		="$pasta/$name".".gif";
			$this->name = "$name".".gif";
			imagejpeg($nova, $local);
		} else if ($imagem['type']=="image/png")
		{
			$local		="$pasta/$name".".png";
			$this->name = "$name".".png";
			imagejpeg($nova, $local);
		}		

		imagedestroy($img);
		imagedestroy($nova);	

		return true;
	}
}
?>
