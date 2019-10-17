<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT id,endereco,complemento,bairro,cep,email,telefone,telefone2,telefone3,horario_atendimento,facebook,instagram,webmail
		FROM cliente_configuracao
		WHERE status_registro = 'A'
		LIMIT 1");

	$stArtigo->execute();
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

	

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">


	<input type="hidden" name="id" id="id" value="<?= $buscaArtigo['id'] ?>"/>

	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			<input type="email" name="email" id="email" value="<?= $buscaArtigo['email'] ?>" class="form-control" placeholder="Informe o email...">
		</div>
	</div>

	<div class="form-group">
		<label for="email_formulario" class="col-sm-2 control-label">Email do formulario</label>
		<div class="col-sm-10">
			<input type="email_formulario" name="email_formulario" id="email_formulario" value="<?= $buscaArtigo['email_formulario'] ?>" class="form-control" placeholder="Informe o email do formulario...">
		</div>
	</div>


	<div class="form-group">
		<label for="horario_atendimento" class="col-sm-2 control-label">Hor&aacute;rio de atendimento</label>
		<div class="col-sm-10">
			<input type="horario_atendimento" name="horario_atendimento" id="horario_atendimento" value="<?= $buscaArtigo['horario_atendimento'] ?>" class="form-control" placeholder="Hor&aacute;rio de atendimento...">
		</div>
	</div>

	<div class="form-group">
		<label for="telefone" class="col-sm-2 control-label">Telefone</label>
		<div class="col-sm-10">
			<input type="text" name="telefone" id="telefone" value="<?= $buscaArtigo['telefone'] ?>" class="form-control" placeholder="Informe o telefone...">
		</div>
	</div>

	<div class="form-group">
		<label for="telefone2" class="col-sm-2 control-label">Telefone 2</label>
		<div class="col-sm-10">
			<input type="text" name="telefone2" id="telefone2" value="<?= $buscaArtigo['telefone2'] ?>" class="form-control" placeholder="Informe o telefone 2...">
		</div>
	</div>

	<div class="form-group">
		<label for="telefone3" class="col-sm-2 control-label">Telefone 3</label>
		<div class="col-sm-10">
			<input type="text" name="telefone3" id="telefone3" value="<?= $buscaArtigo['telefone3'] ?>" class="form-control" placeholder="Informe o telefone 3...">
		</div>
	</div>

	<div class="form-group">
		<label for="facebook" class="col-sm-2 control-label">Facebook</label>
		<div class="col-sm-10">
			<input type="text" name="facebook" id="facebook" value="<?= $buscaArtigo['facebook'] ?>" class="form-control" placeholder="Informe o facebook...">
		</div>
	</div>

	<div class="form-group" style="display: none">
		<label for="webmail" class="col-sm-2 control-label">WEBMAIL</label>
		<div class="col-sm-10">
			<input type="text" name="webmail" id="webmail" value="<?= $buscaArtigo['webmail'] ?>" class="form-control" placeholder="Informe o EMAIL...">
		</div>
	</div>

	<div class="form-group">
		<label for="instagram" class="col-sm-2 control-label">Instagram</label>
		<div class="col-sm-10">
			<input type="text" name="instagram" id="instagram" value="<?= $buscaArtigo['instagram'] ?>" class="form-control" placeholder="Informe o instagram...">
		</div>
	</div>


	<div class="form-group">
		<label for="endereco" class="col-sm-2 control-label">Endere&ccedil;o</label>
		<div class="col-sm-10">
			<input type="text" name="endereco" id="endereco" value="<?= $buscaArtigo['endereco'] ?>" class="form-control" placeholder="Informe o endere&ccedil;o...">
		</div>
	</div>

	<div class="form-group">
		<label for="bairro" class="col-sm-2 control-label">Bairro</label>
		<div class="col-sm-10">
			<input type="text" name="bairro" id="bairro" value="<?= $buscaArtigo['bairro'] ?>" class="form-control" placeholder="Informe o bairro...">
		</div>
	</div>

	<div class="form-group">
		<label for="complemento" class="col-sm-2 control-label">Complemento</label>
		<div class="col-sm-10">
			<input type="text" name="complemento" id="complemento" value="<?= $buscaArtigo['complemento'] ?>" class="form-control" placeholder="Informe o complemento...">
		</div>
	</div>

	<div class="form-group">
		<label for="complemento" class="col-sm-2 control-label">Cep</label>
		<div class="col-sm-10">
			<input type="text" name="cep" id="cep" value="<?= $buscaArtigo['cep'] ?>" class="form-control" placeholder="Informe a complemento...">
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