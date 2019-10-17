<?php
try {

	if(empty($id)) {

		$up = new Uploader('foto');
		$up->setDirectory("$caminhoUploadImagem/");
		$foto = $up->uploadFile();

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO popup 
		                                                  SET titulo = :titulo,
		                                                  	  data_inicial = :data_inicial,
		                                                  	  hora_inicial = :hora_inicial,
		                                                  	  data_limite = :data_limite,
		                                                  	  hora_limite = :hora_limite,
		                                                  	  endereco_eletronico = :endereco_eletronico,
		                                                      foto = :foto");

		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("data_inicial", formata_data_banco($data_inicial), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_inicial", $hora_inicial, PDO::PARAM_STR);
		$stCadastro->bindValue("data_limite", formata_data_banco($data_limite), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_limite", $hora_limite, PDO::PARAM_STR);
		$stCadastro->bindValue("endereco_eletronico", $endereco_eletronico, PDO::PARAM_STR);
		$stCadastro->bindValue("foto", $foto, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();
			logAcesso("I", $t, $id, $idCliente);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stArquivo = Conexao::chamar()->prepare("SELECT foto
									  	 	       FROM popup
									 	  	      WHERE id = :id
										  	        AND status_registro = :status_registro");

		$stArquivo->execute(array("id" => $id, "status_registro" => "A"));
		$buscaArquivo = $stArquivo->fetch(PDO::FETCH_ASSOC);

		$up = new Uploader('foto');
		if($_FILES['foto']['name'] != '') {

			$up->setOldFileToRemove($buscaArquivo['foto']);
			$up->setDirectory("$caminhoUploadImagem/");
			$foto = $up->uploadFile();

		} else {

			$foto = $buscaArquivo['foto'];
		}

		$stCadastro = Conexao::chamar()->prepare("UPDATE popup 
		                                             SET titulo = :titulo,
														 data_inicial = :data_inicial,
														 hora_inicial = :hora_inicial,
														 data_limite = :data_limite,
														 hora_limite = :hora_limite,
														 endereco_eletronico = :endereco_eletronico,
														 foto = :foto
												   WHERE id = :id");

		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("data_inicial", formata_data_banco($data_inicial), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_inicial", $hora_inicial, PDO::PARAM_STR);
		$stCadastro->bindValue("data_limite", formata_data_banco($data_limite), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_limite", $hora_limite, PDO::PARAM_STR);
		$stCadastro->bindValue("endereco_eletronico", $endereco_eletronico, PDO::PARAM_STR);
		$stCadastro->bindValue("foto", $foto, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			logAcesso("A", $t, $id, $idCliente);

			echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

		}

	}

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}