<?php

try {


	if(empty($id)) {

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO curso 
		                                                  SET data_inicio = :data_inicio,
														  	  data_fim = :data_fim,
														  	  horario_inicio = :horario_inicio,
															  horario_fim = :horario_fim,	
															  local = :local,
															  preco = :preco, 
														      titulo = :titulo,
															  artigo = :artigo,
															  email = :email");


		$stCadastro->bindValue("data_inicio", formata_data_banco($data_inicio), PDO::PARAM_STR);
		$stCadastro->bindValue("data_fim", formata_data_banco($data_fim), PDO::PARAM_STR);
		$stCadastro->bindValue("horario_inicio", $horario_inicio, PDO::PARAM_STR);
		$stCadastro->bindValue("horario_fim", $horario_fim, PDO::PARAM_STR);
		$stCadastro->bindValue("local", $local, PDO::PARAM_STR);
		$stCadastro->bindValue("preco", $preco, PDO::PARAM_STR);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$stCadastro->bindValue("email", $email, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();

			$upFoto = true;
			$upAnexo = false;
			$upVideo = false;
			$upLink = false;
			$upTabela = "curso";
			$upRel = "id_curso";

			include_once "$root/privado/sistema/classes/includes/upload_cadastrar.php";

			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";
			var_dump($cadastro);

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stCadastro = Conexao::chamar()->prepare("UPDATE curso 
		                                             SET data_inicio = :data_inicio,
													 	 data_fim = :data_fim,
														 horario_inicio = :horario_inicio,
														 horario_fim = :horario_fim,
														 local = :local,
														 preco = :preco,
													 	 titulo = :titulo,
                                                      	 artigo = :artigo,
														 email = :email
												   WHERE id = :id");

		$stCadastro->bindValue("data_inicio", formata_data_banco($data_inicio), PDO::PARAM_STR);
		$stCadastro->bindValue("data_fim", formata_data_banco($data_fim), PDO::PARAM_STR);
		$stCadastro->bindValue("horario_inicio", $horario_inicio, PDO::PARAM_STR);
		$stCadastro->bindValue("horario_fim", $horario_fim, PDO::PARAM_STR);
		$stCadastro->bindValue("local", $local, PDO::PARAM_STR);
		$stCadastro->bindValue("preco", $preco, PDO::PARAM_STR);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$stCadastro->bindValue("email", $email, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$upFoto = true;
			$upAnexo = false;
			$upVideo = false;
			$upLink = false;
			$upTabela = "curso";
			$upRel = "id_curso";

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