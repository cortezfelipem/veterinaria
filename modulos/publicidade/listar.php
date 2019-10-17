<?php
if($_REQUEST['id_excluir']) {

	include_once "$root/privado/sistema/classes/includes/excluir.php";
	excluir($_REQUEST['id_excluir'], "publicidade");

}
?>

<form class="form-horizontal collapse" id="form-busca" action="<?= $caminhoTela ?>" method="POST">
	
	<div class="form-group">
		<label for="titulo" class="col-sm-2 control-label">T&itilde;tulo</label>
		<div class="col-sm-10">
			<input type="text" name="titulo" id="titulo" class="form-control" value="<?= $titulo ?>" placeholder="Informe a t&itilde;tulo...">
		</div>
	</div>

	<div class="form-group">
		<label for="data_inicial" class="col-sm-2 control-label">Data In&iacute;cial</label>
		<div class="col-sm-10 col-lg-2">
			<div class="input-group date">
				<input type="text" name="data_inicial" id="data_inicial" value="<?= formata_data($data_inicial) ?>" class="form-control data">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="data_limite" class="col-sm-2 control-label">Data Limite</label>
		<div class="col-sm-10 col-lg-2">
			<div class="input-group date">
				<input type="text" name="data_limite" id="data_limite" value="<?= formata_data($data_limite) ?>" class="form-control data" >
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
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

	$sql = "SELECT *
	          FROM publicidade
	         WHERE status_registro = :status_registro ";

	if($buscaAdministrador['master'] == "S" && $status_registro == "I") {

		$vetor["status_registro"] = "I";
		$link .= "&status_registro=I";

	} else $vetor["status_registro"] = "A";
	
	if($titulo != "") {

		$sql .= "AND titulo LIKE :titulo ";
		$vetor['titulo'] = "%$titulo%";
		$link .= "&titulo=" . urlencode($titulo);

	}
	if($data_inicial != "") {

		$sql .= "AND data_inicial = :data_inicial ";
		$vetor['data_inicial'] = formata_data_banco($data_inicial);
		$link .= "&data_inicial=" . urlencode($data_inicial);

	}

	if($data_limite != "") {

		$sql .= "AND data_limite = :data_limite ";
		$vetor['data_limite'] = formata_data_banco($data_limite);
		$link .= "&data_limite=" . urlencode($data_limite);

	}

	$stLinha = Conexao::chamar()->prepare($sql);
	$stLinha->execute($vetor);
	$qryLinha = $stLinha->fetchAll(PDO::FETCH_ASSOC);
	$totalLinha = count($qryLinha);

	if(empty($o)) $o = "titulo";
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
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=titulo&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						T&itilde;tulo
					</a>
					<?php if($o == "titulo" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "titulo" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th style="width: 150px;">
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=data_inicial&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Data In&iacute;cial
					</a>
					<?php if($o == "data_inicial" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "data_inicial" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th style="width: 220px;">
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=data_limite&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Data Limite
					</a>
					<?php if($o == "data_limite" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "data_limite" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
					<button class="btn btn-primary btn-xs pull-right" type="button" data-toggle="collapse" data-target="#form-busca" aria-expanded="false" aria-controls="form-busca">
						<i class="fa fa-search"></i><span class="hidden-xs hidden-sm"> Pesquisar</span>
					</button>
				</th>
			</tr>
			<?php
			if(count($qryArtigo)) {
			foreach($qryArtigo as $buscaArtigo) {
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
						<a href="#" class="text-info btn-lista" onclick="restaurar('publicidade', '<?= $buscaArtigo['id'] ?>', '<?= $caminhoTela ?>');" data-toggle="tooltip" data-placement="right" title="Restaurar Registro"><i class="fa fa-undo"></i></a>
					<?php } ?>
				</td>
				<td class="text-right"><?= $buscaArtigo['id'] ?></td>
				<td><?= $buscaArtigo['titulo'] ?></td>
				<td><?= formata_data($buscaArtigo['data_inicial']) ?></td>
				<td><?= formata_data($buscaArtigo['data_limite']) ?></td>
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
				<option value="titulo" <?php if($o == "titulo") echo "selected"; ?>>T&itilde;tulo</option>
				<option value="data_inicial" <?php if($o == "data_inicial") echo "selected"; ?>>Data In&iacute;cial</option>
				<option value="data_limite" <?php if($o == "data_limite") echo "selected"; ?>>Data Limite</option>
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
    						<a href="#" class="text-info btn-lista" onclick="restaurar('publicidade', '<?= $buscaArtigo['id'] ?>', '<?= $caminhoTela ?>');" data-toggle="tooltip" data-placement="right" title="Restaurar Registro"><i class="fa fa-undo"></i></a>
    					<?php } ?>
    					
    					<? if($excluir) { ?>
    					<input type="checkbox" class="marcar" name="id_excluir[]" value="<?= $buscaArtigo['id'] ?>">
    					<?php } ?>
    					
    				</div>
				</td>
			</tr>
			<tr>
                <td class="conteudo-sx-sm">
                    
                    <span class="topico-sx-sm">T&itilde;tulo</span><br>
					<td class="text-right"><?= $buscaArtigo['id'] ?></td>
					<?= $buscaArtigo['titulo'] ?>
					
                </td>
			</tr>
			<tr>
				<td class="conteudo-sx-sm">

					<span class="topico-sx-sm">Data In&iacute;cial</span><br>
					<?= formata_data($buscaArtigo['data_inicial']) ?>

				</td>
			</tr>
			<tr>
				<td class="conteudo-sx-sm">

					<span class="topico-sx-sm">Data Limite</span><br>
					<?= formata_data($buscaArtigo['data_limite']) ?>

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
		<?php if($buscaAdministrador['permissao_log'] == 'S') { ?>
		<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_listagem.php?t=<?= $t ?>', 'Registro de Logs de Acesso')" class="btn btn-info pull right"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
		<?php } ?>
		<? if($excluir) { ?>
		<button type="submit" class="btn btn-danger pull right"><i class="fa fa-trash"></i> Excluir</button>
		<?php } ?>
		<? if($cadastrar) { ?>
		<a href="<?= $caminhoTela ?>&s=cadastrar" class="btn btn-primary pull right"><i class="fa fa-plus"></i> Cadastrar</a>
		<?php } ?>
		<a href="<?= $CAMINHO ?>/inicio.php" class="btn btn-warning pull right"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>

	<nav class="visible-xs visible-sm">
		<?php if($buscaAdministrador['permissao_log'] == 'S') { ?>
		<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_listagem.php?t=<?= $t ?>', 'Registro de Logs de Acesso')" class="btn btn-info btn-block"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
		<?php } ?>
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
<?php
include "javaScript.php";