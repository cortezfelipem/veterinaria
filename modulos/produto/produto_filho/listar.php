<style type="	">



button.accordion:after {
    content: '\002B';
    color: #777;
    font-weight: bold;
    float: right;
    margin-left: 5px;
}

button.accordion.active:after {
    content: "\2212";
}


</style>



<?php
if($_REQUEST['id_excluir']) {

	include_once "$root/privado/sistema/classes/includes/excluir.php";
	excluir($_REQUEST['id_excluir'], "produto_filho");

}
?>

<form class="form-horizontal collapse" id="form-busca" action="<?= $caminhoTela ?>" method="POST">




	<div class="form-group">
		<label for="id_marca" class="col-sm-2 control-label">Marca</label>
		<div class="col-sm-2 hidden-xs">
			<input type="text" name="id_marca" id="id_marca" value="<?= $id_marca ?>" class="form-control" readonly >
		</div>
		<div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/marca_produto.php?t=<?= $t ?>', 'Marcas')"><i class="fa fa-search"></i></button>
				</span>
				<input type="text" name="marca" id="marca" value="<?= $marca ?>" class="form-control" readonly placeholder="Informe a marca...">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label for="id_categoria" class="col-sm-2 control-label">Categoria</label>
		<div class="col-sm-2 hidden-xs">
			<input type="text" name="id_categoria" id="id_categoria" value="<?= $id_categoria ?>" class="form-control" readonly >
		</div>
		<div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/categoria_produto.php?t=<?= $t ?>', 'Categorias')"><i class="fa fa-search"></i></button>
				</span>
				<input type="text" name="categoria" id="categoria" value="<?= $categoria ?>" class="form-control" readonly placeholder="Informe a categoria...">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label for="id_subcategoria" class="col-sm-2 control-label">Subcategoria</label>
		<div class="col-sm-2 hidden-xs">
			<input type="text" name="id_subcategoria" id="id_subcategoria" value="<?= $id_subcategoria ?>" class="form-control required" required readonly>
		</div>
		<div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/subcategoria_produto.php?t=<?= $t ?>&id_categoria='+jQuery('#id_categoria').val(), 'Subcategorias')"><i class="fa fa-search"></i></button>
				</span>
				<input type="text" name="subcategoria" id="subcategoria" value="<?= $subcategoria ?>" class="form-control required" required readonly placeholder="Informe a subcategoria...">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label for="titulo" class="col-sm-2 control-label">T&iacute;tulo</label>
		<div class="col-sm-10">
			<input type="text" name="titulo" id="titulo" class="form-control" value="<?= $titulo ?>" placeholder="Informe a descri&ccedil;&atilde;o...">
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

