<?php

try {

	if(empty($id)) {

	  echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

	} else {
	  
		$stCadastro = Conexao::chamar()->prepare("UPDATE cliente_configuracao 
		                                             SET endereco = :endereco,
		                                             	 complemento = :complemento,
		                                             	 bairro = :bairro,
		                                             	 cep = :cep,
		                                             	 email =:email,
		                                             	 email_formulario =:email_formulario,
		                                             	 telefone = :telefone,
		                                             	 telefone2 = :telefone2,
		                                             	 telefone3 = :telefone3,
		                                             	 horario_atendimento = :horario_atendimento,
		                                             	 facebook = :facebook,
		                                             	 instagram = :instagram,
		                                             	 webmail = :webmail

		                                           			WHERE id = :id");

		$stCadastro->bindValue("endereco", $endereco, PDO::PARAM_STR);
		$stCadastro->bindValue("complemento", $complemento, PDO::PARAM_STR);
		$stCadastro->bindValue("bairro", $bairro, PDO::PARAM_STR);
		$stCadastro->bindValue("cep", $cep, PDO::PARAM_STR);
		$stCadastro->bindValue("email", $email, PDO::PARAM_STR);
		$stCadastro->bindValue("email_formulario", $email_formulario, PDO::PARAM_STR);
		$stCadastro->bindValue("telefone", $telefone, PDO::PARAM_STR);
		$stCadastro->bindValue("telefone2", $telefone2, PDO::PARAM_STR);
		$stCadastro->bindValue("telefone3", $telefone3, PDO::PARAM_STR);
		$stCadastro->bindValue("horario_atendimento", $horario_atendimento, PDO::PARAM_STR);
		$stCadastro->bindValue("facebook", $facebook, PDO::PARAM_STR);
		$stCadastro->bindValue("instagram", $instagram, PDO::PARAM_STR);
		$stCadastro->bindValue("webmail", $webmail, PDO::PARAM_STR);
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

    echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}