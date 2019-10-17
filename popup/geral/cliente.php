<?php
include "../../../../privado/sistema/conexao.php";

$pagina = isset($p) ? $p : 1;
$max = 10;
$inicio = $max * ($pagina - 1);

if($_REQUEST["origem"] == "troca_sistema") {
    
    $link = "popup/velho-geral/cliente.php?t=$t&origem=troca_sistema";

} else {
    
    $link = "popup/velho-geral/cliente.php?t=$t";
    
}

if(empty($o)) $o = "razao_social";
if(empty($d)) $d = "ASC";

?>

<?php if($_REQUEST["origem"] == "troca_sistema") { ?>

<script>
    function retorna(id, descricao) {
    
    	window.location="<?= $CAMINHO ?>/inicio.php?t=troca_sistema&id_cliente="+id;
    
    }
</script>

<?php } else { ?>

<script>
    function retorna(id, descricao) {
    
    	$('#id_cliente').val(id);
    	$('#cliente').val(descricao);
    	$('#modal').modal('hide');
    
    }
</script>

<?php } ?>

<div class="table-responsive">
	<table class="table table-striped table-hover table-bordered table-condensed">
		<tr class="active">
			<th style="width: 90px;">
				<a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $pagina ?>&o=id&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					C&oacute;digo
				</a>
				<?php if($o == "id" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "id" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
			</th>
			<th>
				<a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $pagina ?>&o=razao_social&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					Raz&atilde;o Social
				</a>
				<?php if($o == "razao_social" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "razao_social" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
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

		$sql = "SELECT * 
                  FROM cliente 
                 WHERE status_registro = :status_registro ";
		$vetor["status_registro"] = "A";

		if($busca != "") {

			$sql .= "AND razao_social LIKE :descricao ";
			$vetor['descricao'] = "%$busca%";
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
			<td class="text-right"><a href="#" class="text-muted" onclick="retorna('<?= $buscaArtigo['id'] ?>', '<?= $buscaArtigo['razao_social'] ?>'); return false;"><?= $buscaArtigo['id'] ?></a></td>
			<td><a href="#" class="text-muted" onclick="retorna('<?= $buscaArtigo['id'] ?>', '<?= $buscaArtigo['razao_social'] ?>'); return false;"><?= $buscaArtigo['razao_social'] ?></a></td>
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