<?php
try {

	if(empty($id)) {
	    
	    $up = new Uploader('arquivo');
	    $up->setDirectory("$caminhoUploadArquivo/");
	    $arquivo = $up->uploadFile();
	    
		$stCadastro = Conexao::chamar()->prepare("INSERT INTO download 
		                                                  SET id_categoria = :id_categoria,
                                                              titulo = :titulo,
		                                                      arquivo = :arquivo");
		
		$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("arquivo", $arquivo, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();
			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela&s=cadastrar&id_categoria=$id_categoria', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stArquivo = Conexao::chamar()->prepare("SELECT arquivo
									  	 	           FROM download
									 	  	          WHERE id = :id
										  	            AND status_registro = :status_registro");
		 
		$stArquivo->execute(array("id" => $id, "status_registro" => "A"));
		$buscaArquivo = $stArquivo->fetch(PDO::FETCH_ASSOC);
		
	    $up = new Uploader('arquivo');
	    if($_FILES[arquivo][name] != '') {
	    
	        $up->setOldFileToRemove($buscaArquivo['arquivo']);
	        $up->setDirectory("$caminhoUploadArquivo/");
	        $arquivo = $up->uploadFile();
	    
	    } else {
	    
	        $arquivo = $buscaArquivo['arquivo'];
	    }
	    
		$stCadastro = Conexao::chamar()->prepare("UPDATE download 
		                                             SET id_categoria = :id_categoria,
                                                         titulo = :titulo,
	                                                     arquivo = :arquivo
		                                           WHERE id = :id");
		
		$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("arquivo", $arquivo, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_STR);
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