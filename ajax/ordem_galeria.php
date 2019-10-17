<?php
include "../sistema/conexao.php";

parse_str($_POST['sort'], $sort1);

try {

	foreach($sort1['item'] as $key => $value) {

		$ordem = $key + 1;

		$st = Conexao::chamar()->prepare("UPDATE $tabela
											 SET ordem = :ordem
										   WHERE id = :id");

		$st->execute(array("ordem" => $ordem, "id" => $value));

	}

} catch (PDOException $e) {

	echo $e->getMessage();

}