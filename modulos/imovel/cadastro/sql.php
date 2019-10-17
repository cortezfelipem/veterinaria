<?php
$link = "inicio.php?&t=" . 55 . "&time=" . time();
$CAMINHODOSISTEMA = "http://www.augustoiimoveis.com.br/www/sistema/";
$CAMINHODOSISTEMA .= $link;

try {
	$valorDecimal = str_replace('.' ,'',$_POST['valor'] );
	$valorDecimal = str_replace(',', '.',$valorDecimal);
// $areaedicula = $_POST['area_edicula'];
	if(empty($id)) {
		$stCadastro = Conexao::chamar()->prepare("INSERT INTO imovel 
		                                                  SET anot_adm = :anot_adm,
														  	  id_negocio = :id_negocio,
															  id_tipo = :id_tipo,
															  apto_andar = :apto_andar,
															  num_apto = :num_apto,
															  num_garagem = :num_garagem,
															  valor = :valor,
															  num_suites = :num_suites,
															  num_quartos = :num_quartos,
															  edificio = :edificio,
															  artigo = :artigo,
															  id_municipio = :id_municipio,
															  bairro = :bairro,
															  logradouro = :logradouro,
															  numero = :numero,
															  cep = :cep,
															  complemento = :complemento,
															  area = :area,
															  area_construida = :area_construida,
															  area_terreno = :area_terreno,
															  area_edicula = :area_edicula,
															  data_atualiza = :data_atualiza,
															  comodos = :comodos,
															  observacao = :observacao,
															  mais_info = :mais_info,
															  mostrar_site 	= :mostrar_site,
															  destaque 	= :destaque,
															  lancamento = :lancamento,
															  caracteristicas 	= :caracteristicas");
		
		$stCadastro->bindValue("anot_adm", $_POST['anot_adm'], PDO::PARAM_STR);
		$stCadastro->bindValue("id_negocio", $_POST['id_negocio'], PDO::PARAM_INT);
		$stCadastro->bindValue("id_tipo", $_POST['id_tipo'], PDO::PARAM_INT);
		$stCadastro->bindValue("apto_andar", $_POST['apto_andar'], PDO::PARAM_STR);
		$stCadastro->bindValue("num_apto", $_POST['num_apto'], PDO::PARAM_STR);
		$stCadastro->bindValue("edificio", $_POST['edificio'], PDO::PARAM_STR);
		$stCadastro->bindValue("valor", $valorDecimal, PDO::PARAM_INT);
		$stCadastro->bindValue("num_suites", $_POST['num_suites'], PDO::PARAM_INT);
		$stCadastro->bindValue("num_quartos", $_POST['num_quartos'], PDO::PARAM_INT);
		$stCadastro->bindValue("num_garagem", $_POST['num_garagem'], PDO::PARAM_INT);
		$stCadastro->bindValue("artigo", $_POST['artigo'], PDO::PARAM_STR);
		$stCadastro->bindValue("id_municipio", $_POST['id_municipio'], PDO::PARAM_INT);
		$stCadastro->bindValue("bairro", $_POST['bairro'], PDO::PARAM_STR);
		$stCadastro->bindValue("logradouro", $_POST['logradouro'], PDO::PARAM_STR);
		$stCadastro->bindValue("numero", $_POST['numero'], PDO::PARAM_STR);
		$stCadastro->bindValue("cep", $_POST['cep'], PDO::PARAM_INT);
		$stCadastro->bindValue("complemento", $_POST['complemento'], PDO::PARAM_STR);
		$stCadastro->bindValue("area", $_POST['area'], PDO::PARAM_STR);
		$stCadastro->bindValue("area_construida", $_POST['area_construida'], PDO::PARAM_STR);
		$stCadastro->bindValue("area_terreno", $_POST['area_terreno'], PDO::PARAM_STR);
		$stCadastro->bindValue("area_edicula", $_POST['area_edicula'], PDO::PARAM_STR);
		$stCadastro->bindValue("data_atualiza", date('Y-m-d'), PDO::PARAM_STR);
		$stCadastro->bindValue("comodos", $_POST['comodos'], PDO::PARAM_STR);
		$stCadastro->bindValue("observacao", $_POST['observacao'], PDO::PARAM_STR);
		$stCadastro->bindValue("mais_info", $_POST['mais_info'], PDO::PARAM_STR);
		$stCadastro->bindValue("mostrar_site", $_POST['mostrar_site'], PDO::PARAM_STR);
		$stCadastro->bindValue("destaque", $_POST['destaque'], PDO::PARAM_STR);
		$stCadastro->bindValue("lancamento", $_POST['lancamento'], PDO::PARAM_STR);
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

			echo "<script>alerta('".$CAMINHODOSISTEMA."', 'Registro cadastrado com sucesso', 'success');</script>";
			

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {
		// echo $_POST['id_municipio'] . " <------";
		// exit();
		$stCadastro = Conexao::chamar()->prepare("UPDATE imovel 
		                                             SET anot_adm = :anot_adm,
													 	 id_negocio = :id_negocio,
														 id_tipo = :id_tipo,
														 apto_andar = :apto_andar,
														 num_apto = :num_apto,
														 edificio = :edificio,
														 valor = :valor,
														 num_suites = :num_suites,
														 num_quartos = :num_quartos,
														 num_garagem = :num_garagem,
														 artigo = :artigo,
														 id_municipio = :id_municipio,
														 bairro = :bairro,
														 logradouro = :logradouro,
														 numero = :numero,
														 cep = :cep,
														 complemento = :complemento,
														 area = :area,
														 area_terreno = :area_terreno,
														 area_edicula = :area_edicula,
														 area_construida = :area_construida,
														 data_atualiza = :data_atualiza,
														 comodos = :comodos,
														 observacao = :observacao,
														 mais_info = :mais_info,
														 mostrar_site = :mostrar_site,
														 destaque = :destaque,
														 lancamento = :lancamento,
														 caracteristicas = :caracteristicas
												   WHERE id = :id");

		$stCadastro->bindValue("anot_adm", $_POST['anot_adm'], PDO::PARAM_STR);
		$stCadastro->bindValue("id_negocio", $_POST['id_negocio'], PDO::PARAM_INT);
		$stCadastro->bindValue("id_tipo", $_POST['id_tipo'], PDO::PARAM_INT);
		$stCadastro->bindValue("apto_andar", $_POST['apto_andar'], PDO::PARAM_STR);
		$stCadastro->bindValue("num_apto", $_POST['num_apto'], PDO::PARAM_STR);
		$stCadastro->bindValue("edificio", $_POST['edificio'], PDO::PARAM_STR);
		$stCadastro->bindValue("valor", $valorDecimal, PDO::PARAM_INT);
		$stCadastro->bindValue("num_suites", $_POST['num_suites'], PDO::PARAM_INT);
		$stCadastro->bindValue("num_quartos", $_POST['num_quartos'], PDO::PARAM_INT);
		$stCadastro->bindValue("num_garagem", $_POST['num_garagem'], PDO::PARAM_INT);
		$stCadastro->bindValue("artigo", $_POST['artigo'], PDO::PARAM_STR);
		$stCadastro->bindValue("id_municipio", $_POST['id_municipio'], PDO::PARAM_INT);
		$stCadastro->bindValue("bairro", $_POST['bairro'], PDO::PARAM_STR);
		$stCadastro->bindValue("logradouro", $_POST['logradouro'], PDO::PARAM_STR);
		$stCadastro->bindValue("cep", $_POST['cep'], PDO::PARAM_INT);
		$stCadastro->bindValue("numero", $_POST['numero'], PDO::PARAM_STR);
		$stCadastro->bindValue("complemento", $_POST['complemento'], PDO::PARAM_STR);
		$stCadastro->bindValue("area", $_POST['area'], PDO::PARAM_STR);
		$stCadastro->bindValue("area_construida", $_POST['area_construida'], PDO::PARAM_STR);
		$stCadastro->bindValue("area_terreno", $_POST['area_terreno'], PDO::PARAM_STR);
		$stCadastro->bindValue("area_edicula", $_POST['area_edicula'], PDO::PARAM_STR);
		$stCadastro->bindValue("data_atualiza", date('Y-m-d'), PDO::PARAM_STR);
		$stCadastro->bindValue("comodos", $_POST['comodos'], PDO::PARAM_STR);
		$stCadastro->bindValue("observacao", $_POST['observacao'], PDO::PARAM_STR);
		$stCadastro->bindValue("mais_info", $_POST['mais_info'], PDO::PARAM_STR);
		$stCadastro->bindValue("mostrar_site", $_POST['mostrar_site'], PDO::PARAM_STR);
		$stCadastro->bindValue("destaque", $_POST['destaque'], PDO::PARAM_STR);
		$stCadastro->bindValue("lancamento", $_POST['lancamento'], PDO::PARAM_STR);
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

			// echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");
						echo "<script>alerta('".$CAMINHODOSISTEMA."', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

		}

	}

} catch (PDOException $e) {

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}