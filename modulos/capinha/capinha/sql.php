<?php

	try {

		if(empty($id)) {

			$stCadastro = Conexao::chamar()->prepare("INSERT INTO capinha 
															  SET id_marca = :id_marca,
																  id_modelo = :id_modelo,
																  descricao = :descricao,
																  data_cadastro = :data_cadastro,
																  valor = :valor,
																  valor_promocional = :valor_promocional,
																  promocao_inicial = :promocao_inicial,
																  promocao_final = :promocao_final");

			$stCadastro->bindValue("id_marca", $id_marca, PDO::PARAM_INT);
			$stCadastro->bindValue("id_modelo", $id_modelo, PDO::PARAM_INT);
			$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);
			$stCadastro->bindValue("data_cadastro", formata_data_banco($data_cadastro), PDO::PARAM_STR);
			$stCadastro->bindValue("valor", formata_valor_banco($valor), PDO::PARAM_STR);
			$stCadastro->bindValue("valor_promocional", formata_valor_banco($valor_promocional), PDO::PARAM_STR);
			$stCadastro->bindValue("promocao_inicial", formata_data_banco($promocao_inicial), PDO::PARAM_STR);
			$stCadastro->bindValue("promocao_final", formata_data_banco($promocao_final), PDO::PARAM_STR);

			$cadastro = $stCadastro->execute();

			if($cadastro) {

				$id = Conexao::chamar()->lastInsertId();

				$upFoto = true;
				$upAnexo = false;
				$upVideo = false;
				$upLink = false;
				$upTabela = "capinha";
				$upRel = "id_capinha";

				include_once "$root/privado/sistema/classes/includes/upload_cadastrar.php";

				logAcesso("I", $tela, $id);

				echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

			}

		} else {

			$stCadastro = Conexao::chamar()->prepare("UPDATE capinha 
														 SET id_marca = :id_marca,
															 id_modelo = :id_modelo,
															 descricao = :descricao,
															 data_cadastro = :data_cadastro,
															 valor = :valor,
															 valor_promocional = :valor_promocional,
															 promocao_inicial = :promocao_inicial,
															 promocao_final = :promocao_final
													   WHERE id = :id");

			$stCadastro->bindValue("id_marca", $id_marca, PDO::PARAM_INT);
			$stCadastro->bindValue("id_modelo", $id_modelo, PDO::PARAM_INT);
			$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);
			$stCadastro->bindValue("data_cadastro", formata_data_banco($data_cadastro), PDO::PARAM_STR);
			$stCadastro->bindValue("valor", formata_valor_banco($valor), PDO::PARAM_STR);
			$stCadastro->bindValue("valor_promocional", formata_valor_banco($valor_promocional), PDO::PARAM_STR);
			$stCadastro->bindValue("promocao_inicial", formata_data_banco($promocao_inicial), PDO::PARAM_STR);
			$stCadastro->bindValue("promocao_final", formata_data_banco($promocao_final), PDO::PARAM_STR);
			$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
			$cadastro = $stCadastro->execute();

			if($cadastro) {

				// if($estoque > 0) {
				// 	$stArtigo = Conexao::chamar()->prepare("SELECT *
				// 									FROM produto_aviso_email
				// 									WHERE id_produto = :id
				// 									AND produto_aviso_email.status = :status");

				// 	$stArtigo->execute(array("id" => $id, "status" => "P"));
				// 	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);
					
				// 	foreach ($buscaArtigo as $aviso){

				// 	$html .= "<p><strong>$aviso[nome]</strong>, ao visitar nosso site na data <strong>".formata_data_hora($aviso['data'])."</strong>, voc&ecirc; cadastrou seu e-mail para ser avisado quando o produto <strong>$descricao</strong> voltasse ao estoque de nossa loja virtual. Agora voc&ecirc; j&aacute; pode finalizar sua compra pois ele est&aacute; dispon&iacute;vel. Clique no link abaixo para acessar a p&aacute;gina do produto:</p>";
				// 		$html .= "<p><a href='$CAMINHO/produtos/$id'><strong>$descricao</strong></a></p>";
				// 		$html .= "<p>&nbsp;</p><p>Atenciosamente,</p><p>Ing&aacute; Comercial</p>";

				// 		$headers  = "MIME-Version: 1.1\n";
				// 		$headers .= "Return-Path: Inga Comercial <$geral[email_contato]> \n";
				// 		$headers .= "From: Inga Comercial <$geral[email_contato]> \n";
				// 		$headers .= "Sender: Inga Comercial <$geral[email_contato]> \n";
				// 		$headers .= "Reply-To: Inga Comercial <$geral[email_contato]> \n";
				// 		$headers .= "Organization: Inga Comercial \n";
				// 		$headers .= "Content-Type: text/html; charset=ISO-8859-1 \n";

				// 		$vai = mail($aviso['email'], "$aviso[nome], o produto ".html_entity_decode($descricao)." ja esta disponivel - Inga Comercial", $html, $headers);

				// 		if($vai) {
				// 			$stCadastro = Conexao::chamar()->prepare("UPDATE produto_aviso_email 
				// 														SET status = :status
				// 													WHERE id = :id");

				// 			$stCadastro->bindValue("status", 'E', PDO::PARAM_STR);
				// 			$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
				// 			$cadastro = $stCadastro->execute();
				// 		}
				// 	}
				// }

				$upFoto = true;
				$upAnexo = false;
				$upVideo = false;
				$upLink = false;
				$upTabela = "capinha";
				$upRel = "id_capinha";

				include_once "$root/privado/sistema/classes/includes/upload_alterar.php";

				logAcesso("A", $tela, $id);

				echo "<script>alerta('$caminhoTela', 'Registro alterado com sucesso', 'success');</script>";

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

			}

		}

	} catch (PDOException $e) {

		echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

	}