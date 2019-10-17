<?php
	if($_POST) {

		include_once "sql.php";

	}

	try {

		$stArtigo = Conexao::chamar()->prepare("SELECT capinha_modelo.*,
													   capinha_marca.marca marca
												  FROM capinha_modelo
											 LEFT JOIN capinha_marca 
													ON capinha_modelo.id_marca = capinha_marca.id
												 WHERE capinha_modelo.id = :id
												   AND capinha_modelo.status_registro = :status_registro");

		$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
		$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

	} catch (PDOException $e) {

		echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

		echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

	}
?>

<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

    <div class="form-group">
		<label for="id_marca" class="col-sm-2 control-label">Marca</label>
		<div class="col-sm-2 hidden-xs">
			<input type="text" name="id_marca" id="id_marca" value="<?= $buscaArtigo['id_marca'] ?>" class="form-control" readonly >
		</div>
		<div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/marca_capinha.php?t=<?= $t ?>', 'Marcas')"><i class="fa fa-search"></i></button>
				</span>
				<input type="text" name="marca" id="marca" value="<?= $buscaArtigo['marca'] ?>" class="form-control" readonly placeholder="Informe a marca...">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label for="modelo" class="col-sm-2 control-label">Descri&ccedil;&atilde;o</label>
		<div class="col-sm-10">
			<input type="text" name="modelo" id="modelo" value="<?= $buscaArtigo['modelo'] ?>" class="form-control required" required placeholder="Informe a descri&ccedil;&atilde;o...">
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