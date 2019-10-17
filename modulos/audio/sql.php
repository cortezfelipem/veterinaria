<?php
try {

	if(empty($id)) {

		$up = new Uploader('arquivo');
		$up->setDirectory("$caminhoUploadArquivo/");
		$arquivo = $up->uploadFile();
		$upf = new Uploader('foto');
		$upf->setDirectory("$caminhoUploadImagem/");
		$foto = $upf->uploadFile();

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO audio 
		                                                  SET descricao = :descricao,
		                                                  	  foto = :foto,
		                                                      arquivo = :arquivo");

		$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);
		$stCadastro->bindValue("foto", $foto, PDO::PARAM_STR);
		$stCadastro->bindValue("arquivo", $arquivo, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();
			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela&s=cadastrar', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stArquivo = Conexao::chamar()->prepare("SELECT arquivo
									  	 	           FROM audio
									 	  	          WHERE id = :id
										  	            AND status_registro = :status_registro");

		$stArquivo->execute(array("id" => $id, "status_registro" => "A"));
		$buscaArquivo = $stArquivo->fetch(PDO::FETCH_ASSOC);
		$up = new Uploader('arquivo');
		
		if($_FILES[arquivo][name] != '') {

			$up->setOldFileToRemove($buscaArquivo['arquivo']);
			$up->setDirectory("$caminhoUploadArquivo/");
			$arquivo = $up->uploadFile();
		}
		
		$stFoto = Conexao::chamar()->prepare("SELECT foto
												   FROM audio
												  WHERE id = :id
													AND status_registro = :status_registro");

		$stFoto->execute(array("id" => $id, "status_registro" => "A"));
		$buscaFoto = $stFoto->fetch(PDO::FETCH_ASSOC);
		$up1 = new Uploader('foto');
		
		if($_FILES[foto][name] != '') {

			$up1->setOldFileToRemove($buscaFoto['foto']);
			$up1->setDirectory("$caminhoUploadImagem/");
			$foto = $up1->uploadFile();

		} else {

			$arquivo = $buscaArquivo['arquivo'];
			$foto = $buscaFoto['foto'];
		}

		$stCadastro = Conexao::chamar()->prepare("UPDATE audio 
		                                             SET descricao = :descricao,
	                                                     arquivo = :arquivo,
	                                                     foto = :foto,
		                                           WHERE id = :id");
		
		$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);
		$stCadastro->bindValue("arquivo", $arquivo, PDO::PARAM_STR);
		$stCadastro->bindValue("foto", $foto, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			logAcesso("A", $tela, $id);

			echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

		}

	}

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}