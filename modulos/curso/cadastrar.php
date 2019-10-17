<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stCurso = Conexao::chamar()->prepare("SELECT *
                                	          FROM curso
                                	         WHERE id = :id 
											   AND status_registro = :status_registro");

	$stCurso->execute(array("id" => $id, "status_registro" => "A"));
	$buscaCurso = $stCurso->fetch(PDO::FETCH_ASSOC);

	if($_REQUEST['id_excluir']) {
		
		include_once "$root/privado/sistema/classes/includes/excluir.php";
		excluir($_REQUEST['id_excluir'], "curso");

	}

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}

$tabelaFoto = "curso_foto";
$relacionamentoTabelaFoto = "curso";

?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">
	
	<ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-sm hidden-xs"> Dados Gerais</span></a></li>
	    <li role="presentation"><a href="#foto" aria-controls="foto" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><span class="hidden-sm hidden-xs"> Fotos</span></a></li>
	</ul>

	<div class="tab-content">
	
		<div role="tabpanel" class="tab-pane active" id="geral">

			<div class="form-group">
				<label for="data_inicio" class="col-sm-2 control-label">Data de In&iacute;cio</label>
				<div class="col-sm-10 col-lg-2">
					<div class="input-group date">
						<input type="text" name="data_inicio" id="data_inicio" value="<?= formata_data($buscaCurso['data_inicio']) ?>" class="form-control required data" required>
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="data_fim" class="col-sm-2 control-label">Data de Encerramento</label>
				<div class="col-sm-10 col-lg-2">
					<div class="input-group date">
						<input type="text" name="data_fim" id="data_fim" value="<?= formata_data($buscaCurso['data_fim']) ?>" class="form-control required data" required>
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="horario_inicio" class="col-sm-2 control-label">Hor&aacute;rio de In&iacute;cio</label>
				<div class="col-sm-10 col-lg-2">
					<input type="text" name="horario_inicio" id="horario_inicio" value="<?= $buscaCurso['horario_inicio'] ?>" class="form-control hora required" required>
				</div>
			</div>

			<div class="form-group">
				<label for="horario_fim" class="col-sm-2 control-label">Hor&aacute;rio de Encerramento</label>
				<div class="col-sm-10 col-lg-2">
					<input type="text" name="horario_fim" id="horario_fim" value="<?= $buscaCurso['horario_fim'] ?>" class="form-control hora required" required>
				</div>
			</div>

			<div class="form-group">
				<label for="local" class="col-sm-2 control-label">Local</label>
				<div class="col-sm-10">
					<input type="text" name="local" id="local" value="<?= $buscaCurso['local'] ?>" class="form-control required" required>
				</div>
			</div>
			
			<div class="form-group">
				<label for="preco" class="col-sm-2 control-label">Pre&ccedil;o</label>
				<div class="col-sm-10 col-lg-2">
					<input type="text" name="preco" id="preco" value="<?= $buscaCurso['preco'] ?>" class="form-control required moeda" required>
				</div>
			</div>

			<div class="form-group">
				<label for="email" class="col-sm-2 control-label">E-mail de Retorno</label>
				<div class="col-sm-10 col-lg-2">
					<input type="text" name="email" id="email" value="<?= $buscaCurso['email'] ?>" class="form-control required email" required>
				</div>
			</div>

			<div class="form-group">
				<label for="titulo" class="col-sm-2 control-label">T&iacute;tulo</label>
				<div class="col-sm-10">
					<input type="text" name="titulo" id="titulo" value="<?= $buscaCurso['titulo'] ?>" class="form-control required" required placeholder="Informe a descri&ccedil;&atilde;o...">
				</div>
			</div>
			
			<div class="form-group">
				<label for="artigo" class="col-sm-2 control-label">Descri&ccedil;&atilde;o</label>
				<div class="col-sm-10">
					<textarea name="artigo" id="artigo" class="form-control editor"><?= $buscaCurso['artigo'] ?></textarea>
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