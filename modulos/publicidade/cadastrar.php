<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stPublicidade = Conexao::chamar()->prepare("SELECT *
									  	 	  FROM publicidade
									 	     WHERE id = :id
	                                           AND status_registro = :status_registro");

	$stPublicidade->execute(array("id" => $id, "status_registro" => "A"));
	$buscaPublicidade = $stPublicidade->fetch(PDO::FETCH_ASSOC);


} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

	<div class="form-group">
		<label for="titulo" class="col-sm-2 control-label">T&itilde;tulo</label>
		<div class="col-sm-10">
			<input type="text" name="titulo" id="titulo" value="<?= $buscaPublicidade['titulo'] ?>" class="form-control required" required placeholder="Informe o T&itilde;tulo...">
		</div>
	</div>

	<div class="form-group">
		<label for="data_inicial" class="col-sm-2 control-label">Data In&iacute;cial</label>
		<div class="col-sm-10 col-lg-2">
			<div class="input-group date">
				<input type="text" name="data_inicial" id="data_inicial" value="<?= formata_data($buscaPublicidade['data_inicial']) ?>" class="form-control data required" required >
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="hora_inicial" class="col-sm-2 control-label">Hora In&iacute;cial</label>
		<div class="col-sm-10 col-lg-2">
			<input type="text" name="hora_inicial" id="hora_inicial" value="<?= $buscaPublicidade['hora_inicial'] ?>" class="form-control required hora" required >
		</div>
	</div>

	<div class="form-group">
		<label for="data_limite" class="col-sm-2 control-label">Data Limite</label>
		<div class="col-sm-10 col-lg-2">
			<div class="input-group date">
				<input type="text" name="data_limite" id="data_limite" value="<?= formata_data($buscaPublicidade['data_limite']) ?>" class="form-control data required" required >
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="hora_limite" class="col-sm-2 control-label">Hora Limite</label>
		<div class="col-sm-10 col-lg-2">
			<input type="text" name="hora_limite" id="hora_limite" value="<?= $buscaPublicidade['hora_limite'] ?>" class="form-control required hora" required >
		</div>
	</div>
	<div class="form-group" >
		<label for="link" class="col-sm-2 control-label">Link</label>
		<div class="col-sm-10">
			<input type="text" name="linkPublicidade" id="linkPublicidade" value="<?= $buscaPublicidade["link"] ?>" class="form-control url" placeholder="Informe uma url v&aacute;lida...">
		</div>
	</div>
	<div class="form-group">
		<label for="foto" class="col-sm-2 control-label">Foto</label>
		<div class="col-sm-10">
			<div class="input-group">
    	    	<span class="input-group-btn">
    	        	<span class="btn btn-primary btn-file">
    	            	<i class="fa fa-folder-open"></i> Selecionar&hellip;
						<input type="file" name="foto" id="foto">
    	      		</span>
    	 		</span>
				<input type="text" class="form-control" readonly placeholder="Selecione uma foto...">
				<?php if($id) { ?>
					<span class="input-group-btn">
    				<?php if($buscaPublicidade['foto'] != "") { ?>
						<a href="<?= $CAMINHO ?>/imagens/<?= $idCliente ?>/<?= $buscaPublicidade['foto'] ?>" class="btn btn-info" target="_blank" data-toggle="tooltip" data-placement="left" title="Baixar Anexo"><i class="fa fa-cloud-download"></i></a>
						<a href="#" onclick="excluirArquivo('<?= $id ?>', 'publicidade', 'foto', 'foto', '<?= $caminhoTela ?>&id=<?= $id ?>&s=cadastrar');" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Excluir Anexo"><i class="fa fa-trash"></i></a>
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