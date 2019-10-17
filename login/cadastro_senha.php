<?php
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
session_start();
include "../../privado/sistema/conexao.php";
$CAMINHO = "http://www.augustoiimoveis.com.br/www";

$hash = secure($_REQUEST['token']);

$stUsuario = Conexao::chamar()->prepare("SELECT *
										 FROM usuario
										 WHERE recupera_senha = :recupera_senha
										 AND status_registro = :status_registro
										 LIMIT 1");

$stUsuario->execute(array("recupera_senha" => $hash, "status_registro" => "A"));
$valor_usuario = $stUsuario->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Ing&aacute; Digital Ltda.</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

	<link rel="shortcut icon" href="configuracao/images/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="configuracao/css/login.css">
	<link rel="stylesheet" type="text/css" href="configuracao/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="configuracao/css/font-awesome.min.css">

	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>
	

	<script type="text/javascript" src="configuracao/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="configuracao/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="configuracao/js/jquery.ui.touch.js"></script>
	<script type="text/javascript" src="configuracao/js/jquery.validate.js"></script>
	<script type="text/javascript" src="configuracao/js/funcoesLogin.js"></script>
	


	
	
</head>
<body>


<?php

if($_REQUEST['senha'] != "" && $_REQUEST['conf_senha'] != '') {
	if($_SESSION['validacao']){

		$msg = "Selecione a imagem correta.";

	} else if($_REQUEST['senha'] != $_REQUEST['conf_senha']) {
		
				$msg = "As senhas s&atilde;o diferentes";
		
	} else {



		$stUpdate = Conexao::chamar()->prepare("UPDATE usuario
												SET senha = :senha,
													recupera_senha = :recupera_senha
												WHERE id = :id");
												  
		$stUpdate->bindValue("senha", password_hash($_REQUEST['senha'], PASSWORD_DEFAULT) , PDO::PARAM_STR);
		$stUpdate->bindParam("recupera_senha", gerarHash($valor_usuario['email']), PDO::PARAM_STR);
		$stUpdate->bindParam("id", $valor_usuario['id'], PDO::PARAM_INT);
		$update = $stUpdate->execute();

		if($update) {
			echo "<script>window.location='index.php?msg_sucesso=".urlencode("Senha alterada com sucesso!")."'</script>";
		} else {
			$msg = "Erro ao cadastrar senha, tente novamente mais tarde.";
		}
		
	}


} else {
	$msg = "Preencha todos os campos";
}

?>
<div class="vertical-center">

		<div class="container text-center">
			<div class="img-painel"></div>
			<div class="row vertical-align">

			<?php
			if( $valor_usuario['id'] != ''){
			?>
				<div class="col-xs-12 col-sm-6 col-lg-4 col-sm-offset-3 col-lg-offset-4">
				  <?php if($msg) { ?>

				     <script>
					    $( document ).ready(function() {
					       alertify.set('notifier','position', 'top-center');
					       var msg = alertify.error('<?= $msg ?>', 0);
							
							$('body').one('click', function(){
							    msg.dismiss();
							 });
  
					    });
					  </script>
				    <?php } ?>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<strong>Cadastrar nova senha</strong>
						</div>
						<div class="panel-body">
							<form name="form_nova_senha" id="form_login" action="" method="post">
								<div class="row">
									<div class="col-sm-12 col-md-12">

										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-lock"></i>
												</span>
												<input class="form-control required" placeholder="Senha" name="senha" id="senha"  type="password" required>
											</div>
											<div id="mostra_senha" class="col-sm-12 hidden" style="margin-top:20px;">
												<div class="progress" style="margin-bottom: 0;">
													<div class="progress-bar progress-bar-danger progress-bar-striped" id="progresso_senha" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
												<p class="text-center text-danger"><small id="texto_senha"></small></p>
											</div>
										</div>

										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-lock"></i>
												</span>
												<input class="form-control password conf_senha" placeholder="Confirme a senha" name="conf_senha" id="conf_senha" type="password" required>
											</div>
											<p class="text-center text-danger"><small id="texto_conf_senha"></small></p>
										</div>

										<div class="form-group">
											<div id="texto"></div>
										</div>

										<div class="form-group">
											<button type="submit" class="btn btn-primary btn-block valida_enviar" disabled>Enviar</button>
										</div>

									</div>
								</div>
							</form>
						</div>
	                </div>
				</div>

				<?php } else { 

					echo "<script>window.location='index.php?msg=".urlencode("Token expirado, realize nova solicita&ccedil;&atilde;o de recupera&ccedil;&atilde;o de senha..")."'</script>";

			    } ?>
			</div>
		</div>
	</div>

<input type="hidden" name="valor_cidade" id="valor_cidade" value="<?php echo $CAMINHO ?>" />



<script type="text/javascript">

	function getCaptcha(str) {

		var url = $('#valor_cidade').val();

		if (str.length != 0) { 

			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					$('#texto').html(this.responseText);
				}
			};
			
			xmlhttp.open("GET", url+"/captcha/captcha.php?q="+str, true);
			xmlhttp.send();
		}
		
	}

	$( document ).ready(function() {
		getCaptcha(9);
		
	});

