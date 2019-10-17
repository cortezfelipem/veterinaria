<?php
if($_POST) {

	include_once "sql.php";

}

try {

	if($id) {

		$stArtigo = Conexao::chamar()->prepare("SELECT produto.*,
                                    	               produto_categoria.categoria categoria,
                                    	               produto_marca.marca marca,
                                    	               produto_subcategoria.subcategoria subcategoria
                                    	          FROM produto
                                    	     LEFT JOIN produto_marca ON produto.id_marca = produto_marca.id
                                    	     LEFT JOIN produto_categoria ON produto.id_categoria = produto_categoria.id
                                    	     LEFT JOIN produto_subcategoria ON produto.id_subcategoria = produto_subcategoria.id
                                    	         WHERE produto.id = :id
    	                                           AND produto.status_registro = :status_registro");

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
		excluir($_REQUEST['id_excluir'], "produto");

	}

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}

$tabelaAnexo = "produto_anexo";
$relacionamentoTabelaAnexo = "artigo";
$tabelaVideo = "produto_video";
$relacionamentoTabelaVideo = "artigo";
$tabelaFoto = "produto_foto";
$relacionamentoTabelaFoto = "artigo";

?>
	<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-sm hidden-xs"> Dados Gerais</span></a></li>
			<li role="presentation"><a href="#anexo" aria-controls="anexo" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i><span class="hidden-sm hidden-xs"> Anexos</span></a></li>
			<li role="presentation"><a href="#foto" aria-controls="foto" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><span class="hidden-sm hidden-xs"> Fotos</span></a></li>
			<li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab"><i class="fa fa-video_camera"></i><span class="hidden-sm hidden-xs"> V&iacute;deos</span></a></li>
		</ul>

		<div class="tab-content">

			<div role="tabpanel" class="tab-pane active" id="geral">

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
					<label for="titulo" class="col-sm-2 control-label">T&iacute;tulo</label>
					<div class="col-sm-10">
						<input type="text" name="titulo" id="titulo" value="<?= $buscaArtigo['titulo'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">
					</div>
				</div>

				<div class="form-group">
					<label for="artigo" class="col-sm-2 control-label">Artigo</label>
					<div class="col-sm-10">
						<textarea name="artigo" id="artigo" class="form-control editor"><?= $buscaArtigo['artigo'] ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="preco" class="col-sm-2 control-label">Pre&ccedil;o</label>
					<div class="col-sm-10">
						<input type="text" name="preco" id="preco" value="<?= formata_valor($buscaArtigo['preco']) ?>" class="form-control required moeda" required placeholder="Informe o pre&ccedil;o...">
					</div>
				</div>

				<div class="form-group">
					<label for="preco_de" class="col-sm-2 control-label">Pre&ccedil;o de </label>
					<div class="col-sm-10">
						<input type="text" name="preco_de" id="preco_de" value="<?= formata_valor($buscaArtigo['preco_de']) ?>" class="form-control moeda" placeholder="Informe o pre&ccedil;o de...">
					</div>
				</div>

				<div class="form-group">
					<label for="peso" class="col-sm-2 control-label">Peso</label>
					<div class="col-sm-10">
						<input type="text" name="peso" id="peso" value="<?= formata_valor($buscaArtigo['peso']) ?>" class="form-control peso" placeholder="Informe o peso...">
					</div>
				</div>

				<div class="form-group">
					<label for="largura" class="col-sm-2 control-label">Largura</label>
					<div class="col-sm-10">
					 	<div data-role="rangeslider">
						<!--<input type="text" name="largura" id="largura" value="<?= $buscaArtigo['largura'] ?>" class="form-control required number" required placeholder="Informe a largura...">-->
        					<output id="label_largura"><?= $buscaArtigo['largura'] ?> <span>cm</span></output> 
        					<input type="range" name="largura" id="largura" value="<?= $buscaArtigo['largura'] ?>" min="11" max="105" onchange="label_largura.value=value+' cm'">
        				</div>
					</div>
				</div>

				<div class="form-group">
					<label for="altura" class="col-sm-2 control-label">Altura</label>
					<div class="col-sm-10">
						<!--<input type="text" name="altura" id="altura" value="<?= $buscaArtigo['altura'] ?>" class="form-control number" placeholder="Informe a altura...">-->
						<div data-role="rangeslider">
        					<output id="label_altura"><?= $buscaArtigo['altura'] ?> <span>cm</span></output>
        					<input type="range" name="altura" id="altura" value="<?= $buscaArtigo['altura'] ?>" min="2" max="105" onchange="label_altura.value=value+' cm'">
        				</div>
					</div>
				</div>

				<div class="form-group">
					<label for="comprimento" class="col-sm-2 control-label">Comprimento</label>
					<div class="col-sm-10">
						<!--<input type="text" name="comprimento" id="comprimento" value="<?= $buscaArtigo['comprimento'] ?>" class="form-control number" placeholder="Informe o comprimento...">-->
						<div data-role="rangeslider">
        					<output id="label_comprimento"><?= $buscaArtigo['comprimento'] ?> <span>cm</span></output>
        					<input type="range" name="comprimento" id="comprimento" value="<?= $buscaArtigo['comprimento'] ?>" min="16" max="105" onchange="label_comprimento.value=value+' cm'">
        				</div>
					</div>
				</div>

				<div class="form-group">
					<label for="diametro" class="col-sm-2 control-label">Di&acirc;metro</label>
					<div class="col-sm-10">
						<input type="text" name="diametro" id="diametro" value="<?= $buscaArtigo['diametro'] ?>" class="form-control number" placeholder="Informe o di&acirc;metro...">
					</div>
				</div>

				<div class="form-group">
					<label for="estoque" class="col-sm-2 control-label">Estoque</label>
					<div class="col-sm-10">
						<input type="text" name="estoque" id="estoque" value="<?= $buscaArtigo['estoque'] ?>" class="form-control required number" required placeholder="Informe o estoque...">
					</div>
				</div>

				<div class="form-group">
					<label for="parcela" class="col-sm-2 control-label">Parcela</label>
					<div class="col-sm-10">
						<input type="text" name="parcela" id="parcela" value="<?= $buscaArtigo['parcela'] ?>" class="form-control required" required placeholder="Informe a parcela...">
					</div>
				</div>
				<div class="form-group">
					<label for="destaque" class="col-sm-2 control-label"></label>
					<div class="col-sm-10">
						<label><input type="checkbox" name="destaque" id="destaque"  <? if($buscaArtigo['destaque'] == "S") echo "checked";?> > Incluir esse produto entre os destaques.</label>
<!--						<label><input type="checkbox" name="promocao" value="S" --><?//= ($buscaArtigo["promocao"] == "S" ? "checked" : "") ?><!-- />&nbsp;Incluir esse produto entre as promo&ccedil;&otilde;es.</label>-->
					</div>
				</div>

				<div class="form-group">
					<label for="promocao" class="col-sm-2 control-label"></label>
					<div class="col-sm-10">
<!--						<label><input type="checkbox" name="promocao" id="promocao"  --><?// if($buscaArtigo['promocao'] == "S") echo "checked";?><!-- > Incluir esse produto entre as promo&ccedil;&otilde;es.</label>-->
						<label><input type="checkbox" name="promocao" value="S" <?= ($buscaArtigo["promocao"] == "S" ? "checked" : "") ?> />&nbsp;Incluir esse produto entre as promo&ccedil;&otilde;es.</label>

					</div>
				</div>

			</div>

			<div role="tabpanel" class="tab-pane" id="anexo">

				<?php include "$root/privado/sistema/classes/galerias/anexo.php"; ?>

			</div>

			<div role="tabpanel" class="tab-pane" id="foto">

				<?php include "$root/privado/sistema/classes/galerias/foto.php"; ?>

			</div>

			<div role="tabpanel" class="tab-pane" id="video">

				<?php include "$root/privado/sistema/classes/galerias/video.php"; ?>

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

