<?php




//print_r($_POST);

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

try {
		if(empty($id)) {

			$tag_pesquisa = $produto_pai_titulo;
			$tag_pesquisa .= "_".$titulo;
			if(!empty($atributo))$tag_pesquisa .= "_".$atributo;
			if(!empty($atributo2))$tag_pesquisa .= "_".$atributo2;
			if(!empty($atributo3))$tag_pesquisa .= "_".$atributo3;


			$stAtributo = Conexao::chamar()->prepare("
													   SELECT *
													     FROM produto_filho
													   	WHERE produto_filho.id_produto_pai  = :id_produto_pai
													   	  AND produto_filho.id_atributo_1   = :id_atributo_1
													   	  AND produto_filho.id_atributo_2   = :id_atributo_2
													   	  AND produto_filho.id_atributo_3   = :id_atributo_3
													   	  AND produto_filho.status_registro = :status_registro

													 ");
			$stAtributo->bindValue("id_produto_pai", $id_produto_pai, PDO::PARAM_INT);
			$stAtributo->bindValue("id_atributo_1", $id_atributo_1, PDO::PARAM_INT);
			$stAtributo->bindValue("id_atributo_2", $id_atributo_2, PDO::PARAM_INT);
			$stAtributo->bindValue("id_atributo_3", $id_atributo_3, PDO::PARAM_INT);
			$stAtributo->bindValue("status_registro", "A", PDO::PARAM_STR);
			$stAtributo->execute();
			$lista_produto_atributo = $stAtributo->fetchAll(PDO::FETCH_ASSOC);


			

			if(count($lista_produto_atributo) == 0 ){

				$stCadastro = Conexao::chamar()->prepare("INSERT produto_filho
			                                             SET 
			                                             	 id_produto_pai = :id_produto_pai,
			                                             	 id_atributo_1  = :id_atributo_1,
														  	 id_atributo_2  = :id_atributo_2,
														  	 id_atributo_3  = :id_atributo_3,
														  	 		 titulo = :titulo,
														  	         artigo = :artigo,
														  	          preco = :preco,
														  	       preco_de = :preco_de,
														  	   data_inicial = :data_inicial,
		                                                  	   hora_inicial = :hora_inicial,
		                                                  	    data_limite = :data_limite,
		                                                  	    hora_limite = :hora_limite,
														  	           peso = :peso,
														  	        largura = :largura,
														  	         altura = :altura,
														  	    comprimento = :comprimento,
														  	       diametro = :diametro,
														  	        estoque = :estoque,
														  	        parcela = :parcela,
														  	       destaque = :destaque,
														  	   tag_pesquisa = :tag_pesquisa
														  	   ");


				$stCadastro->bindValue("id_produto_pai", $id_produto_pai, PDO::PARAM_INT);
				$stCadastro->bindValue("id_atributo_1", empty($id_atributo_1) ? 1 : $id_atributo_1, PDO::PARAM_INT);
				$stCadastro->bindValue("id_atributo_2", empty($id_atributo_2) ? 1 : $id_atributo_2, PDO::PARAM_INT);
				$stCadastro->bindValue("id_atributo_3", empty($id_atributo_3) ? 1 : $id_atributo_3, PDO::PARAM_INT);
				$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
				$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
				$stCadastro->bindValue("preco", formata_valor_banco($preco), PDO::PARAM_STR);
				$stCadastro->bindValue("preco_de", formata_valor_banco($preco_de), PDO::PARAM_STR);
				$stCadastro->bindValue("data_inicial", formata_data_banco($data_inicial), PDO::PARAM_STR);
				$stCadastro->bindValue("hora_inicial", $hora_inicial, PDO::PARAM_STR);
				$stCadastro->bindValue("data_limite", formata_data_banco($data_limite), PDO::PARAM_STR);
				$stCadastro->bindValue("hora_limite", $hora_limite, PDO::PARAM_STR);
				$stCadastro->bindValue("peso", formata_valor_banco($peso), PDO::PARAM_STR);
				$stCadastro->bindValue("largura", $largura, PDO::PARAM_INT);
				$stCadastro->bindValue("altura", $altura, PDO::PARAM_INT);
				$stCadastro->bindValue("comprimento", $comprimento, PDO::PARAM_INT);
				$stCadastro->bindValue("diametro", $diametro, PDO::PARAM_INT);
				$stCadastro->bindValue("estoque", $estoque, PDO::PARAM_INT);
				$stCadastro->bindValue("parcela", $parcela, PDO::PARAM_STR);
				$stCadastro->bindValue("destaque", (empty($destaque) ? "N" : "S"), PDO::PARAM_STR);
				$stCadastro->bindValue("tag_pesquisa", $tag_pesquisa, PDO::PARAM_STR);
				

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

				$html = '';

				foreach ($lista_produto_atributo as $chave => $item) {
				 $html .= "<a href='".$caminhoTela."&id=".$item['id']."&s=cadastro' class='btn btn-sm btn-default' style='margin-left:30px' title='".$item['titulo']."'>";
				 $html .=  "<i class='fa fa-search'></i> Visualizar" ;
				 $html .= "</a>";
				}

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> Exite produto cadastrado com esses atributos ".$html);
			}



		} else {


			$tag_pesquisa = $produto_pai_titulo;
			$tag_pesquisa .= "_".$titulo;
			if(!empty($atributo))$tag_pesquisa .= "_".$atributo;
			if(!empty($atributo2))$tag_pesquisa .= "_".$atributo2;
			if(!empty($atributo3))$tag_pesquisa .= "_".$atributo3;


			$stAtributo = Conexao::chamar()->prepare("
													   SELECT *
													     FROM produto_filho
													   	WHERE produto_filho.id_produto_pai  = :id_produto_pai
													   	  AND produto_filho.id_atributo_1   = :id_atributo_1
													   	  AND produto_filho.id_atributo_2   = :id_atributo_2
													   	  AND produto_filho.id_atributo_3   = :id_atributo_3
													   	  AND produto_filho.status_registro = :status_registro
													   	  AND produto_filho.id != :id

													 ");

			$stAtributo->bindValue("id", $id, PDO::PARAM_INT);
			$stAtributo->bindValue("id_produto_pai", $id_produto_pai, PDO::PARAM_INT);
			$stAtributo->bindValue("id_atributo_1", $id_atributo_1, PDO::PARAM_INT);
			$stAtributo->bindValue("id_atributo_2", $id_atributo_2, PDO::PARAM_INT);
			$stAtributo->bindValue("id_atributo_3", $id_atributo_3, PDO::PARAM_INT);
			$stAtributo->bindValue("status_registro", "A", PDO::PARAM_STR);
			$stAtributo->execute();
			$lista_produto_atributo = $stAtributo->fetchAll(PDO::FETCH_ASSOC);


			if(count($lista_produto_atributo) == 0 ){


				$stCadastro = Conexao::chamar()->prepare("UPDATE produto_filho
			                                             SET 
			                                             	 id_produto_pai = :id_produto_pai,
			                                             	 id_atributo_1  = :id_atributo_1,
														  	 id_atributo_2  = :id_atributo_2,
														  	 id_atributo_3  = :id_atributo_3,
														  	 		 titulo = :titulo,
														  	         artigo = :artigo,
														  	          preco = :preco,
														  	       preco_de = :preco_de,
														  	   data_inicial = :data_inicial,
		                                                  	   hora_inicial = :hora_inicial,
		                                                  	    data_limite = :data_limite,
		                                                  	    hora_limite = :hora_limite,
														  	           peso = :peso,
														  	        largura = :largura,
														  	         altura = :altura,
														  	    comprimento = :comprimento,
														  	       diametro = :diametro,
														  	        estoque = :estoque,
														  	        parcela = :parcela,
														  	       destaque = :destaque,
														  	   tag_pesquisa = :tag_pesquisa

													         WHERE id = :id");


				$stCadastro->bindValue("id_produto_pai", $id_produto_pai, PDO::PARAM_INT);
				$stCadastro->bindValue("id_atributo_1", empty($id_atributo_1) ? 1 : $id_atributo_1, PDO::PARAM_INT);
				$stCadastro->bindValue("id_atributo_2", empty($id_atributo_2) ? 1 : $id_atributo_2, PDO::PARAM_INT);
				$stCadastro->bindValue("id_atributo_3", empty($id_atributo_3) ? 1 : $id_atributo_3, PDO::PARAM_INT);
				$stCadastro->bindValue("titulo", $titulo, PDO::PARAM_STR);
				$stCadastro->bindValue("artigo", $artigo, PDO::PARAM_STR);
				$stCadastro->bindValue("preco", formata_valor_banco($preco), PDO::PARAM_STR);
				$stCadastro->bindValue("preco_de", formata_valor_banco($preco_de), PDO::PARAM_STR);
				$stCadastro->bindValue("data_inicial", formata_data_banco($data_inicial), PDO::PARAM_STR);
				$stCadastro->bindValue("hora_inicial", $hora_inicial, PDO::PARAM_STR);
				$stCadastro->bindValue("data_limite", formata_data_banco($data_limite), PDO::PARAM_STR);
				$stCadastro->bindValue("hora_limite", $hora_limite, PDO::PARAM_STR);
				$stCadastro->bindValue("peso", formata_valor_banco($peso), PDO::PARAM_STR);
				$stCadastro->bindValue("largura", $largura, PDO::PARAM_INT);
				$stCadastro->bindValue("altura", $altura, PDO::PARAM_INT);
				$stCadastro->bindValue("comprimento", $comprimento, PDO::PARAM_INT);
				$stCadastro->bindValue("diametro", $diametro, PDO::PARAM_INT);
				$stCadastro->bindValue("estoque", $estoque, PDO::PARAM_INT);
				$stCadastro->bindValue("parcela", $parcela, PDO::PARAM_STR);
				$stCadastro->bindValue("destaque", (empty($destaque) ? "N" : "S"), PDO::PARAM_STR);
				$stCadastro->bindValue("tag_pesquisa", $tag_pesquisa, PDO::PARAM_STR);
				$stCadastro->bindValue("id", $id, PDO::PARAM_INT);

				$cadastro = $stCadastro->execute();

				if($cadastro) {

				
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

			} else {

				$html = '';

				foreach ($lista_produto_atributo as $chave => $item) {
				 $html .= "<a href='".$caminhoTela."&id=".$item['id']."&s=cadastro' class='btn btn-sm btn-default' style='margin-left:30px' title='".$item['titulo']."'>";
				 $html .=  "<i class='fa fa-search'></i> Visualizar" ;
				 $html .= "</a>";
				}

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> Exite produto cadastrado com esses atributos ".$html);

			}
		
		}
	
} catch (PDOException $e) {

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}


