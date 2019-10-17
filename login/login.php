<?php

if (substr($_SERVER['SERVER_NAME'], 0, 3) != "www" && $_SERVER['SERVER_NAME'] != "localhost") {

	// header("Location: http://www.sistema.clinic.com.br/sistema/index.php");
	echo "aqui - login.php";

}
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);


session_start();
$CAMINHO = "http://localhost/veterinaria/";

?>

<!DOCTYPE html>

<html lang="pt-br">
<head>
	<title>Veterinaria</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

	<link rel="shortcut icon" href="<?=$CAMINHO?>configuracao/images/cropped-favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?=$CAMINHO?>configuracao/css/login.css">
	<link rel="stylesheet" type="text/css" href="<?=$CAMINHO?>configuracao/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=$CAMINHO?>configuracao/css/font-awesome.min.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>

	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
	<script type="text/javascript" src="<?=$CAMINHO?>configuracao/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="<?=$CAMINHO?>configuracao/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?=$CAMINHO?>configuracao/js/jquery.ui.touch.js"></script>
	<script type="text/javascript" src="<?=$CAMINHO?>configuracao/js/jquery.validate.js"></script>
	<script type="text/javascript" src="<?=$CAMINHO?>configuracao/js/funcoesLogin.js"></script>
  	
  	<style>
		label.error {
			position: absolute;
			top: 33px;
			left: 39px;
		}

		.alertify-notifier .ajs-message.ajs-success {
			color: #fff;
			background: #2d7d2c!important;
			text-shadow: none!important;
		}
 	</style>
</head>

<body>
    <div class="vertical-center">
		<div class="container text-center">
			<div class="row vertical-align">
				<div class="col-xs-12 col-sm-6 col-lg-4 col-sm-offset-3 col-lg-offset-4">
				    <?php if($_GET['msg_sucesso'] != '') { ?>
				     <script>
					    $( document ).ready(function() {
					       alertify.set('notifier','position', 'top-center');
					       var msg = alertify.success('<?= $_GET['msg_sucesso'] ?>', 0);
							
							$('body').one('click', function(){
							    msg.dismiss();
							 });
					    });
					  </script>
				    <?php } ?>
				    <?php if($_GET['msg'] != '') { ?>
				     <script>
					    $( document ).ready(function() {
					       alertify.set('notifier','position', 'top-center');
					       var msg = alertify.error('<?= $_GET['msg'] ?>', 0);
							
							$('body').one('click', function(){
							    msg.dismiss();
							 });
					    });
					  </script>
				    <?php } ?>

				    <!-- <div class="img-painel"></div> -->

					<div class="panel panel-primary">
						<div class="panel-heading">
							<strong>LOGIN</strong>
						</div>
						<div class="panel-body">
							<form name="form_login" id="form_login" action="verifica.php" method="post">
								<div class="row">
									<div class="col-sm-12 col-md-10 col-md-offset-1 ">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-envelope"></i>
												</span> 
												<input class="form-control" placeholder="Email" name="email" type="email" required autofocus>
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-unlock"></i>
												</span>
												<input class="form-control password" placeholder="Senha" name="senha"  type="password" required>
											</div>
										</div>
										<div class="form-group" >
											<div id="texto"></div>
										</div>
										<div class="form-group">
											<button type="submit" class="btn btn-primary btn-block valida_enviar" disabled>Entrar</button>
										</div>
									</div>
								</div>
							</form>
						</div>

						<div class="panel-footer ">
							<a href="recupera_senha.php" class="text-danger"> Esqueci minha senha </a>
						</div>
	                </div>
				</div>
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

</body>
</html>
