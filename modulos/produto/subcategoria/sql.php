<?php
try {

	if(empty($id)) {

		$stVerificaSubCategoria = Conexao::chamar()->prepare("SELECT COUNT(*) AS total FROM produto_subcategoria WHERE subcategoria = :subcategoria ");

		$stVerificaSubCategoria->bindValue(":subcategoria", $subcategoria, PDO::PARAM_STR);
		$stVerificaSubCategoria->execute();
		$verificaSubCategoria = $stVerificaSubCategoria->fetch(PDO::FETCH_ASSOC);

		if($verificaSubCategoria['total'] == 0){

			$id_atributo_1 = (empty($id_atributo_1)) ? 1 : $id_atributo_1;
			$id_atributo_2 = (empty($id_atributo_2)) ? 1 : $id_atributo_2;
			$id_atributo_3 = (empty($id_atributo_3)) ? 1 : $id_atributo_3;
		    
			$stCadastro = Conexao::chamar()->prepare("INSERT INTO produto_subcategoria 
			                                                  SET id_categoria = :id_categoria,
			                                                   	  id_atributo_1 = :id_atributo_1,
			                                             	      id_atributo_2 = :id_atributo_2,
			                                             	      id_atributo_3 = :id_atributo_3,
	                                                              subcategoria = :subcategoria");
			
			$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
			$stCadastro->bindValue("id_atributo_1", $id_atributo_1, PDO::PARAM_INT);
			$stCadastro->bindValue("id_atributo_2", $id_atributo_2, PDO::PARAM_INT);
			$stCadastro->bindValue("id_atributo_3", $id_atributo_3, PDO::PARAM_INT);
			$stCadastro->bindValue("subcategoria", $subcategoria, PDO::PARAM_STR);
			$cadastro = $stCadastro->execute();

			if($cadastro) {

				$id = Conexao::chamar()->lastInsertId();
				logAcesso("I", $tela, $id);

				echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

			}

		} else {
			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, existe uma subcategoria cadastrada no sistema com a mesma descrição");
		}

	} else {

		$stVerificaSubCategoria = Conexao::chamar()->prepare("SELECT COUNT(*) AS total FROM produto_subcategoria WHERE subcategoria = :subcategoria  AND  id != :id");

		$stVerificaSubCategoria->bindValue(":subcategoria", $subcategoria, PDO::PARAM_STR);
		$stVerificaSubCategoria->bindValue("id", $id, PDO::PARAM_INT);
		$stVerificaSubCategoria->execute();
		$verificaSubCategoria = $stVerificaSubCategoria->fetch(PDO::FETCH_ASSOC);

		if($verificaSubCategoria['total'] == 0){
	    
			$stCadastro = Conexao::chamar()->prepare("UPDATE produto_subcategoria 
			                                             SET id_categoria = :id_categoria,
			                                             	 id_atributo_1 = :id_atributo_1,
			                                             	 id_atributo_2 = :id_atributo_2,
			                                             	 id_atributo_3 = :id_atributo_3,
	                                                         subcategoria = :subcategoria
			                                           WHERE id = :id");
			
			$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
			$stCadastro->bindValue("id_atributo_1", $id_atributo_1, PDO::PARAM_INT);
			$stCadastro->bindValue("id_atributo_2", $id_atributo_2, PDO::PARAM_INT);
			$stCadastro->bindValue("id_atributo_3", $id_atributo_3, PDO::PARAM_INT);
			$stCadastro->bindValue("subcategoria", $subcategoria, PDO::PARAM_STR);
			$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
			$cadastro = $stCadastro->execute();

			if($cadastro) {

				logAcesso("A", $tela, $id);

				echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

			}

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, existe uma subcategoria cadastrada no sistema com a mesma descrição");
		
		}

	}

} catch (PDOException $e) {

    echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}