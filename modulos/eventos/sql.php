<?php
try {

	if(empty($id)) {

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO eventos 
		                                                  SET evento = :evento,
															  data_inicio = :data_inicio,
															  data_fim = :data_fim,
															  cor = :cor,
															  horario = :horario,
															  local = :local,
															  observacao = :observacao");
		
		$stCadastro->bindValue("evento", $evento, PDO::PARAM_STR);
		$stCadastro->bindValue("data_inicio", formata_data_banco($data_inicio), PDO::PARAM_STR);
		$stCadastro->bindValue("data_fim", formata_data_banco($data_fim), PDO::PARAM_STR);
		$stCadastro->bindValue("cor", $cor, PDO::PARAM_STR);
		$stCadastro->bindValue("horario", $horario, PDO::PARAM_STR);
		$stCadastro->bindValue("local", $local, PDO::PARAM_STR);
		$stCadastro->bindValue("observacao", $observacao, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();

			$upFoto = true;
			$upAnexo = true;
			$upVideo = true;
			$upLink = false;
			$upTabela = "eventos";
			$upRel = "id_artigo";
			
			include_once "$root/privado/sistema/classes/includes/upload_cadastrar.php";

			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stCadastro = Conexao::chamar()->prepare("UPDATE eventos 
		                                            SET evento = :evento,
														data_inicio = :data_inicio,
														data_fim = :data_fim,
														cor = :cor,
														horario = :horario,
														local = :local,
														observacao = :observacao
												   WHERE id = :id");

		$stCadastro->bindValue("evento", $evento, PDO::PARAM_STR);
		$stCadastro->bindValue("data_inicio", formata_data_banco($data_inicio), PDO::PARAM_STR);
		$stCadastro->bindValue("data_fim", formata_data_banco($data_fim), PDO::PARAM_STR);
		$stCadastro->bindValue("cor", $cor, PDO::PARAM_STR);
		$stCadastro->bindValue("horario", $horario, PDO::PARAM_STR);
		$stCadastro->bindValue("local", $local, PDO::PARAM_STR);
		$stCadastro->bindValue("observacao", $observacao, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$upFoto = true;
			$upAnexo = true;
			$upVideo = true;
			$upLink = false;
			$upTabela = "eventos";
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