<?php
include "../../../../privado/sistema/conexao.php";

$pagina = isset($p) ? $p : 1;
$max = 10;
$inicio = $max * ($pagina - 1);

$link = "popup/velho-geral/municipio.php?t=$t";

if(empty($o)) $o = "municipio.nome";
if(empty($d)) $d = "ASC";

?>
<script>
function retorna(id, nome) {

	$('#id_municipio').val(id);
	$('#municipio').val(nome);
	$('#modal').modal('hide');

}
</script>
<div class="table-responsive">
	<table class="table table-striped table-hover table-bordered table-condensed">
		<tr class="active">
			<th style="width: 90px;">
				<a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $pagina ?>&o=municipio.id&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					C&oacute;digo
				</a>
				<?php if($o == "municipio.id" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "municipio.id" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
			</th>
			<th>
				<a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $pagina ?>&o=municipio.nome&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					Munic&iacute;pio
				</a>
				<?php if($o == "municipio.nome" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "municipio.nome" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
			</th>
			<th>
				<a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $pagina ?>&o=estado.nome&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					Estado
				</a>
				<?php if($o == "estado.nome" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "estado.nome" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				<form class="form-inline pull-right" action="" method="post" onsubmit="$('#modal_corpo').load('<?= $link ?>&busca=' + $('#busca').val() + '&time=' + $.now()); return false;">
					<div class="input-group">
					    <input type="search" name="busca" id="busca" class="form-control input-sm" value="<?= $busca ?>" placeholder="Pesquisar...">
					    <span class="input-group-btn">
					    	<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i></button>
					    </span>
				    </div>
			    </form>
			</th>
		</tr>
	<?php
	try {

		$sql = "SELECT municipio.*,
                       estado.nome estado
                  FROM municipio
             LEFT JOIN estado ON municipio.id_estado = estado.id 
                 WHERE municipio.status_registro = :status_registro ";
		
		$vetor["status_registro"] = "A";

		if($busca != "") {

			$sql .= "AND municipio.nome LIKE :nome ";
			$vetor['nome'] = "%$busca%";
			$link .= "&busca=" . urlencode($busca);

		}

		$stLinha = Conexao::chamar()->prepare($sql);
		$stLinha->execute($vetor);
		$qryLinha = $stLinha->fetchAll(PDO::FETCH_ASSOC);
		$totalLinha = count($qryLinha);

		$sql .= "ORDER BY $o $d LIMIT $inicio, $max";

		$stArtigo = Conexao::chamar()->prepare($sql);
		$stArtigo->execute($vetor);
		$qryArtigo = $stArtigo->fetchAll(PDO::FETCH_ASSOC);

		if(count($qryArtigo)) {

			foreach($qryArtigo as $buscaArtigo) {
		?>
		<tr>
			<td class="text-right"><a href="#" class="text-muted" onclick="retorna('<?= $buscaArtigo['id'] ?>', '<?= $buscaArtigo['nome'] ?>'); return false;"><?= $buscaArtigo['id'] ?></a></td>
			<td><a href="#" class="text-muted" onclick="retorna('<?= $buscaArtigo['id'] ?>', '<?= $buscaArtigo['nome'] ?>'); return false;"><?= $buscaArtigo['nome'] ?></a></td>
			<td><a href="#" class="text-muted" onclick="retorna('<?= $buscaArtigo['id'] ?>', '<?= $buscaArtigo['nome'] ?>'); return false;"><?= $buscaArtigo['estado'] ?></a></td>
		</tr>
		<?php
			}
		}

	} catch (PDOException $e) {

		echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar os registros.");
		echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

	}
	?>
	</table>
</div>

<?php
$link = $link . "&o=$o&d=$d";

include_once "$root/privado/sistema/classes/includes/paginacao.php";
paginacao_popup($pagina, $totalLinha, $max, $link);
?>
<p class="clearfix"></p>