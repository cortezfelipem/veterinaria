<?php
	try {

		if(empty($id)) {
			
			$stVerificaModelo = Conexao::chamar()->prepare("SELECT COUNT(*) AS total 
															  FROM capinha_modelo 
															 WHERE modelo = :modelo 
															   AND status_registro = :status_registro");

			$stVerificaModelo->bindValue(":modelo", $modelo, PDO::PARAM_STR);
			$stVerificaModelo->bindValue(":status_registro", "A", PDO::PARAM_STR);
			$stVerificaModelo->execute();
			$VerificaModelo = $stVerificaModelo->fetch(PDO::FETCH_ASSOC);

			if($VerificaModelo['total'] == 0){

				$stCadastro = Conexao::chamar()->prepare("INSERT INTO capinha_modelo 
																  SET id_marca = :id_marca,
															    	  modelo = :modelo");
				
				$stCadastro->bindValue("id_marca", $id_marca, PDO::PARAM_INT);
				$stCadastro->bindValue("modelo", $modelo, PDO::PARAM_STR);
				$cadastro = $stCadastro->execute();

				if($cadastro) {

					$id = Conexao::chamar()->lastInsertId();
					logAcesso("I", $tela, $id);

					echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

				} else {

					echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

				}

			} else {
				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, existe um modelo cadastrada no sistema com a mesma descrição");
			}

		} else {

			$stVerificaModelo = Conexao::chamar()->prepare("SELECT COUNT(*) AS total 
															  FROM capinha_modelo 
															 WHERE modelo = :modelo  
															   AND id != :id");

			$stVerificaModelo->bindValue(":modelo", $modelo, PDO::PARAM_STR);
			$stVerificaModelo->bindValue("id", $id, PDO::PARAM_INT);
			$stVerificaModelo->execute();
			$VerificaModelo = $stVerificaModelo->fetch(PDO::FETCH_ASSOC);

			if($VerificaModelo['total'] == 0){
	    
				$stCadastro = Conexao::chamar()->prepare("UPDATE capinha_modelo 
															 SET id_marca = :id_marca,
																 modelo = :modelo
														   WHERE id = :id");
				
				$stCadastro->bindValue("id_marca", $id_marca, PDO::PARAM_INT);
				$stCadastro->bindValue("modelo", $modelo, PDO::PARAM_STR);
				$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
				$cadastro = $stCadastro->execute();

				if($cadastro) {

					logAcesso("A", $tela, $id);

					echo "<script>alerta('$caminhoTela', 'Registro alterado com sucesso', 'success');</script>";

				} else {

					echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

				}

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, existe um modelo cadastrada no sistema com a mesma descrição");
			
			}

		}

	} catch (PDOException $e) {

		echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
		echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

	}