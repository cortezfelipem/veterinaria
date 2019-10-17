<?php
class ImageManager {
	private $file;

	public function __construct($file) {
		$this->file = $file;
	}

	/**
	 * Obtem detalhes da imagem
	 *
	 * @return array [width,height,aspect,ratio,depth,mime,type]
	 */
	public function getImageDetails() {
		$details = getimagesize($this->file);

		if(!$details) throw new Exception('Falha na busca dos parametros da imagem!');

		list($width, $height, $type, $attr, $mime) = $details;
		$aspect = ($width > $height)?'landscape':'portrait';
		$ration = $width / $height;

		return array('width' => $width,'height' => $height,'aspect' => $aspect,'ratio' => $ratio,'mime' => $mime,'type' => $type);
	}

	/**
	 * Gera um thumbanail
	 *
	 * @param string $name nome do arquivo de thumbnail (canonico)
	 * @param int $type tipo do arquivo (IMAGETYPE_XXX)
	 * @param int $width largura em px do thumbnail
	 * @param int $height altura em px do thumbnail
	 */
	public function thumbnalize($name, $type, $width, $height) {

		if(!is_writable(dirname($name))) throw new Exception("Falha na criacao do thumbnail - Nao foi possivel escrever na pasta de destino!");
		if(!is_readable($this->file)) throw new Exception("Falha na criacao do thumbnail - Nao foi possivel ler do arquivo de origem!");

		switch ($type) {
			case IMAGETYPE_JPEG:
				$tmp = @imagecreatefromjpeg($this->file);
				break;
			case IMAGETYPE_GIF:
				$tmp = @imagecreatefromgif($this->file);
				break;
			case IMAGETYPE_PNG:
				$tmp = imagecreatefrompng($this->file);
				break;
			default:
				throw new Exception("Falha na criacao do thumbnail - Tipo de imagem invalido!");
				break;
		}

		if(!$tmp) throw new Exception('Falha na criacao da imagem-matriz do thumbnail!');

		$srcW = imagesx($tmp);
		$srcH = imagesy($tmp);

		if(!$height && $width) $height = round($srcH * ($width / $srcW));
		if(!$width && $height) $width = round($srcW * ($height / $srcH));
		if(!$width && !$height) throw new Exception("Falha na criacao do thumbnail - Dimensoes invalidas!");

		$tumb = imagecreatetruecolor($width,$height);
		imagealphablending( $tumb, false );
		imagesavealpha( $tumb, true );

		if($srcH > imagesy($tmp)){
			$srcW = (imagesy($tmp)/$height)*$width;
			$srcH = imagesy($tmp);
			imagecopyresampled($tumb,$tmp,0,0,0,0, $width,$height,$srcW,$srcH);
		} else {
			imagecopyresampled($tumb,$tmp,0,0,0,0, $width,$height,$srcW,$srcH);
		}

		switch ($type) {
			case IMAGETYPE_JPEG:
				imagejpeg($tumb, $name, 80);
				break;
			case IMAGETYPE_GIF:
				imagegif($tumb, $name, 80);
				break;
			case IMAGETYPE_PNG:
				imagepng($tumb, $name, 9);
				break;
		}
	}
}
?>