<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT *
											  FROM negocio
										     WHERE id = :id
											   AND status_registro = :status_registro");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}

$tabelaAnexo = "negocio_anexo";
$relacionamentoTabelaAnexo = "artigo";

$tabelaFoto = "negocio_foto";
$relacionamentoTabelaFoto = "artigo";

$tabelaVideo = "negocio_video";
$relacionamentoTabelaVideo = "artigo";
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

	<ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-sm hidden-xs"> Dados Gerais</span></a></li>
	</ul>

	<div class="tab-content">

		<div role="tabpanel" class="tab-pane active" id="geral">
			
			<div class="form-group">
				<label for="titulo" class="col-sm-2 control-label">T&iacute;tulo</label>
				<div class="col-sm-10">
					<input type="text" name="titulo" id="titulo" value="<?= $buscaArtigo['titulo'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">
				</div>
			</div>
			
		</div>

	</div>

	<p class="clearfix"></p>

	<nav class="pull-right hidden-xs hidden-sm">
		<button type="submit" class="btn btn-success pull right"><i class="fa fa-floppy-o"></i> Salvar</button>
		<a href="<?= $caminhoTela ?>" class="btn btn-warning pull right"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>

	<nav class="visible-xs visible-sm">
		<button type="submit" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> Salvar</button>
		<a href="<?= $caminhoTela ?>" class="btn btn-warning btn-block"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>
	
	<p class="clearfix"></p>
	
</form>