</script>

	<script>

	$('#conf_senha').on('keyup', function () {
		var msg = $("#texto_conf_senha");
		if($('#conf_senha').val() != ''){
			if ($('#senha').val() == $('#conf_senha').val()) {
				msg.removeClass('text-danger text-warning text-success');
				msg.addClass('text-success');
				msg.html('Senhas s&atilde;o correspondentes');
			} else {
				msg.removeClass('text-danger text-warning text-success');
				msg.addClass('text-danger');
				msg.html('As senha s&atilde;o diferentes');
			}
		}
   
	});
	
	$("#senha").on("keyup", function(){

	var forca = 0;
	var senha = $("#senha").val();
	var mostra = $("#progresso_senha");
	var texto = $("#texto_senha");

	if((senha.length >= 4) && (senha.length <= 7)){
		forca += 10;
	} else if(senha.length>7){
		forca += 25;
	}
	if(senha.match(/[a-z]+/)){
		forca += 10;
	}
	if(senha.match(/[A-Z]+/)){
		forca += 20;
	}
	if(senha.match(/\d+/)){
		forca += 20;
	}
	if(senha.match(/\W+/)){
		forca += 25;
	}

	mostra.html(forca + "%");
	mostra.css({ width: forca + "%" });

	if(forca < 30) {

		mostra.removeClass('progress-bar-warning progress-bar-success');
		mostra.addClass('progress-bar-danger');
		texto.removeClass('text-warning text-primary text-success');
		texto.addClass('text-danger');
		texto.html('Muito Fraca, misture letras e n&uacute;meros');

	} else if((forca >= 30) && (forca < 60)){

		mostra.removeClass('progress-bar-danger progress-bar-success');
		mostra.addClass('progress-bar-warning');
		texto.removeClass('text-danger text-primary text-success');
		texto.addClass('text-warning');
		texto.html('Ainda Fraca, utilize alguns s&iacute;mbolos, como ! @ # % * _ + =');

	} else if((forca >= 60) && (forca < 85)){

		mostra.removeClass('progress-bar-danger progress-bar-success progress-bar-warning');
		texto.removeClass('text-danger text-warning text-success');
		texto.addClass('text-primary');
		texto.html('Fraca, misture letras mai&uacute;sculas e min&uacute;sculas');

	} else if(senha.length < 8) {

		mostra.removeClass('progress-bar-danger progress-bar-success progress-bar-warning');
		texto.removeClass('text-danger text-warning text-success');
		texto.addClass('text-primary');
		texto.html('A senha deve conter pelo menos 8 caracteres');

	} else {

		mostra.removeClass('progress-bar-danger progress-bar-warning');
		mostra.addClass('progress-bar-success');
		texto.removeClass('text-danger text-warning text-primary');
		texto.addClass('text-success');
		texto.html('Senha Segura');

	}
});

	
	
	</script>
</body>
</html>