<?php
try {

	if(empty($id)) {

		$up = new Uploader('foto');
		$up->setDirectory("$caminhoUploadImagem/");
		$foto = $up->uploadFile();

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO publicidade 
		                                                  SET titulo = :titulo,
		                                                  	  data_inicial = :data_inicial,
		                                                  	  hora_inicial = :hora_inicial,
		                                                  	  data_limite = :data_limite,
		                                                  	  hora_limite = :hora_limite,
		                                                  	  link = :link,
		                                                      foto = :foto");

		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("data_inicial", formata_data_banco($data_inicial), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_inicial", $hora_inicial, PDO::PARAM_STR);
		$stCadastro->bindValue("data_limite", formata_data_banco($data_limite), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_limite", $hora_limite, PDO::PARAM_STR);
		$stCadastro->bindValue("link", $linkPublicidade, PDO::PARAM_STR);
		$stCadastro->bindValue("foto", $foto, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();
			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela&s=cadastrar', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stArquivo = Conexao::chamar()->prepare("SELECT foto
									  	 	           FROM publicidade
									 	  	          WHERE id = :id
										  	            AND status_registro = :status_registro");

		$stArquivo->execute(array("id" => $id, "status_registro" => "A"));
		$buscaArquivo = $stArquivo->fetch(PDO::FETCH_ASSOC);

		$up = new Uploader('foto');
		if($_FILES[foto][name] != '') {

			$up->setOldFileToRemove($buscaArquivo['foto']);
			$up->setDirectory("$caminhoUploadImagem/");
			$foto = $up->uploadFile();

		} else {

			$foto = $buscaArquivo['foto'];
		}


		$stCadastro = Conexao::chamar()->prepare("UPDATE publicidade 
		                                             SET titulo = :titulo,
														 data_inicial = :data_inicial,
														 hora_inicial = :hora_inicial,
														 data_limite = :data_limite,
														 hora_limite = :hora_limite,
														 link = :link,
														 foto = :foto
												   WHERE id = :id");

		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("data_inicial", formata_data_banco($data_inicial), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_inicial", $hora_inicial, PDO::PARAM_STR);
		$stCadastro->bindValue("data_limite", formata_data_banco($data_limite), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_limite", $hora_limite, PDO::PARAM_STR);
		$stCadastro->bindValue("link", $linkPublicidade, PDO::PARAM_STR);
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