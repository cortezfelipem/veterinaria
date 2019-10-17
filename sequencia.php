<?php

function nomeMenus($id_menu, $array) {



	$stNome = Conexao::chamar()->prepare("SELECT descricao, 
												 id_menu 
											FROM controle_menu 
										   WHERE id = :id_menu 
										     AND status_registro = :status_registro");

	$stNome->execute(array("id_menu" => $id_menu, "status_registro" => "A"));

	$nome = $stNome->fetch(PDO::FETCH_ASSOC);



	array_push($array, $nome['descricao']);



	if(!empty($nome['id_menu']))

		return nomeMenus($nome['id_menu'], $array);



	return $array;



}



if(empty($t)) {



	include "home.php";



} else if($t == "troca_sistema") {

    

    include "troca_sistema.php";

    

} else {


	$stMenu = Conexao::chamar()->prepare("SELECT controle_menu.* 
											FROM controle_menu
									   LEFT JOIN controle_menu_usuario
											  ON controle_menu.id = controle_menu_usuario.id_menu 
										   WHERE controle_menu_usuario.id_usuario = :id_usuario
											 AND controle_menu.id = :id
											 AND controle_menu.status_registro = :status_registro
										ORDER BY controle_menu.ordem, controle_menu.id");

	$stMenu->execute(array("id_usuario" => $_COOKIE["id_usuario"], "id" => $t, "status_registro" => "A"));


	$buscaMenu = $stMenu->fetch(PDO::FETCH_ASSOC);

	$caminhoArquivoInclude = "sistema/modulos/$buscaMenu[pasta]/".($s ? $s : "listar").".php";



	if(file_exists($caminhoArquivoInclude)) {



		echo "<h3 class='text-primary'><i class='" . (empty($buscaMenu['icone']) ? "fa fa-caret-right" : $buscaMenu['icone']) . "'></i> " . $buscaMenu['descricao'] . "</h3>";



		$array = array();

		$listaMenu = array_reverse(nomeMenus($buscaMenu['id_menu'], $array));



		echo "<ol class='breadcrumb'>";



		foreach ($listaMenu as $itemLista) {



			echo "<li>$itemLista</li>";



		}



		echo "</ol>";



		$caminhoTela = $CAMINHO . "/inicio.php?m=$m&t=$t&time=" . time();



		include $caminhoArquivoInclude;



	} else {



		include "404.php";



	}



}