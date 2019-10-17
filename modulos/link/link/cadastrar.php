<?php
if($_POST) {

	include_once "sql.php";

}

try {

	if($id) {

		$stArtigo = Conexao::chamar()->prepare("SELECT link.*,
                                    	               link_categoria.descricao categoria
                                    	          FROM link
                                    	     LEFT JOIN link_categoria ON link.id_categoria = link_categoria.id
                                    	         WHERE link.id = :id
    	                                           AND link.status_registro = :status_registro");

		$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
		$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

	} else {

		$stArtigo = Conexao::chamar()->prepare("SELECT link_categoria.id id_categoria,
                                                       link_categoria.descricao categoria
                                                  FROM link_categoria
                                                 WHERE link_categoria.id = :id");

		$stArtigo->execute(array("id" => $id_categoria));
		$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

	}

	if($_REQUEST['id_excluir']) {

		include_once "$root/privado/sistema/classes/includes/excluir.php";
		excluir($_REQUEST['id_excluir'], "link");

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
					<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/categoria_link.php?t=<?= $t ?>', 'Categorias')"><i class="fa fa-search"></i></button>
				</span>
					<input type="text" name="categoria" id="categoria" value="<?= $buscaArtigo["categoria"] ?>" class="form-control required" required readonly placeholder="Informe a categoria...">
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label for="titulo" class="col-sm-2 control-label">T&iacute;tulo</label>
			<div class="col-sm-10">
				<input type="text" name="titulo" id="titulo" value="<?= ($id ? $buscaArtigo["titulo"] : "") ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo do arquivo...">
			</div>
		</div>

		<div class="form-group">
			<label for="link" class="col-sm-2 control-label">Link</label>
			<div class="col-sm-10">
				<input type="text" name="link" id="link" value="<?= ($id ? $buscaArtigo["link"] : "") ?>" class="form-control url" placeholder="Informe uma url v&aacute;lida...">
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

