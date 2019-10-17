<?php
try {

	if(empty($id)) {
	    
		$stCadastro = Conexao::chamar()->prepare("INSERT INTO video
		                                                  SET titulo = :titulo,
                                                              artigo = :artigo,
		                                                      link = :link");
		
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$stCadastro->bindValue("link", $link_video, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();
			logAcesso("I", $tela, $id);
			//echo alerta("success", "<i class=\"fa fa-check\"></i> Registro cadastrado com sucesso.");
			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {
	    
		$stCadastro = Conexao::chamar()->prepare("UPDATE video
		                                             SET titulo = :titulo,
                                                         artigo = :artigo,
	                                                     link = :link
		                                           WHERE id = :id");

		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$stCadastro->bindValue("link", $link_video, PDO::PARAM_STR);
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