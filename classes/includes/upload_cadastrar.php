<?php
//Foto -----------------------------------------------------------------
if($upFoto) {

	$up = new Uploader('imagem');
	$up->setDirectory("$caminhoUploadImagem/");
	$imagem = $up->uploadImage();

	if(!empty($imagem)) {
		foreach($imagem as $indice => $arquivoFoto) {

			$stImagem = Conexao::chamar()->prepare("INSERT INTO ".$upTabela."_foto SET $upRel = :id,
																					   credito = :credito,
																					   legenda = :legenda,
																					   foto = :foto");

			$stImagem->bindValue("id", $id, PDO::PARAM_INT);
			$stImagem->bindValue("credito", utf8_decode($_REQUEST['credito'][$indice]), PDO::PARAM_STR);
			$stImagem->bindValue("legenda", utf8_decode($_REQUEST['legenda'][$indice]), PDO::PARAM_STR);
			$stImagem->bindValue("foto", $arquivoFoto, PDO::PARAM_STR);

			$stImagem->execute();

		}
	}
}

//Anexo -----------------------------------------------------------------
if($upAnexo) {

	$colunaData = Conexao::chamar()->query("SHOW COLUMNS FROM ".$upTabela."_anexo WHERE Field = 'data_insercao'")->fetchAll(PDO::FETCH_ASSOC);

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

			$stAnexo->bindValue("id", $id, PDO::PARAM_INT);
			$stAnexo->bindValue("descricao", utf8_decode($_REQUEST['descricao_anexo'][$indice]), PDO::PARAM_STR);
			$stAnexo->bindValue("arquivo", $arquivoAnexo, PDO::PARAM_STR);

			$stAnexo->execute();

		}
	}
}

//Video -----------------------------------------------------------------
if($upVideo) {

	if(!empty($_REQUEST['titulo_video'])) {
		foreach($_REQUEST['titulo_video'] as $indice => $tituloVideo) {

			$stVideo = Conexao::chamar()->prepare("INSERT INTO ".$upTabela."_video SET $upRel = :id,
																					   titulo = :titulo,
																					   link = :link");

			$stVideo->bindValue("id", $id, PDO::PARAM_INT);
			$stVideo->bindValue("titulo", $tituloVideo, PDO::PARAM_STR);
			$stVideo->bindValue("link", utf8_decode($_REQUEST['link_video'][$indice]), PDO::PARAM_STR);

			$stVideo->execute();

		}
	}
}

//Link -------------------------------------------------------------------
if($upLink) {

	$colunaData = Conexao::chamar()->query("SHOW COLUMNS FROM ".$upTabela."_link WHERE Field = 'data_insercao'")->fetchAll(PDO::FETCH_ASSOC);

	if(!empty($_REQUEST['descricao_link'])) {
		foreach($_REQUEST['descricao_link'] as $indice => $descricaoLink) {

			if(count($colunaData)) {

				$stLink = Conexao::chamar()->prepare("INSERT INTO " . $upTabela . "_link SET $upRel = :id,
																							 descricao = :descricao,
																							 link = :link,
																							 data_insercao = NOW(),
																							 data_modificacao = NOW()");

			} else {

				$stLink = Conexao::chamar()->prepare("INSERT INTO " . $upTabela . "_link SET $upRel = :id,
																							 descricao = :descricao,
																							 link = :link");

			}

			$stLink->bindValue("id", $id, PDO::PARAM_INT);
			$stLink->bindValue("descricao", $descricaoLink, PDO::PARAM_STR);
			$stLink->bindValue("link", utf8_decode($_REQUEST['link_link'][$indice]), PDO::PARAM_STR);

			$stLink->execute();
		}
	}

}