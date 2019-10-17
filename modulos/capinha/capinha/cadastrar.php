<?php
	if($_POST) {

		include_once "sql.php";

	}

	try {

		if($id) {

			$stArtigo = Conexao::chamar()->prepare("SELECT capinha.*,
														   capinha_marca.marca marca,
														   capinha_modelo.modelo modelo
													  FROM capinha
												 LEFT JOIN capinha_modelo 
												 		ON capinha.id_modelo = capinha_modelo.id
												 LEFT JOIN capinha_marca 
												 		ON capinha.id_marca = capinha_marca.id
													 WHERE capinha.id = :id
													   AND capinha.status_registro = :status_registro");

			$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
			$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

		} else {

			$stArtigo = Conexao::chamar()->prepare("SELECT capinha_marca.id id_marca,
														   capinha_marca.marca marca,
														   capinha_modelo.id id_modelo,
														   capinha_modelo.modelo modelo 
													  FROM capinha_marca 
												 LEFT JOIN capinha_modelo 
												        ON capinha_marca.id = capinha_modelo.id_marca
													 WHERE capinha_marca.id = :id_marca
													   AND capinha_modelo.id = :id_modelo");

			$stArtigo->execute(array("id_modelo" => $id_modelo , "id_marca" => $id_marca));
			$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

		}

		if($_REQUEST['id_excluir']) {

			include_once "$root/privado/sistema/classes/includes/excluir.php";
			excluir($_REQUEST['id_excluir'], "capinha");

		}

	} catch (PDOException $e) {

		echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

		echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

	}

	$tabelaFoto = "capinha_foto";
	$relacionamentoTabelaFoto = "capinha";

?>

	<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-sm hidden-xs"> Dados Gerais</span></a></li>
			<li role="presentation"><a href="#foto" aria-controls="foto" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><span class="hidden-sm hidden-xs"> Fotos</span></a></li>
		</ul>

		<div class="tab-content">

			<div role="tabpanel" class="tab-pane active" id="geral">

				<div class="form-group">
					<label for="id_marca" class="col-sm-2 control-label">Marca</label>
					<div class="col-sm-2 hidden-xs">
						<input type="text" name="id_marca" id="id_marca" value="<?= $buscaArtigo["id_marca"] ?>" class="form-control required" required readonly >
					</div>
					<div class="col-sm-8">
						<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/marca_capinha.php?t=<?= $t ?>', 'Marcas')"><i class="fa fa-search"></i></button>
						</span>
							<input type="text" name="marca" id="marca" value="<?= $buscaArtigo["marca"] ?>" class="form-control required" required readonly placeholder="Informe a marca...">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="id_modelo" class="col-sm-2 control-label">Modelo</label>
					<div class="col-sm-2 hidden-xs">
						<input type="text" name="id_modelo" id="id_modelo" value="<?= $buscaArtigo["id_modelo"] ?>" class="form-control" readonly>
					</div>
					<div class="col-sm-8">
						<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/modelo_capinha.php?t=<?= $t ?>&id_marca='+jQuery('#id_marca').val(), 'Modelos')"><i class="fa fa-search"></i></button>
						</span>
							<input type="text" name="modelo" id="modelo" value="<?= $buscaArtigo["modelo"] ?>" class="form-control" readonly placeholder="Informe o modelo...">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">T&iacute;tulo</label>
					<div class="col-sm-10">
						<input type="text" name="descricao" id="descricao" value="<?= $buscaArtigo['descricao'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">
					</div>
				</div>

				<div class="form-group">
					<label for="data_cadastro" class="col-sm-2 control-label">Data de Cadastro</label>
					<div class="col-sm-10 col-lg-2">
						<div class="input-group date">
							<input type="text" name="data_cadastro" id="data_cadastro" value="<?= empty($buscaArtigo['data_cadastro']) ? date("d/m/Y") : formata_data($buscaArtigo['data_cadastro']) ?>" class="form-control data required" required >
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="valor" class="col-sm-2 control-label">Valor</label>
					<div class="col-sm-10">
						<input type="text" name="valor" id="valor" value="<?= formata_valor($buscaArtigo['valor']) ?>" class="form-control required moeda" required placeholder="Informe o pre&ccedil;o...">
					</div>
				</div>

				<div class="form-group">
					<label for="promocao_inicial" class="col-sm-2 control-label">In&iacute;cio da Promo&ccedil;&atilde;o</label>
					<div class="col-sm-10 col-lg-2">
						<div class="input-group date">
							<input type="text" name="promocao_inicial" id="promocao_inicial" value="<?= formata_data($buscaArtigo['promocao_inicial']) ?>" class="form-control data">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="promocao_final" class="col-sm-2 control-label">Fim da Promo&ccedil;&atilde;o</label>
					<div class="col-sm-10 col-lg-2">
						<div class="input-group date">
							<input type="text" name="promocao_final" id="promocao_final" value="<?= formata_data($buscaArtigo['promocao_final']) ?>" class="form-control data">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="valor_promocional" class="col-sm-2 control-label">Valor Promocional </label>
					<div class="col-sm-10">
						<input type="text" name="valor_promocional" id="valor_promocional" value="<?= formata_valor($buscaArtigo['valor_promocional']) ?>" class="form-control moeda" placeholder="Informe o pre&ccedil;o de...">
					</div>
				</div>

			</div>

			<div role="tabpanel" class="tab-pane" id="foto">

				<?php include "$root/privado/sistema/classes/galerias/foto.php"; ?>

			</div>

		</div>

		<p class="clearfix"></p>

		<nav class="pull-right hidden-xs hidden-sm">
			<?php if($id && $buscaAdministrador['permissao_log'] == 'S') { ?>
				<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_cadastro.php?t=<?= $t ?>&id=<?= $id ?>', 'Registro de Logs de Acesso')" class="btn btn-info pull right"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
			<?php } ?>
			<button type="submit" class="btn btn-success pull right"><i class="fa fa-floppy-o"></i> Salvar</button>
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

