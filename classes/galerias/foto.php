<nav class="pull-right hidden-xs hidden-sm">
	<button type="button" name="upload_normal" class="btn btn-success" onclick="maisFoto()"><i class="fa fa-cloud-upload"></i> Upload Individual</button>
	<button type="button" name="upload_multiplo" class="btn btn-primary" onclick="$('#upload_foto').toggleClass('hidden');"><i class="fa fa-cloud-upload"></i> Upload M&uacute;ltiplo</button>
</nav>

<nav class="visible-xs visible-sm">
	<button type="button" name="upload_normal" class="btn btn-success btn-block" onclick="maisFoto()"><i class="fa fa-cloud-upload"></i> Upload Individual</button>
	<button type="button" name="upload_multiplo" class="btn btn-primary btn-block" onclick="$('#upload_foto').toggleClass('hidden');"><i class="fa fa-cloud-upload"></i> Upload M&uacute;ltiplo</button>
</nav>

<p class="clearfix"></p>

<div id="upload_foto" class="form-group hidden">
	<div class="col-sm-offset-2 col-sm-10">
		<div class="input-group">
	    	<span class="input-group-btn">
	        	<span class="btn btn-primary btn-file">
	            	<i class="fa fa-folder-open"></i> Selecionar&hellip;
	              	<input type="file" name="imagem[]" multiple>
	      		</span>
	 		</span>
			<input type="text" class="form-control" readonly placeholder="Selecione uma ou mais imagens...">
	    </div>
    </div>
</div>

<p class="clearfix" id="mais-foto"></p>

<?php
$stFoto = Conexao::chamar()->query("SELECT *
									  FROM $tabelaFoto
									 WHERE id_$relacionamentoTabelaFoto = '$id'
								  ORDER BY ordem ASC, id DESC");

$qryFoto = $stFoto->fetchAll(PDO::FETCH_ASSOC);

if(count($qryFoto)) {
?>
<ol id="<?= $tabelaFoto ?>" class="galeriaSortable">
	<?php
	foreach ($qryFoto as $buscaFoto) {

	if(file_exists("$caminhoUploadImagem/gd_" . $buscaFoto['foto'])) {

		$imgTb = "tb_" . $buscaFoto['foto'];
		$imgGd = "gd_" . $buscaFoto['foto'];

	} else {

		$imgTb = $buscaFoto['foto'];
		$imgGd = $buscaFoto['foto'];

	}
	?>
	<li id="item_<?=$buscaFoto['id'] ?>">

		<!--<span class="item_descricao" id="span_foto_<?=$buscaFoto['id'] ?>"><?= $buscaFoto['legenda'] ?></span>-->
		
		<img data-toggle="tooltip" src="<?= $CAMINHO ?>/imagens/<?= $imgTb ?>" title="Arraste para alterar a ordem" />
	
		<input type="hidden" name="credito_alterar[]" id="credito_alterar_<?= $buscaFoto['id'] ?>" value="<?= $buscaFoto['credito'] ?>" />
		<input type="hidden" name="legenda_alterar[]" id="legenda_alterar_<?= $buscaFoto['id'] ?>" value="<?= $buscaFoto['legenda'] ?>" />
		
		<input type="hidden" name="id_foto[]" value="<?= $buscaFoto['id'] ?>" />

		<a class="btn btn-success btn-xs" href="<?= $CAMINHO ?>/imagens/<?= $imgGd ?>" data-toggle="lightbox" data-gallery="quem_somos_fotos" data-title="Galeria de Fotos" style="position: absolute; bottom: 1px; left: 1px;" title="Ampliar Imagem">
			<i class="fa fa-search"></i>
		</a>
		<!--
		<button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" onclick="editarGaleriaFoto('<?= $buscaFoto['id'] ?>')" style="position: absolute; bottom: 1px; right: 24px;" title="Editar Descri&ccedil;&atilde;o">
			<i class="fa fa-pencil"></i>
		</button>
		-->
		<button type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" onclick="jQuery(this).parent().fadeOut('slow', function(){jQuery(this).remove();})" style="position: absolute; bottom: 1px; right: 1px;" title="Excluir Imagem">
			<i class="fa fa-trash"></i>
		</button>

	</li>
	<?php } ?>
</ol>
<?php } ?>