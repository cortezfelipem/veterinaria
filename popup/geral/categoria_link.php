<?php
include "../../../../privado/sistema/conexao.php";

$pagina = isset($p) ? $p : 1;
$max = 10;
$inicio = $max * ($pagina - 1);

$link = "popup/velho-geral/categoria_link.php?t=$t";

if(empty($o)) $o = "descricao";
if(empty($d)) $d = "ASC";

?>
<script>
function retorna(id, descricao) {

	$('#id_categoria').val(id);
	$('#categoria').val(descricao);
	$('#modal').modal('hide');

}
</script>
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
				<a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $pagina ?>&o=descricao&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					Descri&ccedil;&atilde;o
				</a>
				<?php if($o == "descricao" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "descricao" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
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
                  FROM link_categoria 
                 WHERE status_registro = :status_registro ";
		
		$vetor["status_registro"] = "A";

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
			    
			    $retornoId = $buscaArtigo["id"];
			    $retornoDescricao = $buscaArtigo["descricao"];
		?>
		<tr>
			<td class="text-right"><a href="#" class="text-muted" onclick="retorna('<?= $retornoId ?>', '<?= $retornoDescricao ?>'); return false;"><?= $buscaArtigo["id"] ?></a></td>
			<td><a href="#" class="text-muted" onclick="retorna('<?= $retornoId ?>', '<?= $retornoDescricao ?>'); return false;"><?= $buscaArtigo["descricao"] ?></a></td>
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