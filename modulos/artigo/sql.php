<?php
try {

	if(empty($id)) {

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO artigo 
		                                                  SET titulo = :titulo,
		                                                      artigo = :artigo");
		
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();

			$upFoto = true;
			$upAnexo = true;
			$upVideo = true;
			$upLink = false;
			$upTabela = "artigo";
			$upRel = "id_artigo";

			include_once "$root/privado/sistema/classes/includes/upload_cadastrar.php";

			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stCadastro = Conexao::chamar()->prepare("UPDATE artigo 
		                                             SET titulo = :titulo,
                                                      	 artigo = :artigo
												   WHERE id = :id");

		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$upFoto = true;
			$upAnexo = true;
			$upVideo = true;
			$upLink = false;
			$upTabela = "artigo";
			$upRel = "id_artigo";

			include_once "$root/privado/sistema/classes/includes/upload_alterar.php";

			logAcesso("A", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro alterado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

		}

	}

} catch (PDOException $e) {

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}