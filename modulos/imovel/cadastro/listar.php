<?php
if($_REQUEST['id_excluir']) {

	include_once "$root/privado/sistema/classes/includes/excluir.php";
	excluir($_REQUEST['id_excluir'], "imovel");

}
?>

<form class="form-horizontal collapse" id="form-busca" action="<?= $caminhoTela ?>" method="POST">

	<div class="form-group">
		<label for="logradouro" class="col-sm-2 control-label">Logradouro</label>
		<div class="col-sm-10">
			<input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $logradouro ?>" placeholder="Informe o logradouro...">
		</div>
	</div>

	<div class="form-group">
		<label for="bairro" class="col-sm-2 control-label">Bairro</label>
		<div class="col-sm-10">
			<input type="text" name="bairro" id="bairro" class="form-control" value="<?= $bairro ?>" placeholder="Informe o bairro...">
		</div>
	</div>

	<div class="form-group">
		<label for="cidade" class="col-sm-2 control-label">Cidade</label>
		<div class="col-sm-10">
			<input type="text" name="cidade" id="cidade" class="form-control" value="<?= $cidade ?>" placeholder="Informe a cidade...">
		</div>
	</div>

	
	<div class="form-group">
		<label for="estado" class="col-sm-2 control-label">Estado</label>
		<div class="col-sm-10">
			<input type="text" name="estado" id="estado" class="form-control" value="<?= $estado ?>" placeholder="Informe o estado...">
		</div>
	</div>

	<div class="form-group">
		<label for="cep" class="col-sm-2 control-label">CEP</label>
		<div class="col-sm-10">
			<input type="text" name="cep" id="cep" class="form-control" value="<?= $cep ?>" placeholder="Informe o cep...">
		</div>
	</div>
	
	<?php if($buscaAdministrador['master'] == "S") { ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<label class="radio-inline">
				<input type="radio" name="status_registro" id="status_registro_0" value="A" <? if(empty($status_registro) || $status_registro == "A") echo "checked"; ?>> Registros Ativos
			</label>
			<label class="radio-inline">
				<input type="radio" name="status_registro" id="status_registro_1" value="I" <? if($status_registro == "I") echo "checked"; ?>> Registros Exclu&iacute;dos
			</label>
		</div>
	</div>
	<?php } ?>
	
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="hidden" name="acao" value="cadastrar">
			<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Pesquisar</button>
			<button type="button" data-toggle="collapse" data-target="#form-busca" class="btn btn-warning"><i class="fa fa-ban"></i> Cancelar</button>
		</div>
	</div>
	
</form>

