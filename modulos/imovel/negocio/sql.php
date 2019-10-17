<?php
try {

	if(empty($id)) {

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO negocio 
		                                                  SET titulo = :titulo");
		
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();

			$upFoto = false;
			$upAnexo = false;
			$upVideo = false;
			$upLink = false;
			$upTabela = "negocio";
			$upRel = "id_artigo";

			include_once "$root/privado/sistema/classes/includes/upload_cadastrar.php";

			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stCadastro = Conexao::chamar()->prepare("UPDATE negocio 
		                                             SET titulo = :titulo
												   WHERE id = :id");

		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$upFoto = false;
			$upAnexo = false;
			$upVideo = false;
			$upLink = false;
			$upTabela = "negocio";
			$upRel = "id_artigo";

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