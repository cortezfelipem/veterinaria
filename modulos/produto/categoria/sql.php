<?php
try {

	if(empty($id)) {

		$stVerificaCategoria = Conexao::chamar()->prepare("SELECT COUNT(*) AS total FROM produto_categoria WHERE categoria = :categoria ");

		$stVerificaCategoria->bindValue(":categoria", $categoria, PDO::PARAM_STR);
		$stVerificaCategoria->execute();
		$verificaCategoria = $stVerificaCategoria->fetch(PDO::FETCH_ASSOC);

	    if($verificaCategoria['total'] == 0){

			$stCadastro = Conexao::chamar()->prepare("INSERT INTO produto_categoria 
			                                                  SET categoria = :categoria");

			$stCadastro->bindValue("categoria", $categoria, PDO::PARAM_STR);
			$cadastro = $stCadastro->execute();

			if($cadastro) {

				$id = Conexao::chamar()->lastInsertId();
				logAcesso("I", $tela, $id);

				echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

			}

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, existe uma categoria cadastrada no sistema com a mesma descrição");
		}

	} else {

		$stVerificaCategoria = Conexao::chamar()->prepare("SELECT COUNT(*) AS total FROM produto_categoria WHERE categoria = :categoria  AND id != :id");

		$stVerificaCategoria->bindValue(":categoria", $categoria, PDO::PARAM_STR);
		$stVerificaCategoria->bindValue("id", $id, PDO::PARAM_INT);
		$stVerificaCategoria->execute();
		$verificaCategoria = $stVerificaCategoria->fetch(PDO::FETCH_ASSOC);

	    if($verificaCategoria['total'] == 0){
	    
			$stCadastro = Conexao::chamar()->prepare("UPDATE produto_categoria 
			                                             SET categoria = :categoria
			                                           WHERE id = :id");

			$stCadastro->bindValue("categoria", $categoria, PDO::PARAM_STR);
			$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
			$cadastro = $stCadastro->execute();

			if($cadastro) {

				logAcesso("A", $tela, $id);

				echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

			}
			
		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, existe uma categoria cadastrada no sistema com a mesma descrição");
		}

	}

} catch (PDOException $e) {

    echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}