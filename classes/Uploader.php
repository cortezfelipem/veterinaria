<?php
class Uploader {

	private $field;
	private $dir;
	private $pattern;
	private $remove = false;
	private $old_name = null;
	private $thumb_preffix = 't_';
	private $thumb2_preffix = 'tb_';
	private $copy_preffix = 'gd_';
	private $original = false;

	public function __construct($field) {
		$this->field = $field;
	}

	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}

	public function getPattern() {
		return explode('|',$this->pattern);
	}

	public function setOldFileToRemove($file) {
		$this->old_name = $file;
		$this->remove = true;
	}

	public function setDirectory($dir) {
		$this->dir = $dir;
	}

	private function generateName($name,$ext) {
		while(file_exists($this->dir.($file = substr(md5(uniqid(time())),0,12)).".$ext"));
		return $file;
	}

	public function uploadFile() {
		if((!isset($_FILES[$this->field]) || $_FILES[$this->field]['error'] == 4) && !is_null($this->old_name)) return $this->old_name;

		if(!isset($_FILES[$this->field]['name']) || $_FILES[$this->field]['name'] == '') return '';

		$files = array();
		if(is_array($_FILES[$this->field]['name'])) {
			$multi_field = true;
			$file_result = array();
			foreach($_FILES[$this->field]['name'] as $index => $value) {
				foreach(array_keys($_FILES[$this->field]) as $key) {
					$files[$index][$key] = $_FILES[$this->field][$key][$index];
				}
			}
		} else {
			$multi_field = false;
			$files[0] = $_FILES[$this->field];
		}

		if(is_null($this->dir)) $this->dir = 'arquivos/uploads/';
		if(is_null($this->pattern)) $this->pattern = 'pdf|txt|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|mp3|mp4|avi|flv|wmv|wma|gif|png|jpg|jpeg|ico|csv|cot|esl|vcf|p12|pfx|xml|EWS|zip|tst|dwg|cdr|plt|log|bak|lsp|kit|psd';

		foreach($files as $file_field) {

			if($file_field['error'] == UPLOAD_ERR_OK) {

				if(preg_match("/\.($this->pattern){1}$/i", $file_field['name'], $ext)) {

					//$name = substr($file_field['name'],0,(strlen($ext[1])*-1)+1);
					//$ext  = strtolower($ext[1]);
					//$name = $this->generateName($name,$ext);
					$file_name = explode('.',$file_field['name']);
					$ext = array_pop($file_name);
					//$file = substr(md5(uniqid(time())),0,12) . "." . $ext;
					//$file = "$name.$ext";

					$conf_zip = false;

					if($ext == "zip") {

						$invalidos = 0;
						if ($zip = zip_open($file_field['tmp_name'])) {

							while ($zip_entry = zip_read($zip)) {

								if (zip_entry_open($zip,$zip_entry,"r")) {

									if(!preg_match("/\.($this->pattern){1}$/i", zip_entry_name($zip_entry)))
										$invalidos++;
								}
							}

							zip_close($zip);
						}

						if($invalidos < 1) $conf_zip = true;

					} else {

						$conf_zip = true;
					}

					if($conf_zip) {

						$file = date("dmyHis") . "_" . nomear_pasta($file_field['name']) . "." . $ext;
						$cannonical = $this->dir.$file;

						if($this->remove && (boolean)$this->old_name) {
							@unlink($this->dir.$this->old_name);
						}

						if(!file_exists(dirname($cannonical))) {

							mkdir(dirname($cannonical));
							chmod(dirname($cannonical), 777);
						}

						if(!move_uploaded_file($file_field['tmp_name'], $cannonical)) {
							$erro = 'Falha na grava&ccedil;&atilde;o do arquivo!';
							if(!is_writable($this->dir.$this->file)) {
								$erro .= ' - N&atilde;o foi poss&iacute;vel escrever na pasta definitiva!';
							}

							if(!is_readable($file_field['tmp_name'])) {
								$erro .= ' - N&atilde;o foi poss&iacute;vel ler da pasta tempor&aacute;ria!';
							}
						} else {
							if($multi_field) $file_result[] = $file;
							            else return $file;
						}
					} else {

						$erro = "O arquivo zip enviado possui formatos de arquivos inv&aacute;lidos que podem comprometer a seguran&ccedil;a!";
					}
				} else {

					$erro = "O tipo de arquivo " . strtoupper(array_pop(explode('.',$file_field['name']))) . " &eacute; inv&aacute;lido e pode comprometer a seguran&ccedil;a do sistema!";
				}
			} else {
				switch ($file_field['error']) {
					case UPLOAD_ERR_INI_SIZE:
						$erro .= 'Falha no upload do arquivo! - O tamanho do arquivo excedeu '. ini_get('upload_max_filesize') .'!';
						break;
					case UPLOAD_ERR_FORM_SIZE:
						$erro .= 'Falha no upload do arquivo! - O tamanho do arquivo excedeu '. $_POST['MAX_FILE_SIZE'] .'!';
						break;
					case UPLOAD_ERR_PARTIAL:
						$erro .= 'Falha no upload do arquivo! - O arquivo foi parcialmente recebido!';
						break;
					case UPLOAD_ERR_NO_FILE:
						//$erro .= 'Falha no upload do arquivo! - Nenhum arquivo foi recebido!';
						break;
					case UPLOAD_ERR_NO_TMP_DIR:
						$erro .= 'Falha no upload do arquivo! - N&atilde;o foi localizada uma pasta tempor&aacute;ria!';
						break;
					case UPLOAD_ERR_CANT_WRITE:
						$erro .= 'Falha no upload do arquivo! - N&atilde;o foi poss&iacute;vel escrever na pasta tempor&aacute;ria!';
						break;
					case UPLOAD_ERR_EXTENSION:
						$erro .= 'Falha no upload do arquivo! - O upload foi bloqueado!';
						break;
				}
			}

			//if(isset($erro)) throw new Exception($erro);
			if(isset($erro)) exit($erro);
		}

		return $file_result;
	}

	public function uploadImage($wgd = "",$hgd = "",$wtb = "",$htb = "") {
		if(is_null($this->dir)) $this->dir = '../imagens/';
		if(is_null($this->pattern)) $this->pattern = 'gif|png|jpg|jpeg|ico';

		$file = $this->uploadFile();

		if(is_array($file)) {
			$multi_field = true;
			$file_result = array();
			if(!$file) return array();
		} else {
			$multi_field = false;
			if(!$file) return '';
			$file = array($file);
		}

		foreach($file as $afile) {
			$cannonical = $this->dir.$afile;

			if($this->remove) {
				if($multi_field) throw new Exception('N&atilde;o &eacute; poss&iacute;vel remover arquivos de campos multivalorados!');
				@unlink($this->dir.$this->thumb_preffix.$this->old_name);
				@unlink($this->dir.$this->copy_preffix.$this->old_name);
			}

			$imm = new ImageManager($cannonical);
			$detail = $imm->getImageDetails();

			switch ($detail['aspect']) {
				case 'landscape':
					$gd_w = ($wgd ? $wgd : 860);
					$gd_h = NULL;
					$tb_w = ($wtb ? $wtb : 500);
					$tb_h = NULL;
					$t_w = 50;
					$t_h = 50;
				break;

				case 'portrait':
					$gd_w = NULL;
					$gd_h = ($hgd ? $hgd : 860);
					$tb_w = NULL;
					$tb_h = ($wtb ? $wtb : 500);
					$t_w = 50;
					$t_h = 50;
				break;
			}

			$imm->thumbnalize($this->dir.$this->copy_preffix.$afile,$detail['type'],$gd_w,$gd_h);
			$imm->thumbnalize($this->dir.$this->thumb2_preffix.$afile,$detail['type'],$tb_w,$tb_h);
			$imm->thumbnalize($this->dir.$this->thumb_preffix.$afile,$detail['type'],$t_w,$t_h);

			if(!$this->original) {
				@unlink($this->dir.$this->old_name);
			}

			if($multi_field) $file_result[] = $afile;
			            else return $afile;
		}

		return $file_result;
	}
}