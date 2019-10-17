<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT *
											  FROM eventos
										     WHERE eventos.id = :id
											   AND eventos.status_registro = :status_registro");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}


$tabelaAnexo = "eventos_anexo";
$relacionamentoTabelaAnexo = "artigo";
$tabelaFoto = "eventos_foto";
$relacionamentoTabelaFoto = "artigo";
$tabelaVideo = "eventos_video";
$relacionamentoTabelaVideo = "artigo";

?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-sm hidden-xs"> Dados Gerais</span></a></li>
		<li role="presentation"><a href="#anexo" aria-controls="anexo" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i><span class="hidden-sm hidden-xs"> Anexos</span></a></li>
		<li role="presentation"><a href="#foto" aria-controls="foto" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><span class="hidden-sm hidden-xs"> Fotos</span></a></li>
		<li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab"><i class="fa fa-video_camera"></i><span class="hidden-sm hidden-xs"> V&iacute;deos</span></a></li>
	</ul>

	<div class="tab-content">

		<div role="tabpanel" class="tab-pane active" id="geral">

			<div class="form-group">
				<label for="data_inicio" class="col-sm-2 control-label">Data In&iacute;cio</label>
				<div class="col-sm-10 col-lg-2">
					<div class="input-group date">
						<input type="text" name="data_inicio" id="data_inicio" value="<?= formata_data($buscaArtigo['data_inicio']) ?>" class="form-control data_inicio required" required >
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="data_fim" class="col-sm-2 control-label">Data Fim</label>
				<div class="col-sm-10 col-lg-2">
					<div class="input-group date">
						<input type="text" name="data_fim" id="data_fim" value="<?= formata_data($buscaArtigo['data_fim']) ?>" class="form-control data_fim required" required >
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="horario" class="col-sm-2 control-label">Hora do Evento</label>
				<div class="col-sm-10 col-lg-2">
					<input type="text" name="horario" id="horario" value="<?= $buscaArtigo['horario'] ?>" class="form-control hora" >
				</div>
			</div>

			<div class="form-group">
				<label for="cor" class="col-sm-2 control-label">Cor do Evento</label>
				<div class="col-sm-10 col-lg-2">
					<input type="color" name="cor" id="cor" value="<?= $buscaArtigo['cor'] ?>" class="form-control cor" >
				</div>
			</div>

			<div class="form-group">
				<label for="evento" class="col-sm-2 control-label">Evento</label>
				<div class="col-sm-10">
					<input type="text" name="evento" id="evento" value="<?= $buscaArtigo['evento'] ?>" class="form-control required" required placeholder="Informe a descri&ccedil;&atilde;o...">
				</div>
			</div>

			<div class="form-group">
				<label for="local" class="col-sm-2 control-label">Local</label>
				<div class="col-sm-10">
					<input type="text" name="local" id="local" value="<?= $buscaArtigo['local'] ?>" class="form-control" placeholder="Informe o chap&eacute;u...">
				</div>
			</div>

			<div class="form-group">
				<label for="observacao" class="col-sm-2 control-label">Observa&ccedil;&atilde;o</label>
				<div class="col-sm-10">
					<textarea name="observacao" id="observacao" class="form-control editor"><?= $buscaArtigo['observacao'] ?></textarea>
				</div>
			</div>
			

		</div>

		<div role="tabpanel" class="tab-pane" id="anexo">

			<?php include "$root/privado/sistema/classes/galerias/anexo.php"; ?>

		</div>

		<div role="tabpanel" class="tab-pane" id="foto">

			<?php include "$root/privado/sistema/classes/galerias/foto.php"; ?>

		</div>

		<div role="tabpanel" class="tab-pane" id="video">

			<?php include "$root/privado/sistema/classes/galerias/video.php"; ?>

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