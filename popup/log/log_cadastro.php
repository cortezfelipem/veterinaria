<?php
include "../../../../privado/sistema/conexao.php";

$pagina = isset($p) ? $p : 1;
$max = 10;
$inicio = $max * ($pagina - 1);

$link = "popup/log/log_cadastro.php?t=$t&id=$id";

if(empty($o)) $o = "controle_log_acesso.data_acesso";
if(empty($d)) $d = "DESC";

?>
<form class="form-horizontal" id="form-busca" action="" method="post" onsubmit="return enviaBuscaPopup(this);">
<div class="table-responsive">
	<table class="table table-striped table-hover table-bordered table-condensed">
		<tr class="active">
			<th style="width: 20%;">
				<a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $pagina ?>&o=controle_log_acesso.data_acesso&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					Data
				</a>
				<?php if($o == "controle_log_acesso.data_acesso" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "controle_log_acesso.data_acesso" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
				<div class="pull-right" style="width: 100px;">
			    	<input type="search" name="data_acesso" id="data_acesso" class="form-control input-sm data_popup" value="<?= $data_acesso ?>" placeholder="Pesquisar...">
			    </div>
			</th>
			<th style="width: 20%;">
				<select name="tipo_acesso" id="tipo" class="form-control input-sm">
					<option value="">Tipo de Acesso</option>
					<option value="I" <? if($tipo_acesso == "I") echo "selected"; ?>>Inclus&atilde;o</option>
					<option value="E" <? if($tipo_acesso == "E") echo "selected"; ?>>Exclus&atilde;o</option>
					<option value="A" <? if($tipo_acesso == "A") echo "selected"; ?>>Altera&ccedil;&atilde;o</option>
				</select>
			</th>
			<th>
				<a href="#" onclick="$('#modal_corpo').load('<?= $link ?>&p=<?= $pagina ?>&o=usuario.usuario&d=<?= empty($d) || $d == "DESC" ? "ASC" : "DESC" ?>&time='+$.now()); return false;">
					Usu&aacute;rio
				</a>
				<?php if($o == "usuario.usuario" && $d == "ASC") { ?><i class="fa fa-caret-up"></i><?php } ?>
				<?php if($o == "usuario.usuario" && $d == "DESC") { ?><i class="fa fa-caret-down"></i><?php } ?>
			    <div class="pull-right" style="width: 200px;">
			    	<input type="search" name="usuario" id="usuario" class="form-control input-sm" value="<?= $usuario ?>" placeholder="Pesquisar...">
			    </div>
			</th>
			<th style="width: 20%;" class="text-primary">
				Origem
				<button class="btn btn-primary btn-sm pull-right" type="submit"><i class="fa fa-search"></i></button>
			</th>
		</tr>
	<?php
	try {

		$sql = "SELECT controle_log_acesso.*,
					   usuario.usuario
				  FROM controle_log_acesso,
				  	   usuario
			 	 WHERE controle_log_acesso.id_usuario = usuario.id
				   AND controle_log_acesso.id_cliente = :id_cliente
				   AND controle_log_acesso.id_menu = :id_tela
				   AND controle_log_acesso.id_registro = :id ";

		$vetor["id_cliente"] = $idCliente;
		$vetor["id_tela"] = $t;
		$vetor["id"] = $id;

		if($usuario != "") {

			$sql .= "AND usuario.usuario LIKE :usuario ";
			$vetor['usuario'] = "%$usuario%";
			$link .= "&usuario=" . urlencode($usuario);

		}

		if($tipo_acesso != "") {

			$sql .= "AND controle_log_acesso.tipo_acesso = :tipo_acesso ";
			$vetor['tipo_acesso'] = $tipo_acesso;
			$link .= "&tipo_acesso=$tipo_acesso";

		}

		if($data_acesso != "") {

			$sql .= "AND DATE(controle_log_acesso.data_acesso) = :data_acesso ";
			$vetor['data_acesso'] = formata_data_banco($data_acesso);
			$link .= "&data_acesso=$data_acesso";

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

				switch($buscaArtigo['tipo_acesso']) {

					case "I": $tipoAcesso = "Inclus&atilde;o"; break;
					case "E": $tipoAcesso = "Exclus&atilde;o"; break;
					case "A": $tipoAcesso = "Altera&ccedil;&atilde;o"; break;
				}
		?>
		<tr>
			<td class="text-right"><?= formata_data_hora($buscaArtigo['data_acesso']) ?></td>
			<td><?= $tipoAcesso ?></td>
			<td><?= $buscaArtigo['usuario'] ?></td>
			<td class="text-right"><?= $buscaArtigo['ip'] ?></td>
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
</form>

<?php
$link = $link . "&o=$o&d=$d";

include_once "$root/privado/sistema/classes/includes/paginacao.php";
paginacao_popup($pagina, $totalLinha, $max, $link);
?>
<p class="clearfix"></p>
<script>
$(".data_popup").mask("99/99/9999");

function enviaBuscaPopup(form) {

	$.ajax({
	    type: "POST",
	    url: '<?= $link ?>',
	    data: jQuery(form).serializeArray(),
	    success: function(data) {
	    	jQuery("#modal_corpo").html(data);
	    }
	});

	return false;

}
</script>