<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT *
											  FROM servico
										     WHERE id = :id
											   AND status_registro = :status_registro");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}

$tabelaAnexo = "servico_anexo";
$relacionamentoTabelaAnexo = "servico";

$tabelaFoto = "servico_foto";
$relacionamentoTabelaFoto = "servico";

$tabelaVideo = "servico_video";
$relacionamentoTabelaVideo = "servico";
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

	<ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-sm hidden-xs"> Dados Gerais</span></a></li>
	    <li role="presentation"><a href="#anexo" aria-controls="anexo" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i><span class="hidden-sm hidden-xs"> Anexos</span></a></li>
	    <li role="presentation"><a href="#foto" aria-controls="foto" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><span class="hidden-sm hidden-xs"> Fotos</span></a></li>
	    <li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab"><i class="fa fa-video-camera"></i><span class="hidden-sm hidden-xs"> V&iacute;deos</span></a></li>
	</ul>

	<div class="tab-content">

		<div role="tabpanel" class="tab-pane active" id="geral">
			
			<div class="form-group">
				<label for="titulo" class="col-sm-2 control-label">T&iacute;tulo</label>
				<div class="col-sm-10">
					<input type="text" name="titulo" id="titulo" value="<?= $buscaArtigo['titulo'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">
				</div>
			</div>
			
			<div class="form-group">
				<label for="artigo" class="col-sm-2 control-label">Artigo</label>
				<div class="col-sm-10">
					<textarea name="artigo" id="artigo" class="form-control editor"><?= $buscaArtigo['artigo'] ?></textarea>
				</div>
			</div>
			
		</div>

		<div role="tabpanel" class="tab-pane" id="anexo">
			
			<?php include "$root/privado/sistema/classes/galerias/anexo.php"; 
				
			?>
			
			
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