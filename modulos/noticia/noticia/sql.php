<?php
try {

	if(empty($id)) {

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO noticia 
		                                                  SET data_noticia = :data_noticia,
														  	  hora_noticia = :hora_noticia,
		                                                      tipo = :tipo,
		                                                      chapeu = :chapeu,
		                                                      fonte = :fonte,
		                                                      titulo = :titulo,
		                                                      artigo = :artigo");

		$stCadastro->bindValue("data_noticia", formata_data_banco($data_noticia), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_noticia", $hora_noticia, PDO::PARAM_STR);
		$stCadastro->bindValue("tipo", $tipo, PDO::PARAM_STR);
		$stCadastro->bindValue("chapeu", $chapeu, PDO::PARAM_STR);
		$stCadastro->bindValue("fonte", $fonte, PDO::PARAM_STR);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();

			$upFoto = true;
			$upAnexo = true;
			$upVideo = true;
			$upLink = false;
			$upTabela = "noticia";
			$upRel = "id_noticia";

			include_once "$root/privado/sistema/classes/includes/upload_cadastrar.php";

			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stCadastro = Conexao::chamar()->prepare("UPDATE noticia 
		                                             SET data_noticia = :data_noticia,
													 	 hora_noticia = :hora_noticia,
													  	 tipo = :tipo,
													  	 chapeu = :chapeu,
													  	 fonte = :fonte,
													  	 titulo = :titulo,
													  	 artigo = :artigo
												   WHERE id = :id");

		$stCadastro->bindValue("data_noticia", formata_data_banco($data_noticia), PDO::PARAM_STR);
		$stCadastro->bindValue("hora_noticia", $hora_noticia, PDO::PARAM_STR);
		$stCadastro->bindValue("tipo", $tipo, PDO::PARAM_STR);
		$stCadastro->bindValue("chapeu", $chapeu, PDO::PARAM_STR);
		$stCadastro->bindValue("fonte", $fonte, PDO::PARAM_STR);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$upFoto = true;
			$upAnexo = true;
			$upVideo = true;
			$upLink = false;
			$upTabela = "noticia";
			$upRel = "id_noticia";

			include_once "$root/privado/sistema/classes/includes/upload_alterar.php";

			logAcesso("A", $tela, $id);

			echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

		}

	}

} catch (PDOException $e) {

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}