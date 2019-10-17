<?php
include "../../../../privado/sistema/conexao.php";

$pagina = isset($p) ? $p : 1;
$max = 10;
$inicio = $max * ($pagina - 1);


$produto = "popup/geral/produto_pai.php?t=$t";

if(empty($o)) $o = "titulo";
if(empty($d)) $d = "ASC";

?>
<script>
function retorna(id, produto_pai_titulo, atributo1, atributo2, atributo3, descricao1 , descricao2, descricao3) {

	$('#id_produto_pai').val(id);
	$('#produto_pai_titulo').val(produto_pai_titulo);
	$('#tipo_atributo_1').val(atributo1);
	$('#tipo_atributo_2').val(atributo2);
	$('#tipo_atributo_3').val(atributo3);

	if(atributo1 > 1){
		$("#form_atributo_1").show();
		$("#label_atributo_1").text(descricao1);
	} else {
		$("#form_atributo_1").hide();
	}

	if(atributo2 > 1){
		$("#form_atributo_2").show();
		$("#label_atributo_2").text(descricao2);
	} else {
		$("#form_atributo_2").hide();
	}

	if(atributo3 > 1){
		$("#form_atributo_3").show();
		$("#label_atributo_3").text(descricao3);
	} else {
		$("#form_atributo_3").hide();
	}

	$('#id_atributo_1').val('');
	$('#atributo').val('');
	$('#id_atributo_2').val('');
	$('#atributo2').val('');
	$('#id_atributo_3').val('');
	$('#atributo3').val('');

	$('#modal').modal('hide');

}
</script>
<div class="table-responsive">
	<table class="table table-striped table-hover table-bordered table-condensed">
		<tr class="active">
			<th style="width: 90px;">
				<a href="#" onclick="$('#modal_corpo').load('<?= $produto ?>&p=<?= $pagina ?>&o=id&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					C&oacute;digo
				</a>
				<?php if($o == "id" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "id" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
			</th>
			<th>
				<a href="#" onclick="$('#modal_corpo').load('<?= $produto ?>&p=<?= $pagina ?>&o=titulo&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					Titulo
				</a>
				<?php if($o == "titulo" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "titulo" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				<form class="form-inline pull-right" action="" method="post" onsubmit="$('#modal_corpo').load('<?= $produto ?>&busca=' + $('#busca').val() + '&time=' + $.now()); return false;"

				                                                               ">
					<div class="input-group">
					    <input type="search" name="busca" id="busca" class="form-control input-sm" value="<?= $_REQUEST['busca'] ?>" placeholder="Pesquisar...">
					    <span class="input-group-btn">
					    	<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i></button>
					    </span>
				    </div>
                </form>
			</th>
		</tr>
	<?php
	try {

		$sql = " SELECT  produto_pai.*,
		 				produto_subcategoria.id_atributo_1,
		 				produto_subcategoria.id_atributo_2,
		 				produto_subcategoria.id_atributo_3,
		 				a1.descricao descricao_1,
		 				a2.descricao descricao_2,
		 				a3.descricao descricao_3
				FROM produto_pai
				INNER JOIN produto_subcategoria ON produto_pai.id_subcategoria = produto_subcategoria.id 
				JOIN produto_tipo_atributo a1 ON produto_subcategoria.id_atributo_1 = a1.id 
				JOIN produto_tipo_atributo a2 ON produto_subcategoria.id_atributo_2 = a2.id 
				JOIN produto_tipo_atributo a3 ON produto_subcategoria.id_atributo_3 = a3.id 
                WHERE produto_pai.status_registro = :status_registro";

         if($_REQUEST['busca'] != ''){
         	$sql .= "AND produto_pai.titulo LIKE '%".$_REQUEST['busca']."%'";
         }

		$vetor["status_registro"] = "A";

		$stLinha = Conexao::chamar()->prepare($sql);
		$stLinha->execute($vetor);
		$qryLinha = $stLinha->fetchAll(PDO::FETCH_ASSOC);
		$totalLinha = count($qryLinha);


		
		$sql .= "  ORDER BY produto_pai.$o $d LIMIT $inicio, $max";
		
		$stArtigo = Conexao::chamar()->prepare($sql);
		$stArtigo->execute($vetor);
		$qryArtigo = $stArtigo->fetchAll(PDO::FETCH_ASSOC);

		

		if(count($qryArtigo)) {

			foreach($qryArtigo as $buscaArtigo) {
			    
			    $retornoTitulo = $buscaArtigo["titulo"];
			    $retornoId        = $buscaArtigo["id"];

			    $atributo_1 	  = $buscaArtigo["id_atributo_1"];
			    $atributo_2       = $buscaArtigo["id_atributo_2"];
			    $atributo_3       = $buscaArtigo["id_atributo_3"];

			    $descricao_1      = $buscaArtigo["descricao_1"];
			    $descricao_2      = $buscaArtigo["descricao_2"];
			    $descricao_3      = $buscaArtigo["descricao_3"];
		?>
		<tr>
			<td class="text-right">
				<a 
					href="#" 
					class="text-muted" 
					onclick="retorna('<?= $retornoId ?>', '<?= $retornoTitulo ?>', '<?= $atributo_1 ?>', '<?= $atributo_2 ?>', '<?= $atributo_3 ?>', '<?= $descricao_1 ?>', '<?= $descricao_2 ?>', '<?= $descricao_3 ?>'); return false;">
					<?= $buscaArtigo["id"] ?>
				</a>
			</td>
			<td>
				<a href="#" 
				   class="text-muted" 
				   onclick="retorna('<?= $retornoId ?>', '<?= $retornoTitulo ?>', '<?= $atributo_1 ?>', '<?= $atributo_2 ?>', '<?= $atributo_3 ?>', '<?= $descricao_1 ?>', '<?= $descricao_2 ?>', '<?= $descricao_3 ?>'); return false;">
				   <?= $buscaArtigo["titulo"] ?>
				</a>
			</td>
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
$produto = $produto . "&o=$o&d=$d";

include_once "$root/privado/sistema/classes/includes/paginacao.php";
paginacao_popup($pagina, $totalLinha, $max, $produto);
?>
<p class="clearfix"></p>