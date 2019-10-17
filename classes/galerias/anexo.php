<nav class="pull-right hidden-xs hidden-sm">
	<button type="button" name="upload_normal" class="btn btn-success" onclick="maisAnexo()"><i class="fa fa-cloud-upload"></i> Upload Individual</button>
	<button type="button" name="upload_multiplo" class="btn btn-primary" onclick="$('#upload_anexo').toggleClass('hidden');"><i class="fa fa-cloud-upload"></i> Upload M&uacute;ltiplo</button>
</nav>

<nav class="visible-xs visible-sm">
	<button type="button" name="upload_normal" class="btn btn-success btn-block" onclick="maisAnexo()"><i class="fa fa-cloud-upload"></i> Upload Individual</button>
	<button type="button" name="upload_multiplo" class="btn btn-primary btn-block" onclick="$('#upload_anexo').toggleClass('hidden');"><i class="fa fa-cloud-upload"></i> Upload M&uacute;ltiplo</button>
</nav>

<p class="clearfix"></p>

<div id="upload_anexo" class="form-group hidden">
	<div class="col-sm-offset-2 col-sm-10">
		<div class="input-group">
	    	<span class="input-group-btn">
	        	<span class="btn btn-primary btn-file">
	            	<i class="fa fa-folder-open"></i> Selecionar&hellip;
	              	<input type="file" name="anexo[]" multiple>
	      		</span>
	 		</span>
			<input type="text" class="form-control" readonly placeholder="Selecione um ou mais arquivos...">
	    </div>
    </div>
</div>

<p class="clearfix" id="mais-anexo"></p>

<?php
$stAnexo = Conexao::chamar()->query("SELECT *
									   FROM $tabelaAnexo
									  WHERE id_$relacionamentoTabelaAnexo = '$id'
								   ORDER BY ordem ASC, id DESC");

$qryAnexo = $stAnexo->fetchAll(PDO::FETCH_ASSOC);

if(count($qryAnexo)) {
?>
<ol id="<?= $tabelaAnexo ?>" class="galeriaSortable">
	<?php
	foreach ($qryAnexo as $buscaAnexo) {

		$extensao = array_pop(explode(".", $buscaAnexo['arquivo']));

		switch (strtolower($extensao)) {

			case "jpg":
			case "jpeg":
			case "png":
			case "gif":
				$imagem = $CAMINHO . "/arquivos/" . $buscaAnexo['arquivo'];
				break;
			case "pdf":
				$imagem = $caminhoImg . "/ico_pdf.png";
				break;
			case "doc":
			case "docx":
				$imagem = $caminhoImg . "/ico_word.png";
				break;
			case "xls":
			case "xlsx":
			case "csv":
				$imagem = $caminhoImg . "/ico_excel.png";
				break;
			case "ppt":
			case "pptx":
			case "pps":
			case "ppsx":
				$imagem = $caminhoImg . "/ico_ppt.png";
				break;
			case "mp3":
			case "wma":
			case "wav":
			case "ogg":
				$imagem = $caminhoImg . "/ico_audio.png";
				break;
			case "mp4":
			case "avi":
			case "flv":
			case "wmv":
				$imagem = $caminhoImg . "/ico_video.png";
				break;
			case "zip":
			case "rar":
			case "gz":
				$imagem = $caminhoImg . "/ico_zip.png";
				break;
			default:
				$imagem = $caminhoImg . "/ico_txt.png";
				break;
		}
	?>
	<li id=item_"<?=$buscaAnexo['id'] ?>">

		
		<span class="item_descricao" id="span_anexo_<?=$buscaAnexo['id'] ?>"><?= $buscaAnexo['descricao'] ?></span>
		

		<img data-toggle="tooltip" src="<?= $imagem ?>" title="Arraste para alterar a ordem" />

		<input type="hidden" name="descricao_alterar_anexo[]" id="descricao_alterar_<?= $buscaAnexo['id'] ?>" value="<?= $buscaAnexo['descricao'] ?>" />
		<input type="hidden" name="id_anexo[]" value="<?= $buscaAnexo['id'] ?>" />

		<a class="btn btn-success btn-xs" data-toggle="tooltip" href="<?= $CAMINHO ?>/arquivos/<?= $buscaAnexo['arquivo'] ?>" target="_blank" style="position: absolute; bottom: 1px; right: 48px;" title="Abrir Arquivo">
			<i class="fa fa-search"></i>
		</a>

		<button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" onclick="editarGaleriaAnexo('<?= $buscaAnexo['id'] ?>')" style="position: absolute; bottom: 1px; right: 24px;" title="Editar Descri&ccedil;&atilde;o">
			<i class="fa fa-pencil"></i>
		</button>

		<button type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" onclick="jQuery(this).parent().fadeOut('slow', function(){jQuery(this).remove();})" style="position: absolute; bottom: 1px; right: 1px;" title="Excluir Arquivo">
			<i class="fa fa-trash"></i>
		</button>

	</li>
	<?php } ?>
</ol>
<?php } ?>