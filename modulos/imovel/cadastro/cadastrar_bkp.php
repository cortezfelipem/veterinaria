<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$stNegocio = Conexao::chamar()->prepare("SELECT *
	FROM negocio
   WHERE status_registro = 'A'");
$stNegocio->execute();
$buscaNegocio = $stNegocio->fetchAll(PDO::FETCH_ASSOC);

$stTipoImovel = Conexao::chamar()->prepare("SELECT *
	   FROM tipo_imovel
	  WHERE status_registro = 'A'");

$stTipoImovel->execute();

$buscaTipoImovel = $stTipoImovel->fetchAll(PDO::FETCH_ASSOC);

$stBairro = Conexao::chamar()->prepare("SELECT DISTINCT bairro
	   FROM imovel
	  WHERE status_registro = 'A'");

$stBairro->execute();
$buscaBairro = $stBairro->fetchAll(PDO::FETCH_ASSOC);

$stMunicipio = Conexao::chamar()->prepare("SELECT *
	   FROM municipio
	  WHERE status_registro = 'A'
	  ORDER BY nome");

$stMunicipio->execute();
$buscaMunicipio = $stMunicipio->fetchAll(PDO::FETCH_ASSOC);
try {

$stArtigo = Conexao::chamar()->prepare("SELECT imovel.*, municipio.nome cidade, estado.nome estado,  negocio.titulo negocio, tipo_imovel.titulo tipo_imovel
										FROM imovel
										LEFT JOIN municipio 
										ON municipio.id = imovel.id_municipio
										LEFT JOIN estado
										ON municipio.id_estado = estado.id
										LEFT JOIN negocio 
										ON negocio.id = imovel.id_negocio
										LEFT JOIN tipo_imovel 
										ON tipo_imovel.id =  imovel.id_tipo 
										WHERE imovel.status_registro = :status_registro
										AND imovel.id = :id");

$stArtigo->execute(array("id" => $id, "status_registro" => "A"));
$buscaArtigo = $stArtigo->fetch(PDO::FETCH_ASSOC);

$idnegocio = $buscaArtigo['id_negocio'];
$idtipo = $buscaArtigo['id_tipo'];
$idmunicipio = $buscaArtigo['id_municipio'];

$stCidade = Conexao::chamar()->prepare("SELECT *
	  FROM municipio
	 WHERE status_registro = 'A'
	   AND id = :id");

$stCidade->execute(array("id" => $idmunicipio));
$buscaCidade = $stCidade->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}
?>

<style>
	/* Always set the map height explicitly to define the size of the div
	* element that contains the map. */
	#map {
		height: 100%;
	}
	/* Optional: Makes the sample page fill the window. */
	#mapa {
		height: 500px;
		padding: 0;
	}
</style>


<?php
var_dump($_POST);
if($_POST) {

	include_once "sql.php";

}

// function xml2array($xml) {
// 	$arr = array();
// 	foreach ($xml as $element) {
// 	  $tag = $element->getName();
// 	  $e = get_object_vars($element);
// 	  if (!empty($e)) {
// 		$arr[$tag] = $element instanceof SimpleXMLElement ? xml2array($element) : $e;
// 	  }
// 	  else {
// 		$arr[$tag] = trim($element);
// 	  }
// 	}
// 	return $arr;
//   }

// $address = $buscaArtigo['logradouro'].", ".$buscaArtigo['numero']." - ".$buscaArtigo['bairro'].", ".$buscaCidade['nome']." - ".$buscaArtigo['estado'];
// $lat = null;
// $lng = null;
// $request_url = 'http://maps.google.com/maps/api/geocode/xml?address='.urlencode( $address );
// $xml = simplexml_load_file($request_url) or die("url not loading");
// $arr = xml2array($xml);
// Format: Longitude, Latitude, Altitude
// $lat = $arr['result']['geometry']['location']['lat'];
// $lng = $arr['result']['geometry']['location']['lng'];



// $tabelaAnexo = "imovel_anexo";
// $relacionamentoTabelaAnexo = "artigo";

$tabelaFoto = "imovel_foto";
$relacionamentoTabelaFoto = "imovel";

// $tabelaVideo = "imovel_video";
// $relacionamentoTabelaVideo = "artigo";
?>
<form class="form-horizontal validar_formulario" id="form-fornecedor" action="" enctype="multipart/form-data" method="post">

	<ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-sm hidden-xs"> Dados Gerais</span></a></li>
	</ul>

	<div class="tab-content">

			<div role="tabpanel" class="tab-pane active" id="geral">

				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home">Caracteristicas do Imovel</a></li>
					<li><a data-toggle="tab" href="#menu1">Dados de Endere&ccedil;o</a></li>
					<li><a data-toggle="tab" href="#menu2">Dados Complementares</a></li>
				</ul>

				<div class="tab-content">
				<div id="home" class="tab-pane fade in active">

					<div class="form-group">
						<label for="mostrar_site" class="col-sm-2 control-label">Aparecer no site?</label>
						<div class="col-sm-10">
							<select class="form-control" name="mostrar_site" id="mostrar_site">

							<?php 
								// echo $buscaArtigo['mostrar_site']." aqui";
							if($buscaArtigo['mostrar_site'] == "S"){?>

							<option selected value="<?= $buscaArtigo['mostrar_site']?>"><?= $buscaArtigo['mostrar_site']?></option>
							<option value="N">N</option>

							<?php }elseif ($buscaArtigo['mostrar_site'] == "N") { ?>
								<option selected value="<?= $buscaArtigo['mostrar_site']?>"><?= $buscaArtigo['mostrar_site']?></option>
								<option value="S">S</option>
							
							<? }else{?>

								<option value="S" selected>Sim</option>
								<option value="N">Não</option>
							

							<?php } ?>
						
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="anot_adm" class="col-sm-2 control-label">Anota&ccedil;&otilde;es do administrador</label>
						<div class="col-sm-10">
							<textarea name="anot_adm" id="anot_adm" class="form-control editor"> <?= $buscaArtigo['anot_adm']?> </textarea>
						</div>
					</div>

					<div class="form-group">
						<label for="id_negocio" class="col-sm-2 control-label">Tipo de neg&oacute;cio</label>
						<div class="col-sm-10">
							<select class="form-control" name="id_negocio" id="id_negocio">

							<?php if($buscaArtigo){?>

							<option selected value="<?= $buscaArtigo['id_negocio']?>"><?= $buscaArtigo['negocio']?></option>

							<?php }else{?>

							<option selected value="" selected>Escolha um Tipo de neg&oacute;cio...</option>

							<?php } ?>
								<?php foreach ($buscaNegocio as $key => $negocio) { 
										if($buscaArtigo['id_negocio'] != $negocio['id']){?>
									<option value="<?= $negocio['id']?>"><?= $negocio['titulo']?></option>
								<?php 	}
									  } ?>
							</select>
						</div>
					</div>
				
					<div class="form-group">
						<label for="id_tipo" class="col-sm-2 control-label">Tipo de Im&oacute;vel</label>
						<div class="col-sm-10">
							<select class="form-control" name="id_tipo" id="id_tipo">
							<?php if($buscaArtigo){?>

							<option value="<?= $buscaArtigo['id_tipo']?>"><?= $buscaArtigo['tipo_imovel']?></option>

							<?php }else{?>

							<option selected value="" selected>Escolha um Tipo de Im&oacute;vel<...</option>

							<?php } ?>
								<?php foreach ($buscaTipoImovel as $key => $tipo_imovel) { 
										if($buscaArtigo['id_tipo'] != $tipo_imovel['id']){?>
									<option name="id_tipo" value="<?= $tipo_imovel['id']?>"><?= $tipo_imovel['titulo']?></option>
								<?php	} 
									 } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="valor" class="col-sm-2 control-label">Valor do Imovel / Valor do Aluguel</label>
						<div class="col-sm-10">
							<input type="number" name="valor" id="valor" value="<?= $buscaArtigo['valor'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">
						</div>
					</div>

					<div class="form-group">
						<label for="num_suites" class="col-sm-2 control-label">Numero de Suites</label>
						<div class="col-sm-10">
							<input type="number" name="num_suites" id="num_suites" value="<?= $buscaArtigo['num_suites'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">
						</div>
					</div>

					<div class="form-group">
						<label for="num_quartos" class="col-sm-2 control-label">Numero de Quartos</label>
						<div class="col-sm-10">
							<input type="number" name="num_quartos" id="num_quartos" value="<?= $buscaArtigo['num_quartos'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">
						</div>
					</div>

					<div class="form-group">
						<label for="artigo" class="col-sm-2 control-label">Artigo</label>
						<div class="col-sm-10">
							<textarea name="artigo" id="artigo" class="form-control editor"> <?= $buscaArtigo['artigo'] ?></textarea>
						</div>
					</div>
				</div>

				<div id="menu1" class="tab-pane fade">
					<div class="form-group">
						<label for="id_municipio" class="col-sm-2 control-label">Municipio</label>
						<div class="col-sm-10">
							<select class="form-control" name="id_municipio" id="id_municipio" class="selectpicker" data-show-subtext="true" data-live-search="true">
							<?php if($buscaArtigo){?>

							<option value="<?= $buscaArtigo['id_municipio']?>" selected><?= $buscaCidade['nome']?></option>

							<?php }else{?>

							<option value="" selected>Escolha um Muncipio...</option>

							<?php } ?>
							
								<?php foreach ($buscaMunicipio as $key => $municipio) { ?>
									<option name="id_municipio" value="<?= $municipio['id']?>" ><?= $municipio['nome']?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="logradouro" class="col-sm-2 control-label">Logradouro</label>
						<div class="col-sm-10">
							<input type="text" name="logradouro" id="logradouro" value="<?= $buscaArtigo['logradouro'] ?>" class="form-control required" required placeholder="Informe o t&iacute;tulo...">
						</div>
					</div>

					<div class="form-group" id="bairro_antigo">
						<label for="bairro" class="col-sm-2 control-label">Bairro</label>
						<div class="col-sm-10">
							<select class="form-control" name="bairro" id="bairro">

							<?php if($buscaArtigo){?>

							<option selected value="<?= $buscaArtigo['bairro']?>"><?= $buscaArtigo['bairro']?></option>

							<?php }else{?>

								<option selected value="" selected>Escolha um Bairro...</option>

							<?php } ?>
								<?php foreach ($buscaBairro as $key => $bairro) { 
										if($buscaArtigo['bairro'] != $bairro['bairro']){?>
									<option value="<?= $bairro['bairro']?>"><?= $bairro['bairro']?></option>
								<?php 	}
									} ?>
							</select>
						</div>
					</div>

					<div class="form-group" id="bairro_novo" style="display: none">
						<label for="bairro" class="col-sm-2 control-label">Novo Bairro</label>
						<div class="col-sm-10">
							<input type="text" name="bairro" id="bairro" value="" class="form-control required" required placeholder="Informe o bairro...">
						</div>
					</div>
					
					<div id="novo_bairro" class="form-group">
						<label class="col-sm-2 control-label">Adicionar Bairro</label>
						<div class="col-sm-10">
							<button type="button" value="Click" class="btn btn-success">Novo Bairro</button>
						</div>
					</div>
					
					<div class="form-group">
						<label for="numero" class="col-sm-2 control-label">Numero</label>
						<div class="col-sm-10">
							<input type="text" name="numero" id="numero" value="<?= $buscaArtigo['numero'] ?>" class="form-control required" required placeholder="Informe o numero...">
						</div>
					</div>
					
					<div class="form-group">
						<label for="cep" class="col-sm-2 control-label">CEP</label>
						<div class="col-sm-10">
							<input type="text" name="cep" id="cep" onkeypress="mascara(this, '#####-###')" value="<?= $buscaArtigo['cep'] ?>" class="form-control required" required placeholder="Informe o cep...">
							<!-- <input type="hidden" name="latitude" id="latitude" value="<?= $lat?>"> -->
							<!-- <input type="hidden" name="longitude" id="longitude" value="<?= $lng?>"> -->
						</div>
					</div>

					<div class="form-group">
						<label for="complemento" class="col-sm-2 control-label">Complemento</label>
						<div class="col-sm-10">
							<input type="text" name="complemento" id="complemento" value="<?= $buscaArtigo['complemento'] ?>" class="form-control" placeholder="Informe o t&iacute;tulo...">
						</div>
					</div>
					
					<div class="form-group">
						<label for="area" class="col-sm-2 control-label">Area</label>
						<div class="col-sm-10">
							<input type="text" name="area" id="area" value="<?= $buscaArtigo['area'] ?>" class="form-control" placeholder="Informe a area...">
						</div>
					</div>

				<!-- 	<div id="mapa" class="col-sm-offset-2 col-sm-10">
						<div id="map"></div>
					</div> -->
				</div>

				<div id="menu2" class="tab-pane fade">
					<div class="form-group">
						<label for="observacao" class="col-sm-2 control-label">Observa&ccedil;&atilde;o</label>
						<div class="col-sm-10">
							<textarea name="observacao" id="observacao" class="form-control editor"><?= $buscaArtigo['observacao'] ?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label for="mais_info" class="col-sm-2 control-label">Mais Informa&ccedil;&otilde;es</label>
						<div class="col-sm-10">
							<textarea name="mais_info" id="mais_info" class="form-control editor"><?= $buscaArtigo['mais_info'] ?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label for="caracteristicas" class="col-sm-2 control-label">Caracteristicas</label>
						<div class="col-sm-10">
							<textarea name="caracteristicas" id="caracteristicas" class="form-control editor"><?= $buscaArtigo['caracteristicas'] ?></textarea>
						</div>
					</div>

					<div class="container" style="display: flex; flex-direction: column; justify-content: center; align-items: center; flex-wrap: wrap; border: 1px solid #DDD;">
						<h4 style="font-size: 160%; font-weight: 700; background-color: #DDD; width: 100%; text-align: center;">Fotos do Im&oacute;vel</h4>
						<?php include "$root/privado/sistema/classes/galerias/foto.php"; ?>
						
					</div>

				</div>
				
				<p class="clearfix"></p>

				<nav class="pull-right hidden-xs hidden-sm">
					<button type="submit" onclick="redirect();" class="btn btn-success pull right"><i class="fa fa-floppy-o"></i> Salvar</button>
					<a href="<?= $caminhoTela ?>" class="btn btn-warning pull right"><i class="fa fa-ban"></i> Cancelar</a>
				</nav>

				<nav class="visible-xs visible-sm">
					<button type="submit" onclick="redirect();"  class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> Salvar</button>
					<a href="<?= $caminhoTela ?>" class="btn btn-warning btn-block"><i class="fa fa-ban"></i> Cancelar</a>
				</nav>

				<p class="clearfix"></p>
									
			</div>
	
		</div>

		

	</div>
	
</form>

<script>

function mascara(t, mask){
 var i = t.value.length;
 var saida = mask.substring(2,1);
 var texto = mask.substring(i);
 if (texto.substring(0,1) != saida){
	 t.value += texto.substring(0,1);
 }
 }

// function initMap() {
// 		var latitude = document.getElementById('latitude').value;
// 		var longitude = document.getElementById('longitude').value;
		
//         var myLatLng = {lat: Number(latitude), lng: Number(longitude)};
//         // Create a map object and specify the DOM element
//         // for display.
//         var map = new google.maps.Map(document.getElementById('map'), {
//           center: myLatLng,
//           zoom: 16
//         });

//         // Create a marker and set its position.
//         var marker = new google.maps.Marker({
//           map: map,
//           position: myLatLng,
//           title: 'Hello World!'
//         });
// }

function removeElement(id) {
    var elem = document.getElementById(id);
    return elem.parentNode.removeChild(elem);
}

$('#novo_bairro').click(function() {
	document.getElementById("bairro_novo").style.display='block';
	removeElement("bairro_antigo");
	removeElement("novo_bairro");
})

function redirect()
{
    window.location.href="http://www.augustoiimoveis.com.br/www/sistema/inicio.php?&t=55&id=0";
}
</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXgAY7VPrFm7FH11AMm6MF69IyHWDaWLM&callback=initMap" async defer></script> -->