<?php
// header('Content-Type: text/html; charset=utf-8');

//Foto -----------------------------------------------------------------
if($upFoto) {

	foreach($_REQUEST['legenda_alterar'] as $indice => $legendaAlterar) {
		
		if(!empty($_REQUEST['legenda_alterar'])){

			$stImagem = Conexao::chamar()->prepare("UPDATE ".$upTabela."_foto SET credito = :credito,
																				  legenda = :legenda
																			WHERE id = :id");

			$stImagem->bindParam("credito", $_REQUEST['credito_alterar'][$indice], PDO::PARAM_STR);
			$stImagem->bindParam("legenda", $legendaAlterar, PDO::PARAM_STR);
			$stImagem->bindParam("id", $_REQUEST['id_foto'][$indice], PDO::PARAM_INT);

			$stImagem->execute();

		}
	}

	if(array_key_exists('id_foto', $_POST)) {
		$ids = $_POST['id_foto'];
		$idList = implode(',', $ids);
	} else {
		$idList = '';
	}

	$stExcluiFoto = Conexao::chamar()->prepare("DELETE FROM ".$upTabela."_foto WHERE $upRel = :id AND NOT FIND_IN_SET(id, :ids)");
	$stExcluiFoto->bindParam("id", $id, PDO::PARAM_INT);
	$stExcluiFoto->bindParam("ids", $idList, PDO::PARAM_STR);
	$stExcluiFoto->execute();

	$up = new Uploader('imagem');
	$up->setDirectory("$caminhoUploadImagem/");
	$imagem = $up->uploadImage();

	if(!empty($imagem)) {
		
		foreach($imagem as $indice => $arquivoFoto) {

			$stImagem = Conexao::chamar()->prepare("INSERT INTO ".$upTabela."_foto SET $upRel = :id,
																					   credito = :credito,
																					   legenda = :legenda,
																					   foto = :foto");

			$stImagem->bindParam("id", $id, PDO::PARAM_INT);
			$stImagem->bindParam("credito", $_REQUEST['credito'][$indice], PDO::PARAM_STR);
			$stImagem->bindParam("legenda", $_REQUEST['legenda'][$indice], PDO::PARAM_STR);
			$stImagem->bindParam("foto", $arquivoFoto, PDO::PARAM_STR);

			$stImagem->execute();

		}
	}

}

//Anexo -----------------------------------------------------------------
if($upAnexo) {

	$colunaData = Conexao::chamar()->query("SHOW COLUMNS FROM ".$upTabela."_anexo WHERE Field = 'data_insercao'")->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($_REQUEST['descricao_alterar_anexo'])){
		
		foreach($_REQUEST['descricao_alterar_anexo'] as $indice => $descricaoAlterar) {

			if(count($colunaData)) {

				$stAnexo = Conexao::chamar()->prepare("UPDATE ".$upTabela."_anexo SET descricao = :descricao,
																					  data_modificacao = NOW()
																				WHERE id = :id");

			} else {

				$stAnexo = Conexao::chamar()->prepare("UPDATE ".$upTabela."_anexo SET descricao = :descricao
																				WHERE id = :id");

			}

			$stAnexo->bindParam("descricao", $descricaoAlterar, PDO::PARAM_STR);
			$stAnexo->bindParam("id", $_REQUEST['id_anexo'][$indice], PDO::PARAM_INT);

			$stAnexo->execute();

		}
	}

	if(array_key_exists('id_anexo', $_POST)) {
		$ids2 = $_POST['id_anexo'];
		$idList2 = implode(',', $ids2);
	} else {
		$idList2 = '';
	}

	$stExcluiAnexo = Conexao::chamar()->prepare("DELETE FROM ".$upTabela."_anexo WHERE $upRel = :id AND NOT FIND_IN_SET(id, :ids)");
	$stExcluiAnexo->bindParam("id", $id, PDO::PARAM_INT);
	$stExcluiAnexo->bindParam("ids", $idList2, PDO::PARAM_STR);
	$stExcluiAnexo->execute();

	$up = new Uploader('anexo');
	$up->setDirectory("$caminhoUploadArquivo/");
	$anexo = $up->uploadFile();

	if(!empty($anexo)) {
		foreach($anexo as $indice => $arquivoAnexo) {

			if(count($colunaData)) {

				$stAnexo = Conexao::chamar()->prepare("INSERT INTO ".$upTabela."_anexo SET $upRel = :id,
																					   descricao = :descricao,
																					   arquivo = :arquivo,
																					   data_insercao = NOW(),
																					   data_modificacao = NOW()");

			} else {

				$stAnexo = Conexao::chamar()->prepare("INSERT INTO ".$upTabela."_anexo SET $upRel = :id,
																					   descricao = :descricao,
																					   arquivo = :arquivo");

			}

			$stAnexo->bindParam("id", $id, PDO::PARAM_INT);
			$stAnexo->bindParam("descricao", $_REQUEST['descricao_anexo'][$indice], PDO::PARAM_STR);
			$stAnexo->bindParam("arquivo", $arquivoAnexo, PDO::PARAM_STR);

			$stAnexo->execute();

		}
	}

}

//Video -----------------------------------------------------------------
if($upVideo) {
	if(!empty($_REQUEST['titulo_video_alterar'])){
		
		foreach($_REQUEST['titulo_video_alterar'] as $indice => $tituloVideoAlterar) {

			$stVideo = Conexao::chamar()->prepare("UPDATE ".$upTabela."_video SET titulo = :titulo,
																				  link = :link
																			WHERE id = :id");

			$stVideo->bindParam("titulo", $tituloVideoAlterar, PDO::PARAM_STR);
			$stVideo->bindParam("link", $_REQUEST['link_video_alterar'][$indice], PDO::PARAM_STR);
			$stVideo->bindParam("id", $_REQUEST['id_video'][$indice], PDO::PARAM_INT);

			$stVideo->execute();

		}
	}

	if(array_key_exists('id_video', $_POST)) {
		$ids3 = $_POST['id_video'];
		$idList3 = implode(',', $ids3);
	} else {
		$idList3 = '';
	}

	$stExcluiVideo = Conexao::chamar()->prepare("DELETE FROM ".$upTabela."_video WHERE $upRel = :id AND NOT FIND_IN_SET(id, :ids)");
	$stExcluiVideo->bindParam("id", $id, PDO::PARAM_INT);
	$stExcluiVideo->bindParam("ids", $idList3, PDO::PARAM_STR);
	$stExcluiVideo->execute();

	if(!empty($_REQUEST['titulo_video'])) {
		foreach($_REQUEST['titulo_video'] as $indice => $tituloVideo) {

			$stVideo = Conexao::chamar()->prepare("INSERT INTO ".$upTabela."_video SET $upRel = :id,
																					   titulo = :titulo,
																					   link = :link");

			$stVideo->bindParam("id", $id, PDO::PARAM_INT);
			$stVideo->bindParam("titulo", $tituloVideo, PDO::PARAM_STR);
			$stVideo->bindParam("link", utf8_decode($_REQUEST['link_video'][$indice]), PDO::PARAM_STR);

			$stVideo->execute();

		}
	}
}

//Link -----------------------------------------------------------------
if($upLink) {

	$colunaData = Conexao::chamar()->query("SHOW COLUMNS FROM ".$upTabela."_link WHERE Field = 'data_insercao'")->fetchAll(PDO::FETCH_ASSOC);

	if(!empty($_REQUEST['descricao_link_alterar'])){
		foreach($_REQUEST['descricao_link_alterar'] as $indice => $descricaoLinkAlterar) {

			if(count($colunaData)) {

				$stLink = Conexao::chamar()->prepare("UPDATE ".$upTabela."_link SET descricao = :descricao,
																					link = :link,
																					data_modificacao = NOW()
																			  WHERE id = :id");

			} else {

				$stLink = Conexao::chamar()->prepare("UPDATE ".$upTabela."_link SET descricao = :descricao,
																					link = :link
																			  WHERE id = :id");

			}

			$stLink->bindParam("descricao", $descricaoLinkAlterar, PDO::PARAM_STR);
			$stLink->bindParam("link", $_REQUEST['link_link_alterar'][$indice], PDO::PARAM_STR);
			$stLink->bindParam("id", $_REQUEST['id_link'][$indice], PDO::PARAM_INT);

			$stLink->execute();

		}
	}

	if(array_key_exists('id_link', $_POST)) {
		$ids4 = $_POST['id_link'];
		$idList4 = implode(',', $ids4);
	} else {
		$idList4 = '';
	}

	$stExcluiLink = Conexao::chamar()->prepare("DELETE FROM ".$upTabela."_link WHERE $upRel = :id AND NOT FIND_IN_SET(id, :ids)");
	$stExcluiLink->bindParam("id", $id, PDO::PARAM_INT);
	$stExcluiLink->bindParam("ids", $idList4, PDO::PARAM_STR);
	$stExcluiLink->execute();

	if(!empty($_REQUEST['descricao_link'])) {
		foreach($_REQUEST['descricao_link'] as $indice => $descricaoLink) {

			if(count($colunaData)) {

				$stLink = Conexao::chamar()->prepare("INSERT INTO ".$upTabela."_link SET $upRel = :id,
																					  	 descricao = :descricao,
																						 link = :link,
																						 data_insercao = NOW(),
																						 data_modificacao = NOW()");

			} else {

				$stLink = Conexao::chamar()->prepare("INSERT INTO ".$upTabela."_link SET $upRel = :id,
																					  	 descricao = :descricao,
																					  	 link = :link");

			}

			$stLink->bindParam("id", $id, PDO::PARAM_INT);
			$stLink->bindParam("descricao", $descricaoLink, PDO::PARAM_STR);
			$stLink->bindParam("link", utf8_decode($_REQUEST['link_link'][$indice]), PDO::PARAM_STR);

			$stLink->execute();
		}
	}
}