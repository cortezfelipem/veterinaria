<?php
if($_POST) {

	include_once "sql.php";

}

try {

	if($id) {

		$stArtigo = Conexao::chamar()->prepare("SELECT produto_pai.*,
                                    	               produto_categoria.categoria categoria,
                                    	               produto_marca.marca marca,
                                    	               produto_subcategoria.subcategoria subcategoria
                                    	          FROM produto_pai
                                    	     LEFT JOIN produto_marca ON produto_pai.id_marca = produto_marca.id
                                    	     LEFT JOIN produto_categoria ON produto_pai.id_categoria = produto_categoria.id
                                    	     LEFT JOIN produto_subcategoria ON produto_pai.id_subcategoria = produto_subcategoria.id
                                    	         WHERE produto_pai.id = :id
    	                                           AND produto_pai.status_registro = :status_registro");

		$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
		$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

	} else {

		$stArtigo = Conexao::chamar()->prepare("SELECT produto_categoria.id id_categoria,
                                                       produto_categoria.categoria categoria,
                                                       produto_marca.id id_marca,
                                                       produto_marca.marca marca,
                                                       produto_subcategoria.id id_subcategoria,
                                                       produto_subcategoria.subcategoria subcategoria 
                                                  FROM produto_categoria 
                                             LEFT JOIN produto_subcategoria ON produto_categoria.id = produto_subcategoria.id_categoria
                                             LEFT JOIN produto_marca ON produto_marca.id = :id_marca
                                                 WHERE produto_categoria.id = :id
                                                   AND produto_subcategoria.id = :id_subcategoria
                                                   AND produto_marca.id = :id_marca");

		$stArtigo->execute(array("id" => $id_categoria, "id_subcategoria" => $id_subcategoria , "id_marca" => $id_marca));
		$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

	}

	if($_REQUEST['id_excluir']) {

		include_once "$root/privado/sistema/classes/includes/excluir.php";
		excluir($_REQUEST['id_excluir'], "produto_pai");

	}

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}



?>
	<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">
	


			<div  class="col-md-12" id="geral">

				<div class="form-group">
					<label for="id_marca" class="col-sm-2 control-label">Marca</label>
					<div class="col-sm-2 hidden-xs">
						<input type="text" name="id_marca" id="id_marca" value="<?= $buscaArtigo["id_marca"] ?>" class="form-control required" required readonly >
					</div>
					<div class="col-sm-8">
						<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/marca_produto.php?t=<?= $t ?>', 'Marcas')"><i class="fa fa-search"></i></button>
						</span>
							<input type="text" name="marca" id="marca" value="<?= $buscaArtigo["marca"] ?>" class="form-control required" required readonly placeholder="Informe a marca...">
						</div>
					</div>
				</div>


				<div class="form-group">
					<label for="id_categoria" class="col-sm-2 control-label">Categoria</label>
					<div class="col-sm-2 hidden-xs">
						<input type="text" name="id_categoria" id="id_categoria" value="<?= $buscaArtigo["id_categoria"] ?>" class="form-control required" required readonly >
					</div>
					<div class="col-sm-8">
						<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/categoria_produto.php?t=<?= $t ?>', 'Categorias')"><i class="fa fa-search"></i></button>
						</span>
							<input type="text" name="categoria" id="categoria" value="<?= $buscaArtigo["categoria"] ?>" class="form-control required" required readonly placeholder="Informe a categoria...">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="id_subcategoria" class="col-sm-2 control-label">Subcategoria</label>
					<div class="col-sm-2 hidden-xs">
						<input type="text" name="id_subcategoria" id="id_subcategoria" value="<?= $buscaArtigo["id_subcategoria"] ?>" class="form-control" readonly>
					</div>
					<div class="col-sm-8">
						<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/subcategoria_produto.php?t=<?= $t ?>&id_categoria='+jQuery('#id_categoria').val(), 'Subcategorias')"><i class="fa fa-search"></i></button>
						</span>
							<input type="text" name="subcategoria" id="subcategoria" value="<?= $buscaArtigo["subcategoria"] ?>" class="form-control" readonly placeholder="Informe a subcategoria...">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Titulo</label>
					<div class="col-sm-10">
						<input type="text" name="titulo" id="titulo" value="<?= $buscaArtigo['titulo'] ?>" class="form-control required" required placeholder="Informe o titulo...">
					</div>
				</div>

				<div class="form-group">
					<label for="descricao" class="col-sm-2 control-label">Descri&ccedil;&atilde;o</label>
					<div class="col-sm-10">
						<input type="text" name="descricao" id="descricao" value="<?= $buscaArtigo['descricao'] ?>" class="form-control required" required placeholder="Informe a Descri&ccedil;&atilde;o...">
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

