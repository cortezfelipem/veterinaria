<?php
if($_POST) {

	include_once "sql.php";

}

try {

	if($id) {
		

		$stm = Conexao::chamar()->prepare("	SELECT  produto_filho.*,
			 										produto_pai.titulo produto_pai_titulo,
		 										    produto_subcategoria.id_atributo_1 id_tipo_atributo_1,
		 											produto_subcategoria.id_atributo_2 id_tipo_atributo_2,
		 											produto_subcategoria.id_atributo_3 id_tipo_atributo_3,
		 											a1.descricao tipo_atributo_descricao_1,
		 											a2.descricao tipo_atributo_descricao_2,
		 											a3.descricao tipo_atributo_descricao_3,
		 											at1.descricao atributo_descricao_1,
		 											at2.descricao atributo_descricao_2,
		 											at3.descricao atributo_descricao_3
		 				
											FROM produto_filho
											INNER JOIN produto_pai ON produto_pai.id = produto_filho.id_produto_pai
											INNER JOIN produto_subcategoria ON produto_pai.id_subcategoria = produto_subcategoria.id 
				
											JOIN produto_tipo_atributo a1 ON produto_subcategoria.id_atributo_1 = a1.id 
											JOIN produto_tipo_atributo a2 ON produto_subcategoria.id_atributo_2 = a2.id 
											JOIN produto_tipo_atributo a3 ON produto_subcategoria.id_atributo_3 = a3.id 
				
											JOIN produto_atributo at1 ON produto_filho.id_atributo_1 = at1.id 
											JOIN produto_atributo at2 ON produto_filho.id_atributo_2 = at2.id 
											JOIN produto_atributo at3 ON produto_filho.id_atributo_3 = at3.id
										    WHERE produto_filho.id = :id
    	                                    AND   produto_filho.status_registro = :status_registro
    	                                    LIMIT 1
    	                                 ");

	
		$stm->execute(array("id" => $id, "status_registro" => "A"));
	    $lista = $stm->fetch(PDO::FETCH_ASSOC);


	} else {


		if(isset($_REQUEST['id_prod']) && !$id ){
		

			$stm = Conexao::chamar()->prepare(" SELECT
												produto_pai.id id_produto_pai,
												produto_pai.titulo produto_pai_titulo,
						 						produto_subcategoria.id_atributo_1 id_tipo_atributo_1,
						 						produto_subcategoria.id_atributo_2 id_tipo_atributo_2,
						 						produto_subcategoria.id_atributo_3 id_tipo_atributo_3,
						 						a1.descricao tipo_atributo_descricao_1,
						 						a2.descricao tipo_atributo_descricao_2,
						 						a3.descricao tipo_atributo_descricao_3
												FROM produto_pai
												INNER JOIN produto_subcategoria ON produto_pai.id_subcategoria = produto_subcategoria.id 
												JOIN produto_tipo_atributo a1 ON produto_subcategoria.id_atributo_1 = a1.id 
												JOIN produto_tipo_atributo a2 ON produto_subcategoria.id_atributo_2 = a2.id 
												JOIN produto_tipo_atributo a3 ON produto_subcategoria.id_atributo_3 = a3.id 
                								WHERE produto_pai.status_registro = :status_registro
                								AND produto_pai.id = :id_pai
                								LIMIT 1
                							");
		    $stm->bindValue(":status_registro", "A", PDO::PARAM_STR);
			$stm->bindValue(":id_pai", $_REQUEST['id_prod'] , PDO::PARAM_INT);
			$stm->execute();
			$lista = $stm->fetch(PDO::FETCH_ASSOC);

			
		}

	}

	if($_REQUEST['id_excluir']) {

		include_once "$root/privado/sistema/classes/includes/excluir.php";
		excluir($_REQUEST['id_excluir'], "produto_filho");

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

<style type="text/css">
	.margin-b{
		margin-bottom: 15px
	}

	

    @media only screen and (min-width : 992px) {
    	.col-20{
			width: 20%;
		}
    }
	
</style>
	<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-sm hidden-xs"> Dados Gerais</span></a></li>
			<li role="presentation"><a href="#anexo" aria-controls="anexo" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i><span class="hidden-sm hidden-xs"> Anexos</span></a></li>
			<li role="presentation"><a href="#foto" aria-controls="foto" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i><span class="hidden-sm hidden-xs"> Fotos</span></a></li>
			<li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab"><i class="fa fa-television"></i><span class="hidden-sm hidden-xs"> V&iacute;deos</span></a></li>
		</ul>

		<div class="tab-content">

			<div role="tabpanel" class="tab-pane active" id="geral">


				<div class="panel panel-primary">
					<div class="panel-heading"><i class="fa fa-edit"></i> Dados</div>
					<div class="panel-body">


						<div class="col-xs-12 col-sm-12 col-md-12 margin-b">
							<label for="id_subcategoria">Produto</label>
							<input type="hidden" name="id_produto_pai" id="id_produto_pai" value="<?= $lista["id_produto_pai"] ?>" class="form-control required" required readonly>
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/produto_pai.php?t=<?= $t ?>', 'Produto')"><i class="fa fa-search"></i></button>
								</span>
								<input type="text" name="produto_pai_titulo" id="produto_pai_titulo" value="<?= $lista["produto_pai_titulo"] ?>" oninvalid="this.setCustomValidity('Please Enter valid email')" class="form-control required" required readonly placeholder="Informe o produto...">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 margin-b">
							<label for="titulo">T&iacute;tulo</label>

							<input type="text" name="titulo" id="titulo" value="<?= $lista['titulo'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">			
						</div>

						<div class="col-xs-12 col-sm-12 col-md-4 margin-b"  id="form_atributo_1">
							<label for="id_atributo_1" id="label_atributo_1">
								<?= ($lista['id_tipo_atributo_1'] > 0) ? $lista['tipo_atributo_descricao_1'] : 'Atributo 1' ?>
							</label>
							<input type="hidden" name="tipo_atributo_1" id="tipo_atributo_1" value="<?= $lista["id_tipo_atributo_1"] ?>" class="form-control" readonly >
							<input type="hidden" name="id_atributo_1" id="id_atributo_1" value="<?= $lista['id_atributo_1'] ?>" class="form-control" readonly >
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/atributo_produto_filho.php?t=<?= $t ?>&atributo=1&id_tipo_atributo='+jQuery('#tipo_atributo_1').val(), jQuery('#label_atributo_1').text() )"><i class="fa fa-search"></i></button>
								</span>
								<input type="text" name="atributo" id="atributo" value="<?= $lista['atributo_descricao_1'] ?>" class="form-control" readonly placeholder="Informe o atributo...">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-4 margin-b" id="form_atributo_2">
							<label for="id_atributo_2"  id="label_atributo_2" >
								<?= ($lista['id_tipo_atributo_2'] > 0) ? $lista['tipo_atributo_descricao_2'] : 'Atributo 2' ?>
							</label>
							<input type="hidden" name="tipo_atributo_2" id="tipo_atributo_2" value="<?= $lista["id_tipo_atributo_2"] ?>" class="form-control" readonly >
							<input type="hidden" name="id_atributo_2" id="id_atributo_2" value="<?= $lista['id_atributo_2'] ?>" class="form-control" readonly >
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/atributo_produto_filho.php?t=<?= $t ?>&atributo=2&id_tipo_atributo='+jQuery('#tipo_atributo_2').val(), jQuery('#label_atributo_2').text() )"><i class="fa fa-search"></i></button>
								</span>
								<input type="text" name="atributo2" id="atributo2" value="<?= $lista['atributo_descricao_2'] ?>" class="form-control" readonly placeholder="Informe o segundo atributo...">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-4 margin-b"  id="form_atributo_3">
							<label for="id_atributo_3"  id="label_atributo_3" >
								<?= ($lista['id_tipo_atributo_3'] > 0) ? $lista['tipo_atributo_descricao_3'] : 'Atributo 3' ?>
							</label>
							<input type="hidden" name="tipo_atributo_3" id="tipo_atributo_3" value="<?= $lista["id_tipo_atributo_3"] ?>" class="form-control" readonly >
							<input type="hidden" name="id_atributo_3" id="id_atributo_3" value="<?= $lista['id_atributo_3'] ?>" class="form-control" readonly >
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-primary" onclick="popup('<?= $CAMINHO ?>/popup/geral/atributo_produto_filho.php?t=<?= $t ?>&atributo=3&id_tipo_atributo='+jQuery('#tipo_atributo_3').val(), jQuery('#label_atributo_3').text() )"><i class="fa fa-search"></i></button>
								</span>
								<input type="text" name="atributo3" id="atributo3" value="<?= $lista['atributo_descricao_3'] ?>" class="form-control" readonly placeholder="Informe o segundo atributo...">
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 margin-b">
							<label for="artigo">Artigo</label>
							<textarea name="artigo" id="artigo" class="form-control editor"><?= $lista['artigo'] ?></textarea>					
						</div>

					</div>
				</div>


				<div class="col-md-12" style="padding: 0">	

					<div class="panel panel-primary">
						<div class="panel-heading"><i class="fa fa-building"></i> Invent&aacute;rio</div>
						<div class="panel-body">

							<div class="col-xs-12 col-sm-12 col-md-3 margin-b">
								<label for="preco">Pre&ccedil;o</label>
								<input type="text" name="preco" id="preco" value="<?= formata_valor($lista['preco']) ?>" class="form-control required moeda" required placeholder="Informe o pre&ccedil;o...">
							</div>



							<div class="col-xs-12 col-sm-12 col-md-3 margin-b">
								<label for="estoque">Estoque</label>
								<input type="number" name="estoque" id="estoque" value="<?= $lista['estoque'] ?>" digits="true" class="form-control required number" required placeholder="Informe o estoque...">					
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 margin-b">
								<label for="parcela">Parcela</label>
								<input type="number" name="parcela" id="parcela" value="<?= $lista['parcela'] ?>" digits="true" class="form-control required" required placeholder="Informe a parcela...">
							</div>


							<div class="col-xs-12 col-sm-12 col-md-3 margin-b">
								<label for="peso">Peso</label>
								<input type="text" name="peso" id="peso" value="<?= formata_valor($lista['peso']) ?>" class="form-control peso" placeholder="Informe o peso...">
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 margin-b">
								<label for="largura">Largura (cm)</label>
								<input type="number" name="largura" id="largura" value="<?= $lista['largura'] ?>" digits="true" min="11" max="105" class="form-control range required" placeholder="Largura entre 11 e 105">
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 margin-b">
								<label for="altura">Altura (cm)</label>
								<input type="number" name="altura" id="altura" value="<?= $lista['altura'] ?>" digits="true" min="2" max="105" class="form-control range required" placeholder="Altura entre 2 e 105">
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 margin-b">
								<label for="comprimento">Comprimento (cm)</label>
								<input type="number" name="comprimento" id="comprimento" value="<?= $lista['comprimento'] ?>" digits="true" min="16" max="105" class="form-control range required" placeholder="Comprimento entre 16 e 105">
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 margin-b">
								<label for="diametro">Diametro (cm)</label>
								<input type="number" name="diametro" id="diametro" value="<?= $lista['diametro'] ?>" digits="true" min="1" max="305" class="form-control range required" placeholder="Diametro entre 1 e 305">
							</div>

						</div>
					</div>
			    </div>
			
				<div class="col-xs-12 col-sm-12 col-md-12 margin-b">
					<label><input type="checkbox" name="destaque" id="destaque"  <? if($lista['destaque'] == "S") echo "checked";?> > Incluir esse produto entre os destaques.</label>
				</div>

				

				<div class="col-md-12" style="padding: 0">	

					<div class="panel panel-primary">
						<div class="panel-heading"><i class="fa fa-calendar"></i> Promo&ccedil;&atilde;o</div>
						<div class="panel-body">

							<div class="col-xs-12 col-sm-12 col-md-3 col-20 margin-b">
								<label for="preco_de">Promo&ccedil;&atilde;o </label>
								<input type="text" name="preco_de" id="preco_de" value="<?= formata_valor($lista['preco_de']) ?>" class="form-control moeda" placeholder="Informe o pre&ccedil;o de...">
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 col-20 margin-b">
								<label for="data_inicial">Data Inicial</label>
								
								<div class="input-group date">
									<input type="text" name="data_inicial" id="data_inicial" value="<?= formata_data($lista['data_inicial']) ?>" class="form-control data">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 col-20 margin-b">
								<label for="hora_inicial" >Hora Inicial</label>
								<input type="text" name="hora_inicial" id="hora_inicial" value="<?= $lista['hora_inicial'] ?>" class="form-control hora" >
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 col-20 margin-b">
								<label for="data_limite">Data Limite</label>
								<div class="input-group date">
									<input type="text" name="data_limite" id="data_limite" value="<?= formata_data($lista['data_limite']) ?>" class="form-control data" >
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>

							<div class="col-xs-12 col-sm-12 col-md-3 col-20 margin-b">
								<label for="hora_limite">Hora Limite</label>
								<input type="text" name="hora_limite" id="hora_limite" value="<?= $lista['hora_limite'] ?>" class="form-control hora" >
							</div>

						</div>
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

