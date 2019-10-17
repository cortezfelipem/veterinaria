<?php
try {

	if(empty($id)) {
	    
		$stCadastro = Conexao::chamar()->prepare("INSERT INTO link 
		                                                  SET id_categoria = :id_categoria,
                                                              titulo = :titulo,
		                                                      link = :link");
		
		$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("link", $link, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();
			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela&s=cadastrar&id_categoria=$id_categoria, 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {
	    
		$stCadastro = Conexao::chamar()->prepare("UPDATE link 
		                                             SET id_categoria = :id_categoria,
                                                         titulo = :titulo,
	                                                     link = :link
		                                           WHERE id = :id");
		
		$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("link", $link, PDO::PARAM_STR);
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