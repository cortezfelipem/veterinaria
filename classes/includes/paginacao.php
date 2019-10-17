<?php
function paginacao($pagina, $totalLinha, $max, $link) {

	$menos = $pagina - 1;
	$mais = $pagina + 1;
	$paginas = ceil($totalLinha / $max);
	if($paginas > 1) {
	?>
	<nav class="pull-left">
		<ul class="pagination">
			<?php if($pagina == 1) { ?>
		    <li class="disabled"><a href="#" aria-label="Anterior"><span aria-hidden="true">&laquo;</span></a></li>
		    <?php } else { ?>
		    <li><a href="<?= $link ?>&p=<?= $menos ?>" aria-label="Anterior"><span aria-hidden="true">&laquo;</span></a></li>
		    <?php
			}

		    if(($pagina - 4) < 1) $anterior = 1;
		    else $anterior = $pagina - 4;

		    if(($pagina + 4) > $paginas) $posterior = $paginas;
		    else $posterior = $pagina + 4;

		    for($i = $anterior; $i <= $posterior; $i++) {

		    	if($i != $pagina) {
		    ?>
		    	<li><a href="<?= $link ?>&p=<?= $i ?>"><?= $i ?></a></li>
		    	<?php } else { ?>
		    	<li class="active"><a href="#"><?= $i ?><span class="sr-only">(current)</span></a></li>
		   	<?php
		    	}
		    }

		    if($mais <= $paginas) {
		   	?>
		   	<li><a href="<?= $link ?>&p=<?= $mais ?>" aria-label="Pr&oacute;ximo"><span aria-hidden="true">&raquo;</span></a></li>
		   	<?php } ?>
		</ul>
	</nav>
	<?php
	}

}

function paginacao_popup($pagina, $totalLinha, $max, $link) {

	$menos = $pagina - 1;
	$mais = $pagina + 1;
	$paginas = ceil($totalLinha / $max);
	?>
	<nav>
		<?php if($paginas > 1) { ?>
		<ul class="pagination pull-left">
			<?php if($pagina == 1) { ?>
		    <li class="disabled"><a href="#" aria-label="Anterior"><span aria-hidden="true">&laquo;</span></a></li>
		    <?php } else { ?>
		    <li><a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $menos ?>&time=' + $.now()); return false" aria-label="Anterior"><span aria-hidden="true">&laquo;</span></a></li>
		    <?php
			}

		    if(($pagina - 4) < 1) $anterior = 1;
		    else $anterior = $pagina - 4;

		    if(($pagina + 4) > $paginas) $posterior = $paginas;
		    else $posterior = $pagina + 4;

		    for($i = $anterior; $i <= $posterior; $i++) {

		    	if($i != $pagina) {
		    ?>
		    	<li><a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $i ?>&time=' + $.now()); return false"><?= $i ?></a></li>
		    	<?php } else { ?>
		    	<li class="active"><a href="#"><?= $i ?><span class="sr-only">(current)</span></a></li>
		   	<?php
		    	}
		    }

		    if($mais <= $paginas) {
		   	?>
		   	<li><a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $mais ?>&time=' + $.now()); return false" aria-label="Pr&oacute;ximo"><span aria-hidden="true">&raquo;</span></a></li>
		   	<?php } ?>
		</ul>
		<?php } ?>

		<button type="button" class="btn btn-warning pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Cancelar</button>
	</nav>
	<?php
}