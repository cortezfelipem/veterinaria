<nav class="pull-right hidden-xs hidden-sm">
	<button type="button" name="adicionar_video" class="btn btn-success" onclick="maisVideo()"><i class="fa fa-video-camera"></i> Adicionar V&iacute;deo</button>
</nav>

<nav class="visible-xs visible-sm">
	<button type="button" name="adicionar_video" class="btn btn-success btn-block" onclick="maisVideo()"><i class="fa fa-video-camera"></i> Adicionar V&iacute;deo</button>
</nav>

<p class="clearfix" id="mais-video"></p>

<?php
$stVideo = Conexao::chamar()->query("SELECT *
									   FROM $tabelaVideo
									  WHERE id_$relacionamentoTabelaVideo = '$id'
								   ORDER BY ordem ASC, id DESC");

$qryVideo = $stVideo->fetchAll(PDO::FETCH_ASSOC);

if(count($qryVideo)) {
?>
<ol id="<?= $tabelaVideo ?>" class="galeriaSortable">
	<?php
	foreach ($qryVideo as $buscaVideo) {

	$video = video($buscaVideo['link']);
	$tituloVideo = empty($buscaVideo['titulo']) ? $video['title'] : $buscaVideo['titulo'];
	?>
	<li id="item_<?=$buscaVideo['id'] ?>">

		
		<span class="item_descricao" id="span_video_<?=$buscaVideo['id'] ?>"><?= $tituloVideo ?></span>
		

		<img data-toggle="tooltip" src="<?= $video['img'] ?>" title="Arraste para alterar a ordem" />

		<input type="hidden" name="titulo_video_alterar[]" id="titulo_video_alterar_<?= $buscaVideo['id'] ?>" value="<?= $buscaVideo['titulo'] ?>" />
		<input type="hidden" name="link_video_alterar[]" id="link_video_alterar_<?= $buscaVideo['id'] ?>" value="<?= $buscaVideo['link'] ?>" />
		<input type="hidden" name="id_video[]" value="<?= $buscaVideo['id'] ?>" />

		<a class="btn btn-success btn-xs" href="<?= $video['embed'] ?>" " data-toggle="lightbox" data-gallery="quem_somos_videos" data-title="<?=$tituloVideo?>" style="position: absolute; bottom: 1px; right: 48px;" title="Visualizar V&iacute;deo">
			<i class="fa fa-search"></i>
		</a>

		<button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" onclick="editarGaleriaVideo('<?= $buscaVideo['id'] ?>')" style="position: absolute; bottom: 1px; right: 24px;" title="Editar Descri&ccedil;&atilde;o">
			<i class="fa fa-pencil"></i>
		</button>

		<button type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" onclick="jQuery(this).parent().fadeOut('slow', function(){jQuery(this).remove();})" style="position: absolute; bottom: 1px; right: 1px;" title="Excluir V&iacute;deo">
			<i class="fa fa-trash"></i>
		</button>

	</li>
	<?php } ?>
</ol>
<?php } ?>