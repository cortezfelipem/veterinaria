<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT *
									  	 	  FROM usuario
									 	     WHERE id = :id
										  	   AND status_registro = :status_registro
										  	   AND id_cliente = :id_cliente ");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A", "id_cliente" => $buscaAdministrador['id_cliente']));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>
<form class="form-horizontal validar_formulario_com_senha" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

	<ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active"><a href="#permissoes" aria-controls="permissoes" role="tab" data-toggle="tab"><i class="fa fa-lock"></i><span class="hidden-sm hidden-xs"> Permiss&otilde;es</span></a></li>
	</ul>

	<div class="tab-content">

		<div role="tabpanel" class="tab-pane active" id="permissoes">

			<div class="col-md-12">
				<div class="panel">
					<div class="panel-body">
						<div class="form-group col-md-12">
							<label for="usuario">Nome:</label>
							<input type="text" name="usuario" id="usuario" class="form-control required" value="<?= $buscaArtigo['usuario'] ?>" disabled placeholder="Informe o nome do usu&aacute;rio...">
						
						</div>
						
						<div class="form-group col-md-12">
							<label for="email">E-mail</label>
							<input type="text" name="email" id="email" class="form-control required email" value="<?= $buscaArtigo['email'] ?>"disabled  placeholder="Informe o e-mail do usu&aacute;rio...">
						</div>
						
						<div class="checkbox">
							<label class="col-sm-12">
								<input type="checkbox" name="master" <?php if($buscaArtigo['master'] == 'S') echo"checked"; ?> value="S"> Usu&aacute;rio Master.
							</label>
						</div>
						
						<div class="checkbox">
							<label class="col-sm-12">
								<input type="checkbox" name="permissao_log" <?php if($buscaArtigo['permissao_log'] == 'S') echo"checked"; ?> value="S"> Permitir visualiza&ccedil;&atilde;o de log de acesso.
							</label>
						</div>
					</div>
				</div>
			</div>
		

			<label><input type="checkbox" name="marcar_desmarcar" id="marcar_desmarcar" />&nbsp;Marcar / Desmarcar Todos</label>
			<table class="table table-condensed table-hover">
	    		<?php
//	    		$stSistema = Conexao::chamar()->prepare("SELECT *
//					    								   FROM controle_sistema
//					    								  WHERE status_registro = :status_registro");
//	    		$stSistema->execute(array("status_registro" => "A"));
//	    		$qrySistema = $stSistema->fetchAll(PDO::FETCH_ASSOC);

