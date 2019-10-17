<?php
include "../../../../privado/sistema/conexao.php";

$pagina = isset($p) ? $p : 1;
$max = 12;
$inicio = $max * ($pagina - 1);

$valor_id = $_REQUEST['id_tipo_atributo'];

$produto = "popup/geral/atributo_produto_filho.php?t=$t&id_tipo_atributo=$valor_id";

if(empty($o)) $o = "descricao";
if(empty($d)) $d = "ASC";

?>
<script>
function retorna(id, atributo) {


	var qual_atributo = <?= $_REQUEST['atributo']?>;

	if(qual_atributo == 1){
	   $('#id_atributo_1').val(id);
	   $('#atributo').val(atributo);
	}

	if(qual_atributo == 2){
	   $('#id_atributo_2').val(id);
	   $('#atributo2').val(atributo);
	}

	if(qual_atributo == 3){
	   $('#id_atributo_3').val(id);
	   $('#atributo3').val(atributo);
	}
	
    $("#tabela_principal").hide();
	$('#modal').modal('hide');

}
</script>
<!--
<div class="col-md-12 text-center" 
	style="height: 101%;
    position: absolute;
    top: -1px;
    left: 0;
    background: rgba(0,0,0,.5); 
    display: flex;          
    flex-direction: column;  
    justify-content: center; 
    align-items: center;" 
    id="carregando">
	<img style="height: 60px" class="img-responsive" src="https://www.justfit.com.br/App_Themes/SiteNovo/img/Pages/Cadastro/carregando_0.gif">
</div>
-->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="tabela_principal" style="margin-bottom: 50px">
<!--	<table class="table table-striped table-hover table-bordered table-condensed">
		<tr class="active">
			<th style="width: 90px;">
				<a href="#" style="line-height: 30px" onclick="$('#modal_corpo').load('<?= $produto ?>&p=<?= $pagina ?>&o=id&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					C&oacute;digo
				</a>
				<?php if($o == "id" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "id" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
			</th>
			<th>
				<a href="#" style="line-height: 30px"  onclick="$('#modal_corpo').load('<?= $produto ?>&p=<?= $pagina ?>&o=descricao&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					Descri&ccedil;&atilde;o
				</a>
				<?php if($o == "descricao" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "descricao" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
			    <form class="form-inline pull-right" action="" method="post" onsubmit="$('#modal_corpo').load('<?= $produto ?>&atributo=<?=$_REQUEST['atributo']?>&busca=' + $('#busca').val() + '&time=' + $.now()); return false;">
					<div class="input-group">
					    <input type="search" name="busca" id="busca" class="form-control input-sm" value="<?= $busca ?>" placeholder="Pesquisar...">
					    
					    <span class="input-group-btn">
					    	<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i></button>
					    </span>
				    </div>
                </form>
			</th>
		</tr>-->

	<div class="col-md-12" style="padding-bottom: 10px;padding-top: 10px">
		 <form class="form-inline pull-right" action="" method="post" onsubmit="$('#modal_corpo').load('<?= $produto ?>&atributo=<?=$_REQUEST['atributo']?>&busca=' + $('#busca').val() + '&time=' + $.now()); return false;">
					<div class="input-group">
					    <input type="search" name="busca" id="busca" class="form-control" value="<?= $busca ?>" placeholder="Pesquisar...">
					    
					    <span class="input-group-btn">
					    	<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
					    </span>
				    </div>
                </form>
                <br><br>	
	</div>
	<?php
	try {

		$sql = "SELECT *
                FROM produto_atributo 
                WHERE id_tipo_atributo = :id_tipo_atributo
                AND status_registro != :status_registro 

                ";

         if($_REQUEST['busca'] != ''){
         	$sql .= "AND descricao LIKE '%".$_REQUEST['busca']."%'";
         }


        $vetor["id_tipo_atributo"] = $_REQUEST['id_tipo_atributo'];

		$vetor["status_registro"] = "I";

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
<!--
				<tr>
					<td class="text-right"><a href="#" class="text-muted" onclick="retorna('<?= $retornoId ?>', '<?= $retornoDescricao ?>'); return false;"><?= $buscaArtigo["id"] ?></a></td>
					<td><a href="#" class="text-muted" onclick="retorna('<?= $retornoId ?>', '<?= $retornoDescricao ?>'); return false;"><?= $buscaArtigo["descricao"] ?></a></td>
				</tr>

-->
		<div class="col-md-3"  style="margin-bottom: 30px">	
		<a href="#" class="text-muted" style="text-decoration: none" onclick="retorna('<?= $retornoId ?>', '<?= $retornoDescricao ?>'); return false;">
			<div class="text-center" 
				 style="height: 90px; 
				 		width: 100%;
				 		background: #ddd;
				 		background-image: url('<?=$CAMINHO?>/imagens/<?=$buscaArtigo["foto"]?>'); 
				 		background-size:100% 100%; 
				 		background-repeat: no-repeat; 
				 		background-position: center; 
				 		display: flex;          
	    				flex-direction: column;  
	    				justify-content: center; 
	    				align-items: center;
				 		">
				<!--<img class="img-responsive" src="<?=$CAMINHO?>/imagens/<?=$buscaArtigo["foto"]?>">-->
				<span style="padding: 3px;background: rgba(0,0,0, .5); color: #fff; width: 80%"><b><?=$buscaArtigo["descricao"]?></b></span>
			</div>
		</a>
		</div>
		<?php
		
			}
		
		} 


		?>

		<div class="col-md-3" style="margin-bottom: 20px">	
		<a href="#" class="text-muted" style="text-decoration: none" onclick="retorna(1, 'Sem Atributo'); return false;">
			<div class="text-center" 
				 style="height: 90px; 
				 		width: 100%;
				 		background: #ddd;
				 		display: flex;          
	    				flex-direction: column;  
	    				justify-content: center; 
	    				align-items: center;
				 		">
				<!--<img class="img-responsive" src="<?=$CAMINHO?>/imagens/<?=$buscaArtigo["foto"]?>">-->
				<span style="padding: 3px;background: rgba(0,0,0, .5); color: #fff; width: 80%"><b>Sem Atributo</b></span>
			</div>
		</a>
		</div>


		<?
		
		

	} catch (PDOException $e) {

		echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar os registros.");
		echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

	}
	?>
	
</div>
<div class="col-md-12 col-sm-12 col-xs-12 text-center">	
<?php
$produto = $produto . "&o=$o&d=$d";

include_once "$root/privado/sistema/classes/includes/paginacao.php";
paginacao_popup($pagina, $totalLinha, $max, $produto);
?>
</div>
<p class="clearfix"></p>