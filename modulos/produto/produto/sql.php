<?php

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

try {

	if(empty($id)) {

		$stCadastro = Conexao::chamar()->prepare("INSERT INTO produto 
		                                                  SET id_categoria = :id_categoria,
		                                                      id_marca = :id_marca,
		                                                      id_subcategoria = :id_subcategoria,
		                                                      titulo = :titulo,
		                                                      artigo = :artigo,
		                                                      preco = :preco,
		                                                      preco_de = :preco_de,
		                                                      peso = :peso,
		                                                      largura = :largura,
		                                                      altura = :altura,
		                                                      comprimento = :comprimento,
		                                                      diametro = :diametro,
		                                                      estoque = :estoque,
		                                                      parcela = :parcela,
		                                                      destaque = :destaque,
		                                                      promocao = :promocao");

		$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
		$stCadastro->bindValue("id_marca", $id_marca, PDO::PARAM_INT);
		$stCadastro->bindValue("id_subcategoria", empty($id_subcategoria) ? NULL : $id_subcategoria, PDO::PARAM_INT);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$stCadastro->bindValue("preco", formata_valor_banco($preco), PDO::PARAM_STR);
		$stCadastro->bindValue("preco_de", formata_valor_banco($preco_de), PDO::PARAM_STR);
		$stCadastro->bindValue("peso", formata_valor_banco($peso), PDO::PARAM_STR);
		$stCadastro->bindValue("largura", $largura, PDO::PARAM_INT);
		$stCadastro->bindValue("altura", $altura, PDO::PARAM_INT);
		$stCadastro->bindValue("comprimento", $comprimento, PDO::PARAM_INT);
		$stCadastro->bindValue("diametro", $diametro, PDO::PARAM_INT);
		$stCadastro->bindValue("estoque", $estoque, PDO::PARAM_INT);
		$stCadastro->bindValue("parcela", $parcela, PDO::PARAM_STR);
		$stCadastro->bindValue("destaque", (empty($destaque) ? "N" : "S"), PDO::PARAM_STR);
		$stCadastro->bindValue("promocao", (empty($promocao) ? "N" : "S"), PDO::PARAM_STR);

		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();

			$upFoto = true;
			$upAnexo = true;
			$upVideo = true;
			$upLink = false;
			$upTabela = "produto";
			$upRel = "id_artigo";

			include_once "$root/privado/sistema/classes/includes/upload_cadastrar.php";

			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stCadastro = Conexao::chamar()->prepare("UPDATE produto 
		                                             SET id_categoria = :id_categoria,
													  	 id_subcategoria = :id_subcategoria,
													  	 id_marca = :id_marca,
													  	 titulo = :titulo,
													  	 artigo = :artigo,
													  	 preco = :preco,
													  	 preco_de = :preco_de,
													  	 peso = :peso,
													  	 largura = :largura,
													  	 altura = :altura,
													  	 comprimento = :comprimento,
													  	 diametro = :diametro,
													  	 estoque = :estoque,
													  	 parcela = :parcela,
													  	 destaque = :destaque,
													  	 promocao = :promocao
												   WHERE id = :id");

		$stCadastro->bindValue("id_categoria", $id_categoria, PDO::PARAM_INT);
		$stCadastro->bindValue("id_marca", $id_marca, PDO::PARAM_INT);
		$stCadastro->bindValue("id_subcategoria", empty($id_subcategoria) ? NULL : $id_subcategoria, PDO::PARAM_INT);
		$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
		$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
		$stCadastro->bindValue("preco", formata_valor_banco($preco), PDO::PARAM_STR);
		$stCadastro->bindValue("preco_de", formata_valor_banco($preco_de), PDO::PARAM_STR);
		$stCadastro->bindValue("peso", formata_valor_banco($peso), PDO::PARAM_STR);
		$stCadastro->bindValue("largura", $largura, PDO::PARAM_INT);
		$stCadastro->bindValue("altura", $altura, PDO::PARAM_INT);
		$stCadastro->bindValue("comprimento", $comprimento, PDO::PARAM_INT);
		$stCadastro->bindValue("diametro", $diametro, PDO::PARAM_INT);
		$stCadastro->bindValue("estoque", $estoque, PDO::PARAM_INT);
		$stCadastro->bindValue("parcela", $parcela, PDO::PARAM_STR);
		$stCadastro->bindValue("destaque", (empty($destaque) ? "N" : "S"), PDO::PARAM_STR);
		$stCadastro->bindValue("promocao", (empty($promocao) ? "N" : "S"), PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			if($estoque > 0) {
				$stArtigo = Conexao::chamar()->prepare("SELECT *
                                    	          FROM produto_aviso_email
                                    	         WHERE id_produto = :id
    	                                           AND produto_aviso_email.status = :status");

				$stArtigo->execute(array("id" => $id, "status" => "P"));
				$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);
				
				foreach ($buscaArtigo as $aviso){

					$html .= "<p><strong>$aviso[nome]</strong>, ao visitar nosso site na data <strong>".formata_data_hora($aviso['data'])."</strong>, voc&ecirc; cadastrou seu e-mail para ser avisado quando o produto <strong>$titulo</strong> voltasse ao estoque de nossa loja virtual. Agora voc&ecirc; j&aacute; pode finalizar sua compra pois ele est&aacute; dispon&iacute;vel. Clique no link abaixo para acessar a p&aacute;gina do produto:</p>";
					$html .= "<p><a href='$CAMINHO/produtos/$id'><strong>$titulo</strong></a></p>";
					$html .= "<p>&nbsp;</p><p>Atenciosamente,</p><p>Ing&aacute; Comercial</p>";

					$headers  = "MIME-Version: 1.1\n";
					$headers .= "Return-Path: Inga Comercial <$geral[email_contato]> \n";
					$headers .= "From: Inga Comercial <$geral[email_contato]> \n";
					$headers .= "Sender: Inga Comercial <$geral[email_contato]> \n";
					$headers .= "Reply-To: Inga Comercial <$geral[email_contato]> \n";
					$headers .= "Organization: Inga Comercial \n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1 \n";

					$vai = mail($aviso['email'], "$aviso[nome], o produto ".html_entity_decode($titulo)." ja esta disponivel - Inga Comercial", $html, $headers);

					if($vai) {
						$stCadastro = Conexao::chamar()->prepare("UPDATE produto_aviso_email 
		                                            				 SET status = :status
												   				   WHERE id = :id");

						$stCadastro->bindValue("status", 'E', PDO::PARAM_STR);
						$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
						$cadastro = $stCadastro->execute();
					}
				}
			}

			$upFoto = true;
			$upAnexo = true;
			$upVideo = true;
			$upLink = false;
			$upTabela = "produto";
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