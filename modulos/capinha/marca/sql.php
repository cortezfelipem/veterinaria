<?php
try {

	if(empty($id)) {

		$stVerificaMarca = Conexao::chamar()->prepare("SELECT COUNT(*) AS total 
														 FROM capinha_marca 
														WHERE marca = :marca 
														  AND status_registro = :status_registro");

		$stVerificaMarca->bindValue(":marca", $marca, PDO::PARAM_STR);
		$stVerificaMarca->bindValue(":status_registro", "A", PDO::PARAM_STR);
		$stVerificaMarca->execute();
		$VerificaMarca = $stVerificaMarca->fetch(PDO::FETCH_ASSOC);

	    if($VerificaMarca['total'] == 0){

			$stCadastro = Conexao::chamar()->prepare("INSERT INTO capinha_marca 
			                                                  SET marca = :marca");

			$stCadastro->bindValue("marca", $marca, PDO::PARAM_STR);
			$cadastro = $stCadastro->execute();

			if($cadastro) {

				$id = Conexao::chamar()->lastInsertId();
				logAcesso("I", $tela, $id);

				echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

			}

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, existe uma marca cadastrada no sistema com a mesma descrição");
		}

	} else {

		$stVerificaMarca = Conexao::chamar()->prepare("SELECT COUNT(*) AS total FROM capinha_marca WHERE marca = :marca  AND id != :id");

		$stVerificaMarca->bindValue(":marca", $marca, PDO::PARAM_STR);
		$stVerificaMarca->bindValue("id", $id, PDO::PARAM_INT);
		$stVerificaMarca->execute();
		$VerificaMarca = $stVerificaMarca->fetch(PDO::FETCH_ASSOC);

	    if($VerificaMarca['total'] == 0){
	    
			$stCadastro = Conexao::chamar()->prepare("UPDATE capinha_marca 
			                                             SET marca = :marca
			                                           WHERE id = :id");

			$stCadastro->bindValue("marca", $marca, PDO::PARAM_STR);
			$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
			$cadastro = $stCadastro->execute();

			if($cadastro) {

				logAcesso("A", $tela, $id);

				echo "<script>alerta('$caminhoTela', 'Registro alterado com sucesso', 'success');</script>";

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

			}
			
		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, existe uma marca cadastrada no sistema com a mesma descrição");
		}

	}

} catch (PDOException $e) {

    echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}