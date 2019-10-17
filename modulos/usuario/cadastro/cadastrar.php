<?php

if($_POST) {
	include "../../privado/sistema/classes/modelo_email.php";
	include_once "sql.php";

}

try {

	if(isset($id)){
	
		$stUser = Conexao::chamar()->prepare(" SELECT   usuario.id, 
														usuario.usuario,
														usuario.telefone,
														usuario.foto,
														usuario.celular,
														usuario.data_validade, 
														usuario.email,
														usuario.data_validade,
														usuario.status_registro

													FROM usuario
													WHERE usuario.status_registro = :status_registro 
													AND usuario.id_cliente = :id_cliente
													AND usuario.id != :proprio_id
													AND usuario.id = :id");

		$stUser->execute(array("id" => secure($id), "status_registro" => "A", "proprio_id" => $buscaAdministrador['id'], "id_cliente" => $buscaAdministrador['id_cliente'] ));
		$valor_usuario = $stUser->fetch(PDO::FETCH_ASSOC);

	} 
	


} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">
	<div class="col-md-offset-5 col-md-2" style="margin-bottom:40px;display: flex; flex-direction: column;  justify-content: center; align-items: center; height: 133px ;border: 1px solid #ccc; border-radius: 4px;">
		<img id="img-prev" style="height: 100px" class="img-responsive" src="<?= $CAMINHO ?>/imagens/<?= $idCliente ?>/<?= $valor_usuario['foto'] ?>">
			<?php if($id) { ?>
					
	    				<?php if($buscaArtigo['foto'] != "") { ?>
							<a style="position: absolute;top: 2px;left: 2px" href="<?= $CAMINHO ?>/imagens/<?= $idCliente ?>/<?= $valor_usuario['foto'] ?>" class="btn btn-info" target="_blank" data-toggle="tooltip" data-placement="left" title="Baixar Anexo"><i class="fa fa-cloud-download"></i></a>
							
							<a style="position: absolute;top: 2px;right: 2px" href="#" onclick="excluirArquivo('<?= $id ?>', 'usuario', 'foto', 'foto', '<?= $caminhoTela ?>&id=<?= $id ?>&s=cadastrar');" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Excluir Anexo"><i class="fa fa-trash"></i></a>
						<?php } ?>
					
			<?php } ?>
	</div>
	<div class="col-xs-offset-0 col-xs-12 col-sm-offset-0 col-sm-12 col-md-offset-2 col-md-8">
		

		<div class="form-group">
				<label class="col-sm-12">Nome</label>
				<div class="col-sm-12">
						<input type="text" name="usuario" id="usuario" value="<?= $valor_usuario['usuario'] ?>" class="form-control" required>
				</div>
		</div>

		<div class="form-group">
			<label  class="col-sm-12">Email</label>
			<div class="col-sm-12">
				<input type="text" name="email" id="email"   value="<?= $valor_usuario['email'] ?>" class="form-control" required>
			</div>
		</div>

		<div class="form-group">
				<label for="telefone" class="col-sm-12">Telefone</label>
				<div class="col-sm-12">
						<input type="tel" name="telefone" id="telefone" value="<?= $valor_usuario['telefone'] ?>" class="form-control telefone">
				</div>
		</div>

		<div class="form-group">
			<label  class="col-sm-12">Celular</label>
			<div class="col-sm-12">
				<input type="tel" name="celular" id="celular"   value="<?= $valor_usuario['celular'] ?>" class="form-control telefone">
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-12">Vencimento</label>
			<div class="col-sm-12">
				<div class="input-group date">
					<input type="text" name="data_validade" id="data_validade" value="<?= formata_data($valor_usuario['data_validade']) ?>" class="form-control data required" required >
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-12">Foto</label>
			<div class="col-sm-12">
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