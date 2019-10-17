<?php
if($_POST) {

	include_once "sql.php";

}

try {

	$stArtigo = Conexao::chamar()->prepare("SELECT produto_subcategoria.*,
                                	               produto_categoria.categoria categoria,
                                	               a1.descricao atributo,
                                	               a2.descricao atributo2,
                                	               a3.descricao atributo3

                                	        FROM produto_subcategoria

                                	     	LEFT JOIN produto_categoria ON produto_subcategoria.id_categoria = produto_categoria.id
                                	     	JOIN produto_tipo_atributo a1 ON produto_subcategoria.id_atributo_1 = a1.id
                                	     	JOIN produto_tipo_atributo a2 ON produto_subcategoria.id_atributo_2 = a2.id
                                	     	JOIN produto_tipo_atributo a3 ON produto_subcategoria.id_atributo_3 = a3.id

                                	        WHERE produto_subcategoria.id = :id
	                                        AND produto_subcategoria.status_registro = :status_registro");

	$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
	$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);


	

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

    <div class="form-group">
		<label for="id_categoria" class="col-sm-2 control-label">Categoria</label>
		<div class="col-sm-2 hidden-xs">
			<input type="text" name="id_categoria" id="id_categoria" value="<?= $buscaArtigo['id_categoria'] ?>" class="form-control" readonly >
		</div>
		<div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/categoria_produto.php?t=<?= $t ?>', 'Categorias')"><i class="fa fa-search"></i></button>
				</span>
				<input type="text" name="categoria" id="categoria" value="<?= $buscaArtigo['categoria'] ?>" class="form-control" readonly placeholder="Informe a categoria...">
			</div>
		</div>
	</div>


	<div class="form-group">
		<label for="id_atributo_1" class="col-sm-2 control-label">Atributo 1</label>
		<div class="col-sm-2 hidden-xs">
			<input type="text" name="id_atributo_1" id="id_atributo_1" value="<?= $buscaArtigo['id_atributo_1'] ?>" class="form-control" readonly >
		</div>
		<div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/atributo_produto.php?t=<?= $t ?>&atributo=1', 'Atributos')"><i class="fa fa-search"></i></button>
				</span>
				<input type="text" name="atributo" id="atributo" value="<?= $buscaArtigo['atributo'] ?>" class="form-control" readonly placeholder="Informe o atributo...">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="id_atributo_2" class="col-sm-2 control-label">Atributo 2</label>
		<div class="col-sm-2 hidden-xs">
			<input type="text" name="id_atributo_2" id="id_atributo_2" value="<?= $buscaArtigo['id_atributo_2'] ?>" class="form-control" readonly >
		</div>
		<div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/atributo_produto.php?t=<?= $t ?>&atributo=2', 'Atributos')"><i class="fa fa-search"></i></button>
				</span>
				<input type="text" name="atributo2" id="atributo2" value="<?= $buscaArtigo['atributo2'] ?>" class="form-control" readonly placeholder="Informe o segundo atributo...">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="id_atributo_3" class="col-sm-2 control-label">Atributo 3</label>
		<div class="col-sm-2 hidden-xs">
			<input type="text" name="id_atributo_3" id="id_atributo_3" value="<?= $buscaArtigo['id_atributo_3'] ?>" class="form-control" readonly >
		</div>
		<div class="col-sm-8">
			<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/atributo_produto.php?t=<?= $t ?>&atributo=3', 'Atributos')"><i class="fa fa-search"></i></button>
				</span>
				<input type="text" name="atributo3" id="atributo3" value="<?= $buscaArtigo['atributo3'] ?>" class="form-control" readonly placeholder="Informe o segundo atributo...">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label for="subcategoria" class="col-sm-2 control-label">Descri&ccedil;&atilde;o</label>
		<div class="col-sm-10">
			<input type="text" name="subcategoria" id="subcategoria" value="<?= $buscaArtigo['subcategoria'] ?>" class="form-control required" required placeholder="Informe a descri&ccedil;&atilde;o...">
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