<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT *
									  	 	  FROM audio
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
		<label for="descricao" class="col-sm-2 control-label">Descri&ccedil;&atilde;o</label>
		<div class="col-sm-10">
			<input type="text" name="descricao" id="descricao" value="<?= $buscaArtigo['descricao'] ?>" class="form-control required" required placeholder="Informe a descri&ccedil;&atilde;o...">
		</div>
	</div>
	
	<div class="form-group">
		<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>
		<div class="col-sm-10">
			<div class="input-group">
				<span class="input-group-btn">
					<span class="btn btn-primary btn-file">
						<i class="fa fa-folder-open"></i> Selecionar&hellip;
						<input type="file" name="arquivo" id="arquivo">
					</span>
				</span>
				<input type="text" class="form-control" readonly placeholder="Selecione um arquivo...">
				<?php if($id) { ?>
					<span class="input-group-btn">
						<?php if($buscaArtigo['arquivo'] != "") { ?>
							<a href="<?= $CAMINHO ?>/arquivos/<?= $idCliente ?>/<?= $buscaArtigo['arquivo'] ?>" class="btn btn-info" target="_blank" data-toggle="tooltip" data-placement="left" title="Baixar Anexo"><i class="fa fa-cloud-download"></i></a>
							<a href="#" onclick="excluirArquivo('<?= $id ?>', 'audio', 'arquivo', 'arquivo', '<?= $caminhoTela ?>&id=<?= $id ?>&s=cadastrar');" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Excluir Anexo"><i class="fa fa-trash"></i></a>
						<?php } ?>
					</span>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="foto" class="col-sm-2 control-label">Arquivo</label>
		<div class="col-sm-10">
			<div class="input-group">
    	    	<span class="input-group-btn">
    	        	<span class="btn btn-primary btn-file">
    	            	<i class="fa fa-folder-open"></i> Selecionar&hellip;
						<input type="file" name="foto" id="foto">
    	      		</span>
    	 		</span>
				<input type="text" class="form-control" readonly placeholder="Selecione um foto...">
				<?php if($id) { ?>
					<span class="input-group-btn">
    				<?php if($buscaArtigo['foto'] != "") { ?>
						<a href="<?= $CAMINHO ?>/imagens/<?= $idCliente ?>/<?= $buscaArtigo['foto'] ?>" class="btn btn-info" target="_blank" data-toggle="tooltip" data-placement="left" title="Baixar Anexo"><i class="fa fa-cloud-download"></i></a>
						<a href="#" onclick="excluirArquivo('<?= $id ?>', 'audio', 'foto', 'foto', '<?= $caminhoTela ?>&id=<?= $id ?>&s=cadastrar');" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Excluir Anexo"><i class="fa fa-trash"></i></a>
					<?php } ?>
				</span>
				<?php } ?>
			</div>
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