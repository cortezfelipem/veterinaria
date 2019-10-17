<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT produto_atributo.*,
                                	               produto_tipo_atributo.descricao tipo_atributo
                                	          FROM produto_atributo
                                	     LEFT JOIN produto_tipo_atributo ON produto_atributo.id_tipo_atributo = produto_tipo_atributo.id
                                	         WHERE produto_atributo.id = :id
	                                           AND produto_atributo.status_registro = :status_registro");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);



} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">
	<div class="col-md-10">
		<div class="form-group">
			<label for="descricao" class="col-sm-2 control-label">Atributo</label>
			<div class="col-sm-10">
				<input type="text" name="descricao" id="descricao" value="<?= $buscaArtigo['descricao'] ?>" class="form-control required" required placeholder="Informe a descri&ccedil;&atilde;o...">
			</div>
		</div>

	    <div class="form-group">
			<label for="id_tipo_atributo" class="col-sm-2 control-label">Tipo Atributo</label>
			<div class="col-sm-2 hidden-xs">
				<input type="text" name="id_tipo_atributo" id="id_tipo_atributo" value="<?= $buscaArtigo['id_tipo_atributo'] ?>" class="form-control" readonly >
			</div>
			<div class="col-sm-8">
				<div class="input-group">
					<span class="input-group-btn">
						<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/tipo_atributo.php?t=<?= $t ?>', 'Tipo Atributo')"><i class="fa fa-search"></i></button>
					</span>
					<input type="text" name="tipo_atributo" id="tipo_atributo" value="<?= $buscaArtigo['tipo_atributo'] ?>" class="form-control" readonly placeholder="Informe o tipo de atributo...">
				</div>
			</div>
		</div>
	

		<div class="form-group">
			<label for="foto" class="col-sm-2 control-label">Foto</label>
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
	</div>
	<div class="col-md-2" style="display: flex; flex-direction: column;  justify-content: center; align-items: center; height: 133px ;border: 1px solid #ccc; border-radius: 4px;">
		<img id="img-prev" style="height: 100px" class="img-responsive" src="<?= $CAMINHO ?>/imagens/<?= $buscaArtigo['foto'] ?>">
			<?php if($id) { ?>
					
	    				<?php if($buscaArtigo['foto'] != "") { ?>
							<a style="position: absolute;top: 2px;left: 2px" href="<?= $CAMINHO ?>/imagens/<?= $buscaArtigo['foto'] ?>" class="btn btn-info" target="_blank" data-toggle="tooltip" data-placement="left" title="Baixar Anexo"><i class="fa fa-cloud-download"></i></a>
							
							<a style="position: absolute;top: 2px;right: 2px" href="#" onclick="excluirArquivo('<?= $id ?>', 'produto_atributo', 'foto', 'foto', '<?= $caminhoTela ?>&id=<?= $id ?>&s=cadastrar');" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Excluir Anexo"><i class="fa fa-trash"></i></a>
						<?php } ?>
					
			<?php } ?>
	</div>
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