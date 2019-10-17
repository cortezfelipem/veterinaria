<?php
include "../sistema/conexao.php";

$stConf = Conexao::chamar()->query("SELECT *
									  FROM chave_seguranca
									 WHERE id_cliente = '$idCliente'");

$conf = $stConf->fetch(PDO::FETCH_ASSOC);

if (crypt($chave, $conf['chave']) === $conf['chave']) {

	$stRecupera = Conexao::chamar()->prepare("UPDATE $tabela SET status_registro = :status_registro WHERE id = :id");
	$recupera = $stRecupera->execute(array("status_registro" => "A", "id" => $id));

	if($recupera) {

		logAcesso("R", $tela, $id);
		echo "sucesso";
	} else {

		echo "erro";
	}

} else {

	echo "invalido";
}