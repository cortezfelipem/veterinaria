<?php
include "../sistema/conexao.php";

try {

	$stConf = Conexao::chamar()->query("SELECT $coluna
			FROM $tabela
			WHERE id = '$id'");

	$busca = $stConf->fetch(PDO::FETCH_ASSOC);

	$stExcluir = Conexao::chamar()->prepare("UPDATE $tabela SET $coluna = NULL WHERE id = :id");
	$exclui = $stExcluir->execute(array("id" => $id));

	if($tipo == "imagem") {

		@unlink($caminhoUploadImagem . "/" . $busca[$coluna]);
		@unlink($caminhoUploadImagem . "/t_" . $busca[$coluna]);
		@unlink($caminhoUploadImagem . "/tb_" . $busca[$coluna]);
		@unlink($caminhoUploadImagem . "/gd_" . $busca[$coluna]);

	} else {

		@unlink($caminhoUploadArquivo . "/" . $busca[$coluna]);
	}

	if($exclui)
		echo "sucesso";

} catch (PDOException $e) {

	echo $e->getMessage();

}