//	    		foreach ($qrySistema as $buscaSistema) {
	    		?>
					<tr>
						<td class="success">
							<h4><i class="fa fa-folder-open"></i> <?= $buscaSistema['descricao'] ?></h4>
						</td>
					</tr>
					<!-- Hotsites Secretarias -->
					<?php
					if(empty($id)) {

					//MODULOS PARA UM NOVO CADASTRO
					$stModulo = Conexao::chamar()->prepare("SELECT *
															  FROM controle_menu
															 WHERE id_menu IS NULL
															   AND status_registro = :status_registro");
//					$stModulo->execute(array("id_sistema" => $buscaSistema['id'], "status_registro" => "A"));
						$stModulo->execute(array("status_registro" => "A"));
					$qryModulo = $stModulo->fetchAll(PDO::FETCH_ASSOC);

					foreach ($qryModulo as $buscaModulo) {
					?>
						<tr>
							<td style="padding-left: 20px;" class="info">
								<label><input type="checkbox" name="id_modulo_<?=$buscaModulo['id'] ?>" id="id_modulo_<?=$buscaModulo['id'] ?>" class="marcar" value="<?=$buscaModulo['id'] ?>" onchange="marcaDesmarcaModulo('<?=$buscaModulo['id'] ?>')" />&nbsp;<strong><?=$buscaModulo['descricao'] ?></strong></label>
							</td>
						</tr>
						<?php
						$stMenu = Conexao::chamar()->prepare("SELECT *
																FROM controle_menu
															   WHERE id_menu = :id_menu
																 AND status_registro = :status_registro
															ORDER BY ordem");
						$stMenu->execute(array("id_menu" => $buscaModulo['id'], "status_registro" => "A"));
						$qryMenu = $stMenu->fetchAll(PDO::FETCH_ASSOC);

						foreach ($qryMenu as $buscaMenu) {

							$stMenu2 = Conexao::chamar()->prepare("SELECT *
																	 FROM controle_menu
																	WHERE id_menu = :id_menu
																	  AND status_registro = :status_registro
																 ORDER BY ordem");
							$stMenu2->execute(array("id_menu" => $buscaMenu['id'], "status_registro" => "A"));
							$qryMenu2 = $stMenu2->fetchAll(PDO::FETCH_ASSOC);

							if(count($qryMenu2) < 1) {
								?>
								<tr class="tr_modulo_<?=$buscaModulo['id'] ?> active">
									<td style="padding-left: 35px;">
										<label><input type="checkbox" name="id_menu[]" id="id_menu_<?=$buscaMenu['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="<?=$buscaMenu['id'] ?>" onchange="marcaDesmarcaMenu('<?=$buscaMenu['id'] ?>')" />&nbsp;<strong><?=$buscaMenu['descricao'] ?></strong></label>
									</td>
								</tr>
								<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
									<td style="padding-left: 50px;">
										<label><input type="checkbox" name="id_menu_cadastrar_<?=$buscaMenu['id'] ?>" id="id_menu_cadastrar_<?=$buscaMenu['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="S" />&nbsp;Cadastrar / Alterar</label>
									</td>
								</tr>
								<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
									<td style="padding-left: 50px;">
										<label><input type="checkbox" name="id_menu_excluir_<?=$buscaMenu['id'] ?>" id="id_menu_excluir_<?=$buscaMenu['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="S" />&nbsp;Excluir</label>
									</td>
								</tr>
								<!-- SUBMENU NIVEL 2 -->
								<?php
							} else {
								foreach ($qryMenu2 as $buscaMenu2) {
								?>
								<tr class="tr_modulo_<?=$buscaModulo['id'] ?> active">
									<td style="padding-left: 35px;">
										<label><input type="checkbox" name="id_menu[]" id="id_menu_<?=$buscaMenu2['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="<?=$buscaMenu2['id'] ?>" onchange="marcaDesmarcaMenu('<?=$buscaMenu2['id'] ?>')" />&nbsp;<strong><?=$buscaMenu2['descricao'] ?></strong></label>
									</td>
								</tr>
								<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
									<td style="padding-left: 50px;">
										<label><input type="checkbox" name="id_menu_cadastrar_<?=$buscaMenu2['id'] ?>" id="id_menu_cadastrar_<?=$buscaMenu2['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="S" />&nbsp;Cadastrar / Alterar</label>
									</td>
								</tr>
								<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
									<td style="padding-left: 50px;">
										<label><input type="checkbox" name="id_menu_excluir_<?=$buscaMenu2['id'] ?>" id="id_menu_excluir_<?=$buscaMenu2['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="S" />&nbsp;Excluir</label>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
						<!-- SUBMENU NIVEL 2 -->
						<?php } ?>
						<tr>
							<td>&nbsp;</td>
						</tr>
					<?php } ?>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<?php } else { ?>
						<?php
						//MODULOS PARA ALTERACAO DE CADASTRO
						$stModulo = Conexao::chamar()->prepare("SELECT *
																  FROM controle_menu
																 WHERE id_menu IS NULL
																   AND status_registro = :status_registro");
//						$stModulo->execute(array("id_sistema" => $buscaSistema['id'], "status_registro" => "A"));
						$stModulo->execute(array("status_registro" => "A"));
						$qryModulo = $stModulo->fetchAll(PDO::FETCH_ASSOC);

						foreach ($qryModulo as $buscaModulo) {
							?>
							<tr>
								<td style="padding: 2px 2px 2px 20px; background: #EDEDED;">
									<label><input type="checkbox" name="id_modulo_<?=$buscaModulo['id'] ?>" id="id_modulo_<?=$buscaModulo['id'] ?>" class="marcar" value="<?=$buscaModulo['id'] ?>" checked="checked" onchange="marcaDesmarcaModulo('<?=$buscaModulo['id'] ?>')" />&nbsp;<strong style="font-size: 13pt;"><?=$buscaModulo['descricao'] ?></strong></label>
									<!--<input type="checkbox" id="modulo_<?=$buscaModulo['id'] ?>" name="id_menu[]" value="<?=$buscaModulo['id'] ?>" checked="checked"/>--> 
								</td>
							</tr>
							<?php
							$stMenu = Conexao::chamar()->prepare("SELECT *
																	FROM controle_menu
																   WHERE id_menu = :id_menu
																	 AND status_registro = :status_registro
																ORDER BY ordem");
							$stMenu->execute(array("id_menu" => $buscaModulo['id'], "status_registro" => "A"));
							$qryMenu = $stMenu->fetchAll(PDO::FETCH_ASSOC);

							foreach ($qryMenu as $buscaMenu) {

								$stMenu2 = Conexao::chamar()->prepare("SELECT *
																		 FROM controle_menu
																		WHERE id_menu = :id_menu
																		  AND status_registro = :status_registro
																	 ORDER BY ordem");
								$stMenu2->execute(array("id_menu" => $buscaMenu['id'], "status_registro" => "A"));
								$qryMenu2 = $stMenu2->fetchAll(PDO::FETCH_ASSOC);

								if(count($qryMenu2) < 1) {

									$stConf = Conexao::chamar()->prepare("SELECT *
																			FROM controle_menu_usuario
																		   WHERE id_usuario = :id_usuario
																			 AND id_menu = :id_menu");
									$stConf->execute(array("id_usuario" => $id, "id_menu" => $buscaMenu['id']));
									$conf = $stConf->fetch(PDO::FETCH_ASSOC);
									?>
									<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
										<td style="padding: 2px 2px 2px 35px;">
											<label><input type="checkbox" name="id_menu[]" id="id_menu_<?=$buscaMenu['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="<?=$buscaMenu['id'] ?>" <? if($conf['id'] != "") echo "checked"; ?> onchange="marcaDesmarcaMenu('<?=$buscaMenu['id'] ?>')" />&nbsp;<strong><?=$buscaMenu['descricao'] ?></strong></label>
										</td>
									</tr>
									<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
										<td style="padding: 2px 2px 2px 50px;">
											<label><input type="checkbox" name="id_menu_cadastrar_<?=$buscaMenu['id'] ?>" id="id_menu_cadastrar_<?=$buscaMenu['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="S" <? if($conf['cadastrar'] == "S") echo "checked"; ?> />&nbsp;Cadastrar / Alterar</label>
										</td>
									</tr>
									<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
										<td style="padding: 2px 2px 2px 50px;">
											<label><input type="checkbox" name="id_menu_excluir_<?=$buscaMenu['id'] ?>" id="id_menu_excluir_<?=$buscaMenu['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="S" <? if($conf['excluir'] == "S") echo "checked"; ?> />&nbsp;Excluir</label>
										</td>
									</tr>
									<!-- SUBMENU NIVEL 2 -->
									<?php
								} else {
									foreach ($qryMenu2 as $buscaMenu2) {

										$stConf2 = Conexao::chamar()->prepare("SELECT *
																				 FROM controle_menu_usuario
																				WHERE id_usuario = :id_usuario
																				  AND id_menu = :id_menu");
										$stConf2->execute(array("id_usuario" => $id, "id_menu" => $buscaMenu2['id']));
										$conf2 = $stConf2->fetch(PDO::FETCH_ASSOC);

									?>
									<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
										<td style="padding: 2px 2px 2px 35px;">
											<label><input type="checkbox" name="id_menu[]" id="id_menu_<?=$buscaMenu2['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="<?=$buscaMenu2['id'] ?>" <? if($conf2['id'] != "") echo "checked"; ?> onchange="marcaDesmarcaMenu('<?=$buscaMenu2['id'] ?>')" />&nbsp;<strong><?=$buscaMenu2['descricao'] ?></strong></label>
										</td>
									</tr>
									<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
										<td style="padding: 2px 2px 2px 50px;">
											<label><input type="checkbox" name="id_menu_cadastrar_<?=$buscaMenu2['id'] ?>" id="id_menu_cadastrar_<?=$buscaMenu2['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="S" <? if($conf2['cadastrar'] == "S") echo "checked"; ?> />&nbsp;Cadastrar / Alterar</label>
										</td>
									</tr>
									<tr class="tr_modulo_<?=$buscaModulo['id'] ?>">
										<td style="padding: 2px 2px 2px 50px;">
											<label><input type="checkbox" name="id_menu_excluir_<?=$buscaMenu2['id'] ?>" id="id_menu_excluir_<?=$buscaMenu2['id'] ?>" class="marcar marcar_<?=$buscaModulo['id'] ?>" value="S" <? if($conf2['excluir'] == "S") echo "checked"; ?> />&nbsp;Excluir</label>
										</td>
									</tr>
								<?php } ?>
							<?php } ?>
						<!-- SUBMENU NIVEL 2 -->
						<?php } ?>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<?php } ?>
						<tr>
							<td>&nbsp;</td>
						</tr>
					<?php } ?>
<!--	    		--><?php //} ?>
			</table>
		</div>

	</div>

	<p class="clearfix"></p>

	<nav class="pull-right hidden-xs hidden-sm">
		<?php if($id && $buscaAdministrador['permissao_log'] == 'S') { ?>
		<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_cadastro.php?t=<?= $t ?>&id=<?= $id ?>', 'Registro de Logs de Acesso')" class="btn btn-info pull right"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
		<?php } ?>
		<button onclick="$(this).attr('disabled');" type="submit" class="btn btn-success pull right"><i class="fa fa-floppy-o"></i> Salvar</button>
		<a href="<?= $caminhoTela ?>" class="btn btn-warning pull right"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>

	<nav class="visible-xs visible-sm">
		<?php if($id && $buscaAdministrador['permissao_log'] == 'S') { ?>
		<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_cadastro.php?t=<?= $t ?>&id=<?= $id ?>', 'Registro de Logs de Acesso')" class="btn btn-info btn-block"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
		<?php } ?>
		<button type="submit" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> Salvar</button>
		<a href="<?= $caminhoTela ?>" class="btn btn-warning btn-block"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>
	
	<p class="clearfix"></p>
	
</form>
<?php
include "javaScript.php";