<form id="form-lista" name="form_lista" action="<?= $caminhoTela ?>" method="POST" >
	
	<?php
	$pagina = isset($p) ? $p : 1;
	$max = 10;
	$inicio = $max * ($pagina - 1);

	$link = $caminhoTela;

	try {

	$sql = "SELECT produto_pai.*,
	               produto_categoria.categoria categoria,
	               produto_subcategoria.subcategoria subcategoria,
	               produto_marca.marca marca
	          FROM produto_pai
	     LEFT JOIN produto_categoria ON produto_pai.id_categoria = produto_categoria.id
	     LEFT JOIN produto_subcategoria ON produto_pai.id_subcategoria = produto_subcategoria.id
	     LEFT JOIN produto_marca ON produto_pai.id_marca = produto_marca.id
	         WHERE produto_pai.status_registro = :status_registro ";

	if($buscaAdministrador['master'] == "S" && $status_registro == "I") {

		$vetor["status_registro"] = "I";
		$link .= "&status_registro=I";

	} else $vetor["status_registro"] = "A";
	
	if($id_categoria != "") {

		$sql .= "AND produto_pai.id_categoria = :id_categoria ";
		$vetor['id_categoria'] = $id_categoria;
		$link .= "&id_categoria=" . urlencode($id_categoria);

	}
	
	if($id_subcategoria != "") {

		$sql .= "AND produto_pai.id_subcategoria = :id_subcategoria ";
		$vetor['id_subcategoria'] = $id_subcategoria;
		$link .= "&id_subcategoria=" . urlencode($id_subcategoria);

	}

	if($id_marca != "") {

		$sql .= "AND produto_pai.id_marca = :id_marca ";
		$vetor['id_marca'] = $id_marca;
		$link .= "&id_marca=" . urlencode($id_marca);

	}
	
	if($titulo != "") {

		$sql .= "AND produto_pai.titulo LIKE :titulo ";
		$vetor['titulo'] = "%$titulo%";
		$link .= "&titulo=" . urlencode($titulo);

	}

	$stLinha = Conexao::chamar()->prepare($sql);
	$stLinha->execute($vetor);
	$qryLinha = $stLinha->fetchAll(PDO::FETCH_ASSOC);
	$totalLinha = count($qryLinha);

	if(empty($o)) $o = "produto_pai.titulo";
	if(empty($d)) $d = "ASC";

	$sql .= "ORDER BY $o $d LIMIT $inicio, $max";

	$stArtigo = Conexao::chamar()->prepare($sql);
	$stArtigo->execute($vetor);
	$qryArtigo = $stArtigo->fetchAll(PDO::FETCH_ASSOC);

	?>
	<div class="table-responsive hidden-sm hidden-xs" id="lista_produto">
	
		<table class="table table-striped table-hover table-bordered table-condensed" style="border-collapse:collapse;">
			<thead>
			<tr class="active">
				<th style="width: 50px;">
					<!--<?php if(empty($status_registro) || $status_registro == "A") { ?>
					<input type="checkbox" name="marcar" onchange="marcarDesmarcar(this)" data-toggle="tooltip" data-placement="right" title="Marcar / Desmarcar todos">
					<?php } ?>-->
				</th>
				<th style="width: 90px;">
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=produto_pai.id&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						C&oacute;digo
					</a>
					<?php if($o == "produto_pai.id" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "produto_pai.id" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=produto_pai.titulo&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						T&iacute;tulo
					</a>
					<?php if($o == "produto_pai.titulo" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "produto_pai.titulo" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=produto_marca.marca&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Marca
					</a>
					<?php if($o == "produto_marca.marca" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "produto_marca.marca" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=produto_categoria.categoria&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Categoria
					</a>
					<?php if($o == "produto_categoria.categoria" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "produto_categoria.categoria" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				</th>
				<th>
					<a href="<?= $link ?>&p=<?= $pagina ?>&o=produto_subcategoria.subcategoria&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>" data-toggle="tooltip" data-placement="top" title="Clique para ordenar">
						Subcategoria
					</a>
					<?php if($o == "produto_subcategoria.subcategoria" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
					<?php if($o == "produto_subcategoria.subcategoria" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				    <button class="btn btn-primary btn-xs pull-right" type="button" data-toggle="collapse" data-target="#form-busca" aria-expanded="false" aria-controls="form-busca">
						<i class="fa fa-search"></i><span class="hidden-xs hidden-sm"> Pesquisar</span>
					</button>
				</th>
			</tr>
			</thead>
			<?php
			if(count($qryArtigo)) {

			foreach($qryArtigo as $key => $buscaArtigo) {

			$qry = Conexao::chamar()->prepare("
												SELECT produto_filho.*
												FROM produto_filho
												WHERE produto_filho.id_produto_pai = :id
												AND produto_filho.status_registro = :status_registro
												ORDER BY id
											 ");

			$qry->bindValue(":status_registro", "A", PDO::PARAM_STR);
			$qry->bindValue(":id", $buscaArtigo['id'] , PDO::PARAM_INT);

    		$qry->execute();
            $lista = $qry->fetchAll(PDO::FETCH_ASSOC);

			?>
			<tr>
				<? if(count($lista) > 0){ ?>
				<td class="text-center" data-toggle="collapse" data-target="#tabela_<?= $buscaArtigo['id']?>" style="cursor: pointer;">
					<button type="button" class="btn btn-sm btn-primary" style="border-radius: 50%; min-width: 34px"><?=count($lista)?></button>
				</td>
				<? } else { ?>
					<td class="text-center" style="cursor: pointer;"> 
						<a href="<?= $caminhoTela ?>&s=cadastro&id_prod=<?=$buscaArtigo['id']?>" class="btn btn-sm btn-default" style="border-radius: 50%; min-width: 34px"><i class="fa fa-plus" aria-hidden="true"></i></a>
					</td>
				<? } ?>
				<td class="text-center" data-toggle="collapse" data-target="#tabela_<?= $buscaArtigo['id']?>" style="cursor: pointer;">
					<?= $buscaArtigo['id'] ?>
				</td>
				<td data-toggle="collapse" data-target="#tabela_<?= $buscaArtigo['id']?>" style="cursor: pointer;">
					<?= $buscaArtigo['titulo'] ?>
				</td>
				<td data-toggle="collapse" data-target="#tabela_<?= $buscaArtigo['id']?>" style="cursor: pointer;">
					<?= $buscaArtigo['marca'] ?>
				</td>
				<td  data-toggle="collapse" data-target="#tabela_<?= $buscaArtigo['id']?>" style="cursor: pointer;">
					<?= $buscaArtigo['categoria'] ?>
				</td>
				<td><?= (empty($buscaArtigo['id_subcategoria']) ? "<span class='text-muted small'><em>N&atilde;o Informada</em><span>" : $buscaArtigo['subcategoria']) ?></td>
			</tr>

			
			 <tr>
			 	<td colspan="6"  class="hiddenRow">
			 		<div id="tabela_<?= $buscaArtigo['id']?>" class="accordian-body collapse">
			 			<div class="table-bordered table-striped">
			 				<table class="table table-striped">
			 					<?
									if(count($lista) > 0){
								?>
					 			<thead>
					 				<tr>
					 					<th style="width: 5%"  class="text-center">
					 						<a href="<?= $caminhoTela ?>&s=cadastro&id_prod=<?=$buscaArtigo['id']?>" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Adicionar Registro">
												<i class="fa fa-plus" aria-hidden="true"></i>
											</a></th>
					 					<th style="width: 5%" class="text-center">#</th>
										<th style="width: 15%" class="text-center">Filho</th>
										<th style="width: 5%" class="text-center">Estoque</th>
										<th style="width: 5%" class="text-center">Promocao</th>
										<th style="width: 5%" class="text-center">Destaque</th>
					 				</tr>
					 			</thead>
					 			 <? } ?>    
					 			<tbody>
					 			<?
					 				foreach ($lista as $key => $item) {
								?>
									<tr>	
										<td style="width: 5%" class="text-center">

											<? if($excluir) { ?>
					    							<a href="#" class="btn btn-sm btn-danger" style="margin-right: 10px" onclick="excluir('<?= $caminhoTela ?>&id_excluir=<?= $item['id'] ?>');" title="Excluir Registro">
					    								<i class="fa fa-trash"></i>
					    							</a>
					    					<?php } ?>
											<a href="<?= $caminhoTela ?>&id=<?= $item['id'] ?>&s=cadastro" class="btn btn-sm btn-default"  title="Editar Registro">
												<i class="fa fa-search" aria-hidden="true"></i>
											</a>
										</td>
										<td style="width: 5%" class="text-center"><?= $item['id']?></td>
										<td style="width: 15%" class="text-center"><?= $item['titulo']?></td>
										<td style="width: 5%" class="text-center"><?= $item['estoque']?></td>
										<td style="width: 5%" class="text-center"><?= $item['promocao']?></td>
										<td style="width: 5%" class="text-center"><?= $item['destaque']?></td>
									</tr>
								<? } ?>
									
					 			</tbody>
			 				</table>
			 			</div>
			 		</div>
			 	</td>
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
				<option value="produto_pai.id" <?php if($o == "produto_pai.id") echo "selected"; ?>>C&oacute;digo</option>
				<option value="produto_pai.titulo" <?php if($o == "produto_pai.titulo") echo "selected"; ?>>T&iacute;tulo</option>
				<option value="produto_marca.marca" <?php if($o == "produto_marca.marca") echo "selected"; ?>>Marca</option>
				<option value="produto_categoria.categoria" <?php if($o == "produto_categoria.categoria") echo "selected"; ?>>Categoria</option>
				<option value="produto_subcategoria.subcategoria" <?php if($o == "produto_subcategoria.subcategoria") echo "selected"; ?>>Subcategoria</option>
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
				    <!--
				    <div class="div-btn-sx-sm">
    					
    					<?php if($buscaArtigo['status_registro'] == "A") { ?>
    						<? if($excluir) { ?>
    						<a href="#" class="text-danger btn-lista" onclick="excluir('<?= $caminhoTela ?>&id_excluir=<?= $buscaArtigo['id'] ?>');" data-toggle="tooltip" data-placement="right" title="Excluir Registro"><i class="fa fa-trash"></i></a>
    						<?php } ?>
    						<? if($cadastrar) { ?>
    						<a href="<?= $caminhoTela ?>&id=<?= $buscaArtigo['id'] ?>&s=cadastrar" class="btn-lista" data-toggle="tooltip" data-placement="right" title="Editar Registro"><i class="fa fa-pencil"></i></a>
    						<?php } ?>
    					<?php } else { ?>
    						<a href="#" class="text-info btn-lista" onclick="restaurar('produto', '<?= $buscaArtigo['id'] ?>', '<?= $caminhoTela ?>');" data-toggle="tooltip" data-placement="right" title="Restaurar Registro"><i class="fa fa-undo"></i></a>
    					<?php } ?>
    					
    					<? if($excluir) { ?>
    					<input type="checkbox" class="marcar" name="id_excluir[]" value="<?= $buscaArtigo['id'] ?>">
    					<?php } ?>
    					
    				</div>
    			-->
				</td>
			</tr>
			<tr>
                <td class="conteudo-sx-sm">
                    
                    <span class="topico-sx-sm">T&iacute;tulo</span><br>
					<?= $buscaArtigo['titulo'] ?>
					
                </td>
			</tr>
			<tr>
                <td class="conteudo-sx-sm">
                    
                    <span class="topico-sx-sm">Marca</span><br>
					<?= $buscaArtigo['marca'] ?>
					
                </td>
			</tr>
			<tr>
                <td class="conteudo-sx-sm">
                    
                    <span class="topico-sx-sm">Categoria</span><br>
					<?= $buscaArtigo['categoria'] ?>
					
                </td>
			</tr>
			<tr>
                <td class="conteudo-sx-sm">
                    
                    <span class="topico-sx-sm">Subcategoria</span><br>
					<?= (empty($buscaArtigo['id_subcategoria']) ? "<span class='text-muted small'><em>N&atilde;o Informada</em><span>" : $buscaArtigo['subcategoria']) ?>
					
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
		<!--
		<?php if($buscaAdministrador['permissao_log'] == 'S') { ?>
		<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_listagem.php?t=<?= $t ?>', 'Registro de Logs de Acesso')" class="btn btn-info pull right"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
		<?php } ?>
		<? if($excluir) { ?>
		<button type="submit" class="btn btn-danger pull right"><i class="fa fa-trash"></i> Excluir</button>
		<?php } ?>-->
		<? if($cadastrar) { ?>
		<a href="<?= $caminhoTela ?>&s=cadastro" class="btn btn-primary pull right"><i class="fa fa-plus"></i> Cadastrar</a>
		<?php } ?>
	    
		<a href="<?= $CAMINHO ?>/inicio.php" class="btn btn-warning pull right"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>

	<nav class="visible-xs visible-sm">
		<!--<?php if($buscaAdministrador['permissao_log'] == 'S') { ?>
		<a href="#" onclick="popup('<?= $CAMINHO ?>/popup/log/log_listagem.php?t=<?= $t ?>', 'Registro de Logs de Acesso')" class="btn btn-info btn-block"><i class="fa fa-bar-chart"></i> Log de Acesso</a>
		<?php } ?>
		<? if($excluir) { ?>
		<button type="submit" class="btn btn-danger btn-block"><i class="fa fa-trash"></i> Excluir</button>
		<?php } ?>-->
		<? if($cadastrar) { ?>
		<a href="<?= $caminhoTela ?>&s=cadastro" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Cadastrar</a>
		<?php } ?>
		<a href="<?= $CAMINHO ?>/inicio.php" class="btn btn-warning btn-block"><i class="fa fa-ban"></i> Cancelar</a>
	</nav>
	
	<p class="clearfix"></p>
	
</form>
<?php
include "javaScript.php";
?>

<style type="text/css">
    .hiddenRow {
    padding: 0 !important;
}
.topo-azul{
	background: #286090;
	color: #fff
}
  </style>
