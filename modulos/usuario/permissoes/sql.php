<?php
try {

	


			$stCadastro = Conexao::chamar()->prepare("UPDATE usuario SET master = :master,
																		 permissao_log = :permissao_log
																   WHERE id = :id");
			
			$stCadastro->bindValue("master", $master ? $master : "N", PDO::PARAM_STR);
			$stCadastro->bindValue("permissao_log", $permissao_log ? $permissao_log : "N", PDO::PARAM_STR);
			$stCadastro->bindValue("id", $id, PDO::PARAM_INT);

			$cadastro = $stCadastro->execute();

			if($cadastro) {
			
				$stRemoveMenu = Conexao::chamar()->prepare("DELETE FROM controle_menu_usuario WHERE id_usuario = :id");
				$stRemoveMenu->bindValue("id", $id, PDO::PARAM_INT);
				$stRemoveMenu->execute();
			
				
				foreach($_POST['id_menu'] as $indice => $idMenu) {
					
					
					$stMenuUsuario = Conexao::chamar()->prepare("INSERT INTO controle_menu_usuario SET id_menu = :id_menu,
																									   id_usuario = :id_usuario,
																									   cadastrar = :cadastrar,
																									   excluir = :excluir");
					$stMenuUsuario->bindValue("id_menu", $idMenu, PDO::PARAM_INT);
					$stMenuUsuario->bindValue("id_usuario", $id, PDO::PARAM_INT);
					$stMenuUsuario->bindValue("cadastrar", empty($_POST["id_menu_cadastrar_$idMenu"]) ? "N" : "S", PDO::PARAM_STR);
					$stMenuUsuario->bindValue("excluir", empty($_POST["id_menu_excluir_$idMenu"]) ? "N" : "S", PDO::PARAM_STR);

					$stMenuUsuario->execute();

					$stMenu2 = Conexao::chamar()->prepare("SELECT * FROM controle_menu WHERE id_menu = :id_menu AND status_registro = :status_registro");
					$stMenu2->execute(array("id_menu" => $idMenu, "status_registro" => "A"));
					$qryMenu2 = $stMenu2->fetchAll(PDO::FETCH_ASSOC);
				  
					if(count($qryMenu2)) {
						
						foreach ($qryMenu2 as $menu2) {

							$stMenuUsuario2 = Conexao::chamar()->prepare("INSERT INTO controle_menu_usuario SET id_menu = :id_menu,
																											 	id_usuario = :id_usuario,
																											 	cadastrar = :cadastrar,
																											 	excluir = :excluir");
							$stMenuUsuario2->bindValue("id_menu", $menu2['id'], PDO::PARAM_INT);
							$stMenuUsuario2->bindValue("id_usuario", $id, PDO::PARAM_INT);
							$stMenuUsuario2->bindValue("cadastrar", empty($_POST["id_menu_cadastrar_$idMenu"]) ? "N" : "S", PDO::PARAM_STR);
							$stMenuUsuario2->bindValue("excluir", empty($_POST["id_menu_excluir_$idMenu"]) ? "N" : "S", PDO::PARAM_STR);

							$stMenuUsuario2->execute();

						}

					}
				

				}

				logAcesso("A", $tela, $id);

				//echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");
				
				echo "<script>window.location='inicio.php?msg_sucesso=".urlencode('Registro alterado com sucesso.')."'</script>";

			} else {

				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

			}

		

	

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel salvar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}