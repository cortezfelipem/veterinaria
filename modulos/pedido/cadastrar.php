<?php
if($_POST) {

	include_once "sql.php";

}



try {

	$stArtigo = Conexao::chamar()->prepare("SELECT pedido.*,
												   pedido_cadastro.*,
												   mun1.nome municipio,
												   mun2.nome municipio_entrega,
												   est1.nome estado,
												   est2.nome estado_entrega
											  FROM pedido
										 LEFT JOIN pedido_cadastro ON pedido.id_cadastro = pedido_cadastro.id
										 LEFT JOIN municipio as mun1 ON pedido_cadastro.id_municipio = mun1.id
										 LEFT JOIN municipio as mun2 ON pedido_cadastro.id_municipio_entrega = mun2.id
										 LEFT JOIN estado as est1 ON mun1.id_estado = est1.id
										 LEFT JOIN estado as est2 ON mun2.id_estado = est2.id
											 WHERE pedido.status_registro = :status_registro
											 AND   pedido.id = :id");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>


<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

	<ul class="list-group">
	   <li class="list-group-item flex-center active"> Dados do Pedido</li>

	  <li class="list-group-item flex-center">
	  	<div class="col-md-6 col-sm-12 col-xs-12">
	  		<span>N&uacute;mero do Pedido: <strong><?= $buscaArtigo['id'] ?></strong></span>
	  	</div>
	  	<div class="col-md-6 col-sm-12 col-xs-12">
	  		<span>Data do Pedido: <strong><?= formata_data_hora($buscaArtigo['data']) ?></strong></span>
	  	</div>
	  </li>


	  <li class="list-group-item flex-center">

	  	<div class="col-md-4 col-sm-12 col-xs-12">
	  		Status
	  		<select name="status" id="status" class="form-control required" required>
	  			<option value="">&raquo;&nbsp;Selecione</option>
	  			<option value="P" <?= ($buscaArtigo["status"] == "P" ? "selected" : "") ?>>Pendente</option>
	  			<option value="F" <?= ($buscaArtigo["status"] == "F" ? "selected" : "") ?>>Faturado</option>
	  			<option value="E" <?= ($buscaArtigo["status"] == "E" ? "selected" : "") ?>>Enviado</option>
	  			<option value="C" <?= ($buscaArtigo["status"] == "C" ? "selected" : "") ?>>Cancelado</option>
	  		</select>
	  		* Um e-mail ser&aacute; enviado para o cliente informando a altera&ccedil;&atilde;o no status.
	  	</div>

	  	<div class="col-md-4 col-sm-12 col-xs-12">
	  		Pagamento
	  		<input type="text" value="<?= statusPagseguro($buscaArtigo["status_pagseguro"])?>" class="form-control" disabled>
	  		* Esta informação é obtida da sicronização com Pagseguro .
	  	</div>

	  	<div class="col-md-4 col-sm-12 col-xs-12">
	  		C&oacute;d. Rastreio
	  		<input type="text" name="codigo_rastreio" id="codigo_rastreio" value="<?= $buscaArtigo['codigo_rastreio'] ?>" class="form-control"  placeholder="Informe o c&oacute;digo rastreio...">
	  		* Preencha em caso de entrega via Correios com o c&oacute;digo de rastreio fornecido.
	  	</div>

	  </li>


	  <li class="list-group-item flex-center">
	  	<div class="col-md-4 col-sm-12 col-xs-12">
	  		Frete Contratado:&nbsp;
	  		<strong><?= $buscaArtigo['codigo_rastreio'] ?></strong>
	  	</div>

	  	<div class="col-md-4 col-sm-12 col-xs-12">
	  		Nome:&nbsp;
	  		<strong><?= $buscaArtigo['nome'] ?></strong>
	  	</div>

	  	<div class="col-md-4 col-sm-12 col-xs-12">
	  		CPF / CNPJ:&nbsp;
	  		<strong><?= $buscaArtigo['cpf_cnpj'] ?></strong>
	  	</div>
	  </li>

	   <li class="list-group-item  flex-center active">Contato</li>

	   <li class="list-group-item flex-center">

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Email:&nbsp;
	  		<strong><?= $buscaArtigo['email'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Telefone:&nbsp;
	  		<strong><?= $buscaArtigo['telefone'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Celular:&nbsp;
	  		<strong><?= $buscaArtigo['telefone_celular'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Outro Telefone:&nbsp;
	  		<strong><?= $buscaArtigo['telefone_outro'] ?></strong>
	  	</div>
	  	

	  </li>

	  <li class="list-group-item flex-center active">Endereço</li>


	  <li class="list-group-item flex-center">

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Endere&ccedil;o:&nbsp;
	  		<strong><?= $buscaArtigo['endereco'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Número:&nbsp;
	  		<strong><?= $buscaArtigo['numero'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Bairro:&nbsp;
	  		<strong><?= $buscaArtigo['bairro'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Complemento:&nbsp;
	  		<strong><?= $buscaArtigo['complemento'] ?></strong>
	  	</div>

	  </li>

	  <li class="list-group-item flex-center">

	 

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Cep:&nbsp;
	  		<strong><?= $buscaArtigo['cep'] ?></strong>
	  	</div>


	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Municipio:&nbsp;
	  		<strong><?= $buscaArtigo['municipio'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Estado:&nbsp;
	  		<strong><?= $buscaArtigo['estado'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		País:&nbsp;
	  		<strong>Brasil</strong>
	  	</div>

	  </li>


	  <li class="list-group-item flex-center active">Endereço de Entrega</li>


	  <li class="list-group-item flex-center">

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Endere&ccedil;o:&nbsp;
	  		<strong><?= $buscaArtigo['endereco_entrega'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Número:&nbsp;
	  		<strong><?= $buscaArtigo['numero_entrega'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Bairro:&nbsp;
	  		<strong><?= $buscaArtigo['bairro_entrega'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Complemento:&nbsp;
	  		<strong><?= $buscaArtigo['complemento_entrega'] ?></strong>
	  	</div>

	  </li>

	  <li class="list-group-item flex-center">

	 

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Cep:&nbsp;
	  		<strong><?= $buscaArtigo['cep_entrega'] ?></strong>
	  	</div>


	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Municipio:&nbsp;
	  		<strong><?= $buscaArtigo['municipio_entrega'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		Estado:&nbsp;
	  		<strong><?= $buscaArtigo['estado_entrega'] ?></strong>
	  	</div>

	  	<div class="col-md-3 col-sm-12 col-xs-12">
	  		País:&nbsp;
	  		<strong>Brasil</strong>
	  	</div>

	  </li>




	  
	</ul>

	
	

	

	




	

	<p class="clearfix"></p>

	<nav class="pull-right hidden-xs hidden-sm">
		<?php if($id && $buscaAdministrador['permissao_log'] == 'S') { ?>
		<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_cadastro.php?t=<?= $t ?>&id=<?= $id ?>', 'Registro de Logs de Acesso')" class="btn btn-info pull right"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
		<?php } ?>
		<a href="impressao/pedido.php?id=<?= $id ?>" target="_blank" class="btn btn-primary pull right"><i class="fa fa-print"></i> Gerar Impress&atilde;o</a>
		<button type="submit" class="btn btn-success pull right"><i class="fa fa-check"></i> Finalizar</button>
		<a href="<?= $caminhoTela ?>" class="btn btn-warning pull right"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>

	<nav class="visible-xs visible-sm">
		<?php if($id && $buscaAdministrador['permissao_log'] == 'S') { ?>
		<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_cadastro.php?t=<?= $t ?>&id=<?= $id ?>', 'Registro de Logs de Acesso')" class="btn btn-info btn-block"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
		<?php } ?>
		<a href="impressao/pedido.php?id=<?= $id ?>" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-print"></i> Gerar Impress&atilde;o</a>
		<button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Finalizar</button>
		<a href="<?= $caminhoTela ?>" class="btn btn-warning btn-block"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>
	
	<p class="clearfix"></p>
	
</form>