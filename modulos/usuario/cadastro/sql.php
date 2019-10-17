<?php


try {

	$id_cliente = $buscaAdministrador['id_cliente'];

	if(empty($id)) {
		

		$resultado = Conexao::chamar()->prepare("SELECT id
														 FROM usuario
														 WHERE email = :email");
		$resultado->bindValue("email", $email, PDO::PARAM_STR);
		$resultado->execute();
		$retorno = $resultado->fetchAll(PDO::FETCH_ASSOC);

		

		if(count($retorno) == 0){
			
			$up = new Uploader('foto');
			$up->setDirectory("$caminhoUploadImagem/$id_cliente/");
			$foto = $up->uploadFile();

			$stCadastro = Conexao::chamar()->prepare("INSERT INTO usuario
			                                                  SET id_cliente = :id_cliente,
			                                                  	  usuario.usuario = :usuario,
			                                                  	  email = :email,
			                                                  	  senha = :senha,
			                                                  	  telefone = :telefone,
			                                                  	  celular = :celular,
			                                                  	  data_validade = :data_validade,
			                                                  	  recupera_senha = :recupera_senha,
			                                                  	  foto = :foto,
			                                                  	  master = :master,
			                                                  	  permissao_log = :permissao_log");

			$stCadastro->bindValue("id_cliente", $buscaAdministrador['id_cliente'], PDO::PARAM_INT);
			$stCadastro->bindValue("usuario", $usuario, PDO::PARAM_STR);
			$stCadastro->bindParam("senha", gerarHash("dona_helena"), PDO::PARAM_STR);
			$stCadastro->bindParam("recupera_senha", gerarHash($email), PDO::PARAM_STR);
			$stCadastro->bindValue("email", $email, PDO::PARAM_STR);
			$stCadastro->bindValue("telefone", $telefone, PDO::PARAM_STR);
			$stCadastro->bindValue("celular", $celular, PDO::PARAM_STR);
			$stCadastro->bindValue("data_validade", formata_data_banco($data_validade), PDO::PARAM_STR);
			$stCadastro->bindValue("foto", $foto, PDO::PARAM_STR);
			$stCadastro->bindValue("master", "N", PDO::PARAM_STR);
			$stCadastro->bindValue("permissao_log", "N", PDO::PARAM_STR);
			$cadastro = $stCadastro->execute();

			if($cadastro) {


				 $stUsuario = Conexao::chamar()->prepare("SELECT usuario.id,
														usuario.email,
														usuario.usuario,
														usuario.recupera_senha,
		 												cliente.nome_fantasia cliente_nome,
		 												cliente.email cliente_email
            									   FROM usuario
            							     INNER JOIN cliente
													 ON usuario.id_cliente = cliente.id
            									  WHERE usuario.email = :email
            									    AND usuario.status_registro = :status_registro
            									  LIMIT 1");
	    
			    $stUsuario->execute(array("email" => $email, "status_registro" => "A"));
			    $buscaUsuario = $stUsuario->fetch(PDO::FETCH_ASSOC);


			    
			    $id = $buscaUsuario['id'];
				logAcesso("I", $tela, $id);

				
				$hash_novo = $buscaUsuario['recupera_senha'];
				$nome = $buscaUsuario['cliente_nome'];
				$remetente = $buscaUsuario['cliente_email'];
				$to = $buscaUsuario['email'];

				$headers = "From: $nome <$remetente> \r\n";
				$headers.= "Return-Path: $nome <$remetente> \r\n";
				$headers.= "Content-Type: text/html; charset=ISO-8859-1 ";
				$headers.= "MIME-Version: 1.0 ";

				$subject = "Novo Cadastro ( $nome ) ";
				$titulo = "Novo Cadastro ( $nome )";
				$corpo = "<p><b>Bem vindo; $buscaUsuario[usuario]</b>, segue o link para cadastrar a senha para o acesso do sistema - $nome.</p>";
				$link = "$CAMINHO/cadastro_senha.php?token=$hash_novo";
				$titulo_botao = "Cadastrar Senha";
				$rodape = "<p>&nbsp;</p><p>Origem: ".$_SERVER['REMOTE_ADDR'].", enviado em " . date("d/m/Y H:i") . " por $nome</p>";

				$html = recupera_senha_email($titulo,$corpo,$link,$titulo_botao,$rodape);
				$vai = mail($to, html_entity_decode($subject), $html, $headers);


				if($vai){
				
					echo "<script>window.location='$CAMINHO/inicio.php?m=&t=20&id=$id&s=cadastrar&time=".time()."'</script>";
				
				} else {
					echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel enviar o email para o usu&aacute;rio.");

				}

				

				

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

			}
		} else {
			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, este email est&aacute; sendo utilizado por outro usu&aacute;rio.");
		}

	} else {
		

		$resultado = Conexao::chamar()->prepare("SELECT id
												   FROM usuario
												  WHERE email = :email
												       AND id != :id ");

		$resultado->bindValue("email", $email, PDO::PARAM_STR);
		$resultado->bindValue("id", $id, PDO::PARAM_INT);
		$resultado->execute();
		$retorno = $resultado->fetchAll(PDO::FETCH_ASSOC);

		

		if(count($retorno) == 0){

		
			$stArquivo = Conexao::chamar()->prepare("SELECT foto
										  	 	       FROM usuario
										 	  	      WHERE id = :id
											  	        AND status_registro = :status_registro");

			$stArquivo->execute(array("id" => $id, "status_registro" => "A"));
			$buscaArquivo = $stArquivo->fetch(PDO::FETCH_ASSOC);


			$up = new Uploader('foto');
			if($_FILES[foto][name] != '') {

				$up->setOldFileToRemove($buscaArquivo['foto']);
				$up->setDirectory("$caminhoUploadImagem/$id_cliente/");

				$foto = $up->uploadFile();

			} else {

				$foto = $buscaArquivo['foto'];
			}


			$stCadastro = Conexao::chamar()->prepare(" UPDATE usuario

		                                                  SET usuario = :usuario,
		                                                  	  email = :email,
		                                                  	  telefone = :telefone,
		                                                  	  celular = :celular,
		                                                  	  data_validade = :data_validade,
		                                                      foto = :foto

		                                                WHERE id = :id");

			$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
			$stCadastro->bindValue("usuario", $usuario, PDO::PARAM_STR);
			$stCadastro->bindValue("email", $email, PDO::PARAM_STR);
			$stCadastro->bindValue("telefone", $telefone, PDO::PARAM_STR);
			$stCadastro->bindValue("celular", $celular, PDO::PARAM_STR);
			$stCadastro->bindValue("data_validade", formata_data_banco($data_validade), PDO::PARAM_STR);
			$stCadastro->bindValue("foto", $foto, PDO::PARAM_STR);
			$cadastro = $stCadastro->execute();

			if($cadastro) {

				logAcesso("A", $tela, $id);

				echo "<script>window.location='$CAMINHO/inicio.php?m=&t=20&id=$id&s=cadastrar&time=".time()."'</script>";

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

			}

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro, este email est&aacute; sendo utilizado por outro usu&aacute;rio.");

		}

	}

} catch (PDOException $e) {

    echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}