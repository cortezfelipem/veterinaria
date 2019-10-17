<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try {
	
	if(empty($id)) {
echo $_POST['bairro']."aqui";
		$stCadastro = Conexao::chamar()->prepare("INSERT INTO imovel 
		                                                  SET anot_adm = :anot_adm,
														  	  id_negocio = :id_negocio,
															  id_tipo = :id_tipo,
															  valor = :valor,
															  num_suites = :num_suites,
															  num_quartos = :num_quartos,
															  artigo = :artigo,
															  id_municipio = :id_municipio,
															  bairro = :bairro,
															  logradouro = :logradouro,
															  numero = :numero,
															  cep = :cep,
															  complemento = :complemento,
															  area = :area,
															  observacao = :observacao,
															  mais_info = :mais_info,
															  mostrar_site 	= :mostrar_site,
															  caracteristicas 	= :caracteristicas");
		
		$stCadastro->bindValue("anot_adm", $_POST['anot_adm'], PDO::PARAM_STR);
		$stCadastro->bindValue("id_negocio", $_POST['id_negocio'], PDO::PARAM_INT);
		$stCadastro->bindValue("id_tipo", $_POST['id_tipo'], PDO::PARAM_INT);
		$stCadastro->bindValue("valor", $_POST['valor'], PDO::PARAM_INT);
		$stCadastro->bindValue("num_suites", $_POST['num_suites'], PDO::PARAM_INT);
		$stCadastro->bindValue("num_quartos", $_POST['num_quartos'], PDO::PARAM_INT);
		$stCadastro->bindValue("artigo", $_POST['artigo'], PDO::PARAM_STR);
		$stCadastro->bindValue("id_municipio", $_POST['id_municipio'], PDO::PARAM_INT);
		$stCadastro->bindValue("bairro", $_POST['bairro'], PDO::PARAM_STR);
		$stCadastro->bindValue("logradouro", $_POST['logradouro'], PDO::PARAM_STR);
		$stCadastro->bindValue("numero", $_POST['numero'], PDO::PARAM_STR);
		$stCadastro->bindValue("cep", $_POST['cep'], PDO::PARAM_INT);
		$stCadastro->bindValue("complemento", $_POST['complemento'], PDO::PARAM_STR);
		$stCadastro->bindValue("area", $_POST['area'], PDO::PARAM_STR);
		$stCadastro->bindValue("observacao", $_POST['observacao'], PDO::PARAM_STR);
		$stCadastro->bindValue("mais_info", $_POST['mais_info'], PDO::PARAM_STR);
		$stCadastro->bindValue("mostrar_site", $_POST['mostrar_site'], PDO::PARAM_STR);
		$stCadastro->bindValue("caracteristicas", $_POST['caracteristicas'], PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();
		
		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();

			$upFoto = true;
			$upAnexo = false;
			$upVideo = false;
			$upLink = false;
			$upTabela = "imovel";
			$upRel = "id_imovel";

			include_once "$root/privado/sistema/classes/includes/upload_cadastrar.php";

			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {

		$stCadastro = Conexao::chamar()->prepare("UPDATE imovel 
		                                             SET anot_adm = :anot_adm,
													 	 id_negocio = :id_negocio,
														 id_tipo = :id_tipo,
														 valor = :valor,
														 num_suites = :num_suites,
														 num_quartos = :num_quartos,
														 artigo = :artigo,
														 id_municipio = :id_municipio,
														 bairro = :bairro,
														 logradouro = :logradouro,
														 numero = :numero,
														 cep = :cep,
														 complemento = :complemento,
														 area = :area,
														 observacao = :observacao,
														 mais_info = :mais_info,
														 mostrar_site = :mostrar_site,
														 caracteristicas = :caracteristicas
												   WHERE id = :id");

		$stCadastro->bindValue("anot_adm", $_POST['anot_adm'], PDO::PARAM_STR);
		$stCadastro->bindValue("id_negocio", $_POST['id_negocio'], PDO::PARAM_INT);
		$stCadastro->bindValue("id_tipo", $_POST['id_tipo'], PDO::PARAM_INT);
		$stCadastro->bindValue("valor", $_POST['valor'], PDO::PARAM_INT);
		$stCadastro->bindValue("num_suites", $_POST['num_suites'], PDO::PARAM_INT);
		$stCadastro->bindValue("num_quartos", $_POST['num_quartos'], PDO::PARAM_INT);
		$stCadastro->bindValue("artigo", $_POST['artigo'], PDO::PARAM_STR);
		$stCadastro->bindValue("id_municipio", $_POST['id_municipio'], PDO::PARAM_INT);
		$stCadastro->bindValue("bairro", $_POST['bairro'], PDO::PARAM_STR);
		$stCadastro->bindValue("logradouro", $_POST['logradouro'], PDO::PARAM_STR);
		$stCadastro->bindValue("cep", $_POST['cep'], PDO::PARAM_INT);
		$stCadastro->bindValue("numero", $_POST['numero'], PDO::PARAM_STR);
		$stCadastro->bindValue("complemento", $_POST['complemento'], PDO::PARAM_STR);
		$stCadastro->bindValue("area", $_POST['area'], PDO::PARAM_STR);
		$stCadastro->bindValue("observacao", $_POST['observacao'], PDO::PARAM_STR);
		$stCadastro->bindValue("mais_info", $_POST['mais_info'], PDO::PARAM_STR);
		$stCadastro->bindValue("mostrar_site", $_POST['mostrar_site'], PDO::PARAM_STR);
		$stCadastro->bindValue("caracteristicas", $_POST['caracteristicas'], PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();
	
		if($cadastro) {

			$upFoto = true;
			$upAnexo = false;
			$upVideo = false;
			$upLink = false;
			$upTabela = "imovel";
			$upRel = "id_imovel";

			include_once "$root/privado/sistema/classes/includes/upload_alterar.php";

			// logAcesso("A", $tela, $id);

			echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

		}

	}

} catch (PDOException $e) {

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}