<form id="form-lista" name="form_lista" action="<?= $caminhoTela ?>" method="POST" onsubmit="return excluirVarios();">
	
	<?php
	$pagina = isset($p) ? $p : 1;
	$max = 10;
	$inicio = $max * ($pagina - 1);

	$link = $caminhoTela;

	try {

	$sql = "SELECT imovel.*, municipio.nome cidade, estado.nome estado
			  FROM imovel
		 LEFT JOIN municipio 
				ON municipio.id = imovel.id_municipio
		 LEFT JOIN estado
				ON municipio.id_estado = estado.id
			 WHERE imovel.status_registro = :status_registro ";

	if($buscaAdministrador['master'] == "S" && $status_registro == "I") {

		$vetor["status_registro"] = "I";
		$link .= "&status_registro=I";

	} else $vetor["status_registro"] = "A";


	if($logradouro != "") {

		$sql .= "AND imovel.logradouro LIKE :logradouro ";
		$vetor['logradouro'] = "%$logradouro%";
		$link .= "&logradouro=" . urlencode($logradouro);

	}

	if($bairro != "") {

		$sql .= "AND imovel.bairro LIKE :bairro ";
		$vetor['bairro'] = "%$bairro%";
		$link .= "&bairro=" . urlencode($bairro);

	}

	if($cep != "") {

		$sql .= "AND imovel.cep LIKE :cep ";
		$vetor['cep'] = "%$cep%";
		$link .= "&cep=" . urlencode($cep);

	}

	if($cidade!= "") {

		$sql .= "AND municipio.nome LIKE :cidade ";
		$vetor['cidade'] = "%$cidade%";
		$link .= "&cidade=" . urlencode($cidade);

	}

	if($estado!= "") {

		$sql .= "AND estado.nome LIKE :estado ";
		$vetor['estado'] = "%$estado%";
		$link .= "&estado=" . urlencode($estado);

	}

	$stLinha = Conexao::chamar()->prepare($sql);
	$stLinha->execute($vetor);
	$qryLinha = $stLinha->fetchAll(PDO::FETCH_ASSOC);
	$totalLinha = count($qryLinha);

	if(empty($o)) $o = "logradouro";
	if(empty($d)) $d = "ASC";

	$sql .= "ORDER BY $o $d LIMIT $inicio, $max";

	$stArtigo = Conexao::chamar()->prepare($sql);
	$stArtigo->execute($vetor);
	$qryArtigo = $stArtigo->fetchAll(PDO::FETCH_ASSOC);
	
	?>
	<div class="table-responsive hidden-sm hidden-xs">
	
		<table class="table table-striped table-hover table-bordered table-condensed">
			<tr class="active">
				<th style="width: 90px;">
					<?php if(empty($status_registro) || $status_registro == "A") { ?>
					<input type="checkbox" name="marcar" onchange="marcarDesmarcar(this)" data-toggle="tooltip" data-placement="right" title="Marcar / Desmarcar todos">
					<?php } ?>
				</th>
				<th style="width: 90px;">
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=id&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						C&oacute;digo
					</a>
					<?php if($o == "id" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "id" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=logradouro&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Logradouro
					</a>
					<?php if($o == "logradouro" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "logradouro" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=numero&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Numero
					</a>
					<?php if($o == "numero" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "numero" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=bairro&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Bairro
					</a>
					<?php if($o == "bairro" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "bairro" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=id_municipio&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Cidade
					</a>
					<?php if($o == "id_municipio" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "id_municipio" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>

				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=estado&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Estado
					</a>
					<?php if($o == "estado" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "estado" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
					<button class="btn btn-primary btn-xs pull-right" type="button" data-toggle="collapse" data-target="#form-busca" aria-expanded="false" aria-controls="form-busca">
						<i class="fa fa-search"></i><span class="hidden-xs hidden-sm"> Pesquisar</span>
					</button>
				</th>
			</tr>
			<?php
			if(count($qryArtigo)) {
			foreach($qryArtigo as $buscaArtigo) {
				$idmunicipio = $buscaArtigo['id_municipio'];

				$stCidade = Conexao::chamar()->prepare("SELECT *
															FROM municipio
															WHERE status_registro = 'A'
															AND id = :id");

				$stCidade->execute(array("id" => $idmunicipio));
				$buscaCidade = $stCidade->fetch(PDO::FETCH_ASSOC);


			?>
			<tr>
				<td class="text-center">
					<?php if($buscaArtigo['status_registro'] == "A") { ?>
						<? if($excluir) { ?>
						<input type="checkbox" class="marcar" name="id_excluir[]" value="<?= $buscaArtigo['id'] ?>">
						<a href="#" class="text-danger btn-lista" onclick="excluir('<?= $caminhoTela ?>&id_excluir=<?= $buscaArtigo['id'] ?>');" data-toggle="tooltip" data-placement="right" title="Excluir Registro"><i class="fa fa-trash"></i></a>
						<?php } ?>
						<? if($cadastrar) { ?>
						<a href="<?= $caminhoTela ?>&id=<?= $buscaArtigo['id'] ?>&s=cadastrar" class="btn-lista" data-toggle="tooltip" data-placement="right" title="Editar Registro"><i class="fa fa-pencil"></i></a>
						<?php } ?>
					<?php } else { ?>
						<a href="#" class="text-info btn-lista" onclick="restaurar('imovel', '<?= $buscaArtigo['id'] ?>', '<?= $caminhoTela ?>');" data-toggle="tooltip" data-placement="right" title="Restaurar Registro"><i class="fa fa-undo"></i></a>
					<?php } ?>
				</td>
				<td class="text-right"><?= $buscaArtigo['id'] ?></td>
				<td><?= $buscaArtigo['logradouro'] ?></td>
				<td><?= $buscaArtigo['numero'] ?></td>
				<td><?= $buscaArtigo['bairro'] ?></td>
				<td><?= $buscaCidade['nome']?></td>
				<td><?= $buscaArtigo['estado']?></td>
			</tr>
			<?php }} ?>
		</table>
		
	</div>
	
	<div class="visible-xs visible-sm">
	
        <div class="div-sup-sx-sm">
	   
	        <?php if(empty($status_registro) || $status_registro == "A") { ?>
			<input type="checkbox" name="marcar" onchange="marcarDesmarcar(this)"  data-placement="right" title="Marcar / Desmarcar todos" class="checkbox-sx-sm">
			<?php } ?>
			
            <button class="btn btn-primary btn-xs pull-right lupa-sx-sm" type="button" data-toggle="collapse" data-target="#form-busca" aria-expanded="false" aria-controls="form-busca" >
				<i class="fa fa-search"></i><span class="hidden-xs hidden-sm"> Pesquisar</span>
            </button>
			
    		<select name="d" id="d" class="form-control input-sm select_direcao" onchange="direcao(this.value)">
				<option value="ASC" <?php if($d == "ASC") echo "selected"; ?>>Crescente</option>
				<option value="DESC" <?php if($d == "DESC") echo "selected"; ?>>Decrescente</option>
			</select>
			
    		<select name="o" id="o" class="form-control input-sm select_ordem" onchange="ordem(this.value)">
				<option value="id" <?php if($o == "id") echo "selected"; ?>>C&oacute;digo</option>
				<option value="logradouro" <?php if($o == "logradouro") echo "selected"; ?>>T&iacute;tulo</option>
			</select>
			
	   </div>
	
		<?php
		if(count($qryArtigo)) {
		foreach($qryArtigo as $buscaArtigo) {
		?>
		<table class="tb-sx-sm">
			<tr>
				<td class="td-sx-sm">
				    
				    <div style="float: left; padding-top: 4px;">
				        <?= $buscaArtigo['id'] ?>
				    </div>
				    
				    <div class="div-btn-sx-sm">
    					
    					<?php if($buscaArtigo['status_registro'] == "A") { ?>
    						<? if($excluir) { ?>
    						<a href="#" class="text-danger btn-lista" onclick="excluir('<?= $caminhoTela ?>&id_excluir=<?= $buscaArtigo['id'] ?>');" data-toggle="tooltip" data-placement="right" title="Excluir Registro"><i class="fa fa-trash"></i></a>
    						<?php } ?>
    						<? if($cadastrar) { ?>
    						<a href="<?= $caminhoTela ?>&id=<?= $buscaArtigo['id'] ?>&s=cadastrar" class="btn-lista" data-toggle="tooltip" data-placement="right" title="Editar Registro"><i class="fa fa-pencil"></i></a>
    						<?php } ?>
    					<?php } else { ?>
    						<a href="#" class="text-info btn-lista" onclick="restaurar('imovel', '<?= $buscaArtigo['id'] ?>', '<?= $caminhoTela ?>');" data-toggle="tooltip" data-placement="right" title="Restaurar Registro"><i class="fa fa-undo"></i></a>
    					<?php } ?>
    					
    					<? if($excluir) { ?>
    					<input type="checkbox" class="marcar" name="id_excluir[]" value="<?= $buscaArtigo['id'] ?>">
    					<?php } ?>
    					
    				</div>
				</td>
			</tr>
			<tr>
                <td class="conteudo-sx-sm">
                    <span class="topico-sx-sm">T&iacute;tulo</span><br>
					<?= $buscaArtigo['logradouro'] ?>
                </td>
			</tr>
		</table>
		<?php }} ?>
		
	</div>

	<?php
	if(count($qryArtigo) < 1)
	   echo alerta("warning", "<i class=\"fa fa-warning\"></i> Nenhum registro localizado.");

	$link = $link . "&o=$o&d=$d";

	include_once "$root/privado/sistema/classes/includes/paginacao.php";
	paginacao($pagina, $totalLinha, $max, $link);

	} catch (PDOException $e) {

		echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar os registros.");
		echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

	}

	?>
	
	<p class="clearfix"></p>
	
	<nav class="pull-right hidden-xs hidden-sm">
		<? if($excluir) { ?>
		<button type="submit" class="btn btn-danger pull right"><i class="fa fa-trash"></i> Excluir</button>
		<?php } ?>
		<? if($cadastrar) { ?>
		<a href="<?= $caminhoTela ?>&s=cadastrar" class="btn btn-primary pull right"><i class="fa fa-plus"></i> Cadastrar</a>
		<?php } ?>
		<a href="<?= $CAMINHO ?>/inicio.php" class="btn btn-warning pull right"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>

	<nav class="visible-xs visible-sm">
		<? if($excluir) { ?>
		<button type="submit" class="btn btn-danger btn-block"><i class="fa fa-trash"></i> Excluir</button>
		<?php } ?>
		<? if($cadastrar) { ?>
		<a href="<?= $caminhoTela ?>&s=cadastrar" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Cadastrar</a>
		<?php } ?>
		<a href="<?= $CAMINHO ?>/inicio.php" class="btn btn-warning btn-block"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>
	
	<p class="clearfix"></p>
	
</form>
