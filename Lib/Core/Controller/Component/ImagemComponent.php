<?php
/**
 * Classe para trabalhar com Imagnes
 * 
 * @package		Core
 * @subpackage	Core.Component
 */
class ImagemComponent {
	/**
	 * Retorna o local de nova imagem redimensionada
	 * 
	 * @param	array	$imagem		Propriedades da imagem original
	 * @param	integer	$largura	Largura da nova imagem em pixels
	 * @param	string	$pasta		Diretório aonde a nova imagem será criada
	 * @return	void
	 */
	public function Redimensionar($imagem=null, $largura=50, $pasta='uploads'){
		
		//$name = md5(uniqid(rand(),true));
		$name = $imagem['name'];

		if ($imagem['type']=="image/jpeg") 
		{
			$img = imagecreatefromjpeg($imagem['tmp_name']);
			$name = str_replace('.jpg','',$name).$largura;
		} else if ($imagem['type']=="image/gif")
		{
			$img = imagecreatefromgif($imagem['tmp_name']);
			$name = str_replace('.gif','',$name).$largura;
		} else if ($imagem['type']=="image/png")
		{
			$img = imagecreatefrompng($imagem['tmp_name']);
			$name = str_replace('.png','',$name).$largura;
		}
		$x   = imagesx($img);
		$y   = imagesy($img);
		$autura = ($largura * $y)/$x;

		$nova = imagecreatetruecolor($largura, $autura);
		imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $autura, $x, $y);

		if ($imagem['type']=="image/jpeg")
		{
			$local="$pasta/$name".".jpg";
			imagejpeg($nova, $local);
		} else if ($imagem['type']=="image/gif")
		{
			$local="$pasta/$name".".gif";
			imagejpeg($nova, $local);
		} else if ($imagem['type']=="image/png")
		{
			$local="$pasta/$name".".png";
			imagejpeg($nova, $local);
		}		

		imagedestroy($img);
		imagedestroy($nova);	

		return $name;
	}
}
?>
