<?php
if($_POST) {
	
	include "sql.php";
	
}



try {

	$stArtigo = Conexao::chamar()->prepare("SELECT *
									  	 	  FROM banner
									 	     WHERE id = :id
	                                           AND status_registro = :status_registro
	                                           AND id_cliente = :id_cliente");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A","id_cliente" => $buscaAdministrador['id_cliente']));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);



} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

	<div class="form-group">
		<label for="titulo" class="col-sm-2 col-md-2 control-label">T&itilde;tulo</label>
		<div class="col-sm-10 col-md-10">
			<input type="text" name="titulo" id="titulo" value="<?= $buscaArtigo['titulo'] ?>" class="form-control" placeholder="Informe o T&itilde;tulo...">
		</div>
	</div>


	<div class="form-group" >
		<label for="link_banner" class="col-sm-2 col-md-2 control-label">Link</label>
		<div class="col-sm-10 col-md-10">
			<input type="text" name="link_banner" id="link_banner" value="<?= ($id ? $buscaArtigo["link_banner"] : "") ?>" class="form-control url" placeholder="Informe uma url v&aacute;lida...">
		</div>
	</div>

	<div class="form-group">
		
		<label for="data_inicial" class="col-sm-2 col-md-2 control-label" style="margin-bottom: 20px">Data Inicial</label>
		<div class="col-sm-4 col-md-4">
			<div class="input-group date" style="margin-bottom: 20px">
				<input type="text" name="data_inicial" id="data_inicial" value="<?= formata_data($buscaArtigo['data_inicial']) ?>" class="form-control data">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
		</div>
	

	
		<label for="hora_inicial" class="col-sm-2 col-md-2 control-label" style="margin-bottom: 20px">Hora Inicial</label>
		<div class="col-sm-4 col-md-4" style="margin-bottom: 20px">
			<div class="input-group time">
				<input type="text" name="hora_inicial" id="hora_inicial" value="<?= $buscaArtigo['hora_inicial'] ?>" class="form-control hora">
				<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
			</div>
			
		</div>

		<label for="data_limite" class="col-sm-2 col-md-2 control-label" style="margin-bottom: 20px">Data Limite</label>
		<div class="col-sm-4 col-md-4" style="margin-bottom: 20px">
			<div class="input-group date">
				<input type="text" name="data_limite" id="data_limite" value="<?= formata_data($buscaArtigo['data_limite']) ?>" class="form-control data">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
		</div>

		<label for="hora_limite" class="col-sm-2 col-md-2 control-label" style="margin-bottom: 20px">Hora Limite</label>
		<div class="col-sm-4 col-md-4" style="margin-bottom: 20px">
			<div class="input-group time">
				<input type="text" name="hora_limite" id="hora_limite" value="<?= $buscaArtigo['hora_limite'] ?>" class="form-control hora">
				<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
			</div>
		</div>

	</div>

	

	<div class="form-group">
		<label class="col-sm-2 control-label">Foto</label>
		<div class="col-sm-10">
			<div class="input-group">
				<span class="input-group-btn" >
					<span class="btn btn-primary btn-file">
						<i class="fa fa-folder-open"></i> Selecionar&hellip;
						<input type="file" name="foto" id="foto">
					</span>
				</span>
				<input type="text" class="form-control" readonly placeholder="Selecione uma foto...">
			</div>
		</div>
	</div>

	<div class="col-xs-offset-0 col-xs-12 col-sm-offset-4 col-sm-4 col-md-offset-4 col-md-4" style="margin-bottom:40px;display: flex; flex-direction: column;  justify-content: center; align-items: center; height: 133px ;border: 1px solid #ccc; border-radius: 4px;">
		<img id="img-prev" style="height: 100px" class="img-responsive" src="<?= $CAMINHO ?>/imagens/<?= $buscaArtigo['foto'] ?>">
			<?php if($id) { ?>
					
	    				<?php if($buscaArtigo['foto'] != "") { ?>
							<a style="position: absolute;top: 2px;left: 2px" href="<?= $CAMINHO ?>/imagens/<?= $buscaArtigo['foto'] ?>" class="btn btn-info" data-toggle="lightbox" data-gallery="quem_somos_fotos" data-title="Galeria de Fotos" >
							<i class="fa fa-search"></i>
							</a>
							<a style="position: absolute;top: 2px;right: 2px" href="#" onclick="excluirArquivo('<?= $id ?>', 'usuario', 'foto', 'foto', '<?= $caminhoTela ?>&id=<?= $id ?>&s=cadastrar');" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Excluir Anexo"><i class="fa fa-trash"></i></a>
						<?php } ?>
					
			<?php } ?>
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

<script type="text/javascript">

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#img-prev').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	$("#foto").change(function(){
		readURL(this);
	});
</script>