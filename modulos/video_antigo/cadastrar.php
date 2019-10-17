<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT *
									  	 	  FROM video
									 	     WHERE id = :id
	                                           AND status_registro = :status_registro");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>
	<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">
		
		<div class="form-group">
			<label for="titulo" class="col-sm-2 control-label">T&iacute;tulo</label>
			<div class="col-sm-10">
				<input type="text" name="titulo" id="titulo" value="<?= $buscaArtigo["titulo"] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo do arquivo...">
			</div>
		</div>

		<div class="form-group">
			<label for="link_video" class="col-sm-2 control-label">Link</label>
			<div class="col-sm-10">
				<input type="text" name="link_video" id="link_video" value="<?= $buscaArtigo["link"] ?>" class="form-control url" placeholder="Informe o link...">
			</div>
		</div>

		<div class="form-group">
			<label for="artigo" class="col-sm-2 control-label">Artigo</label>
			<div class="col-sm-10">
				<textarea name="artigo" id="artigo" class="form-control editor"><?= $buscaArtigo['artigo'] ?></textarea>
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
<?php
include "javaScript.php";

