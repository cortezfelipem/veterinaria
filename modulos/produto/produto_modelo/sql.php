<?php

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

try {

	if(empty($id)) {

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO produto_pai 
		                                                  SET id_categoria = :id_categoria,
		                                                      id_marca = :id_marca,
		                                                      id_subcategoria = :id_subcategoria,
		                                                      titulo = :titulo,
		                                                      descricao = :descricao");

		$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
		$stCadastro->bindValue("id_marca", $id_marca, PDO::PARAM_INT);
		$stCadastro->bindValue("id_subcategoria", empty($id_subcategoria) ? NULL : $id_subcategoria, PDO::PARAM_INT);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);

		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();

			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stCadastro = Conexao::chamar()->prepare("UPDATE produto_pai  
		                                             SET id_categoria = :id_categoria,
													  	 id_subcategoria = :id_subcategoria,
													  	 id_marca = :id_marca,
													  	 titulo = :titulo,
													  	 descricao = :descricao
												   WHERE id = :id");

		$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
		$stCadastro->bindValue("id_marca", $id_marca, PDO::PARAM_INT);
		$stCadastro->bindValue("id_subcategoria", $id_subcategoria, PDO::PARAM_INT);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			
			logAcesso("A", $tela, $id);

			echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

		}

	}

} catch (PDOException $e) {

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}