<?php
if($_POST) {

	include_once "sql.php";

}

try {

	if($id) {

		$stArtigo = Conexao::chamar()->prepare("SELECT download.*,
                                    	               download_categoria.descricao categoria
                                    	          FROM download
                                    	     LEFT JOIN download_categoria ON download.id_categoria = download_categoria.id
                                    	         WHERE download.id = :id
    	                                           AND download.status_registro = :status_registro");

		$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
		$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

	} else {

		$stArtigo = Conexao::chamar()->prepare("SELECT download_categoria.id id_categoria,
                                                       download_categoria.descricao categoria
                                                  FROM download_categoria
                                                 WHERE download_categoria.id = :id");

		$stArtigo->execute(array("id" => $id_categoria));
		$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

	}

	if($_REQUEST['id_excluir']) {

		include_once "$root/privado/sistema/classes/includes/excluir.php";
		excluir($_REQUEST['id_excluir'], "download");

	}

} catch (PDOException $e) {

	echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}

?>
	<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

		<div class="form-group">
			<label for="id_categoria" class="col-sm-2 control-label">Categoria</label>
			<div class="col-sm-2 hidden-xs">
				<input type="text" name="id_categoria" id="id_categoria" value="<?= $buscaArtigo["id_categoria"] ?>" class="form-control required" required readonly >
			</div>
			<div class="col-sm-8">
				<div class="input-group">
				<span class="input-group-btn">
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/categoria_download.php?t=<?= $t ?>', 'Categorias')"><i class="fa fa-search"></i></button>
				</span>
					<input type="text" name="categoria" id="categoria" value="<?= $buscaArtigo["categoria"] ?>" class="form-control required" required readonly placeholder="Informe a categoria...">
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#arquivo" aria-controls="arquivo" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i><span class="hidden-sm hidden-xs"> Arquivos / Links</span></a></li>
		</ul>

		<div class="tab-content">

			<div role="tabpanel" class="tab-pane active" id="arquivo">

				<?php if(empty($id)) { ?>

					<div class="table-responsive">

						<table class="table table-striped table-hover table-bordered table-condensed">
							<tr class="active">
								<th style="width: 60px;"></th>
								<th>T&iacute;tulo</th>
							</tr>
							<?php

							if($id)
								$id_categoria = $buscaArtigo["id_categoria"];
							else
								$id_categoria = $id_categoria;

							$stArquivo = Conexao::chamar()->prepare("SELECT download.*,
																			download_categoria.descricao categoria
																	   FROM download
																  LEFT JOIN download_categoria ON download.id_categoria = download_categoria.id
																	  WHERE download.status_registro = :status_registro
																		AND download.id_categoria = :id_categoria");

							$stArquivo->execute(array("status_registro" => 'A', "id_categoria" => $id_categoria));
							$qryArquivo = $stArquivo->fetchAll(PDO::FETCH_ASSOC);

							if(count($qryArquivo)) {
								foreach($qryArquivo as $buscaArquivo) {
									?>
									<tr>
										<td class="text-center">
											<? if($excluir) { ?>
												<a href="#" class="text-danger btn-lista" onclick="excluir('<?= $caminhoTela ?>&s=cadastrar&tb_excluir=download&id_excluir=<?= $buscaArquivo['id'] ?>&id=<?= $id ?>');" data-toggle="tooltip" data-placement="right" title="Excluir Registro"><i class="fa fa-trash"></i></a>
											<?php } ?>
											<? if($cadastrar) { ?>
												<a href="<?= $caminhoTela ?>&id=<?= $buscaArquivo['id'] ?>&s=cadastrar" class="btn-lista" data-toggle="tooltip" data-placement="right" title="Editar Registro"><i class="fa fa-pencil"></i></a>
											<?php } ?>
										</td>
										<td><?= $buscaArquivo['titulo'] ?></td>
									</tr>
								<?php }} ?>
						</table>

					</div>

				<?php } ?>

				<p class="clearfix"></p>

				<div class="div_add_element">

					<div class="form-group">
						<label for="titulo" class="col-sm-2 control-label">T&iacute;tulo</label>
						<div class="col-sm-10">
							<input type="text" name="titulo" id="titulo" value="<?= ($id ? $buscaArtigo["titulo"] : "") ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo do arquivo...">
						</div>
					</div>

					<div class="form-group">
						<label for="arquivo" class="col-sm-2 control-label">Arquivo</label>
						<div class="col-sm-10">
							<div class="input-group">
							<span class="input-group-btn">
								<span class="btn btn-primary btn-file">
									<i class="fa fa-folder-open"></i> Selecionar&hellip;
									<input type="file" name="arquivo" id="arquivo">
								</span>
							</span>
								<input type="text" class="form-control" readonly placeholder="Selecione um arquivo...">
								<?php if($id) { ?>
									<span class="input-group-btn">
								<?php if($buscaArtigo['arquivo'] != "") { ?>
									<a href="<?= $CAMINHO ?>/arquivos/<?= $idCliente ?>/<?= $buscaArtigo['arquivo'] ?>" class="btn btn-info" target="_blank" data-toggle="tooltip" data-placement="left" title="Baixar Anexo"><i class="fa fa-cloud-download"></i></a>
									<a href="#" onclick="excluirArquivo('<?= $id ?>', 'download', 'arquivo', 'arquivo', '<?= $caminhoTela ?>&id=<?= $id ?>&s=cadastrar');" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Excluir Anexo"><i class="fa fa-trash"></i></a>
								<?php } ?>
							</span>
								<?php } ?>
							</div>
						</div>
					</div>

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
<?php
include "javaScript.php";

