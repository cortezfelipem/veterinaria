<?php
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
session_start();
include "../conexao.php";
include "../classes/modelo_email.php";

$CAMINHO = "http://www.sistema.augustoiimoveis.com.br/www/";
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

<?php
if($_REQUEST['email'] != "") {

	

	if($_SESSION['validacao']) {

		$msg = "Selecione a imagem correta.";

	} else if(empty($_POST['email'])) {

		$msg = "Forne&ccedil;a seu email.";

	} else {
	    
	    $stUsuario = Conexao::chamar()->prepare("SELECT usuario.id,
														usuario.email,
														usuario.usuario,
		 												cliente.nome_fantasia cliente_nome,
		 												cliente.email cliente_email
            									   FROM usuario
            							     INNER JOIN cliente
													 ON usuario.id_cliente = cliente.id
            									  WHERE usuario.email = :email
            									    AND usuario.status_registro = :status_registro");
	    
	    $stUsuario->execute(array("email" => $email, "status_registro" => "A"));
	    $buscaUsuario = $stUsuario->fetch(PDO::FETCH_ASSOC);

		if(!empty($buscaUsuario['id'])) {

			
			
			$stUpdate = Conexao::chamar()->prepare("UPDATE usuario
                									   SET recupera_senha = :recupera_senha
                									 WHERE id = :id");
			$hash_novo = gerarHash($buscaUsuario['email']);
			$stUpdate->bindParam("recupera_senha", $hash_novo, PDO::PARAM_STR);
			$stUpdate->bindParam("id", $buscaUsuario['id'], PDO::PARAM_INT);
			
			$update = $stUpdate->execute();
		
			if($update) {
				
				$nome = $buscaUsuario['cliente_nome'];
				$remetente = $buscaUsuario['cliente_email'];
				$to = $buscaUsuario['email'];

				$headers = "From: $nome <$remetente> \r\n";
				$headers.= "Return-Path: $nome <$remetente> \r\n";
				$headers.= "Content-Type: text/html; charset=ISO-8859-1 ";
				$headers.= "MIME-Version: 1.0 ";

				$subject = "Recupera&ccedil;&atilde;o de Senha ( $nome ) ";
				$titulo = "Recupera&ccedil;&atilde;o de Senha ( $nome )";
				$corpo = "<p>Ol&aacute; $buscaUsuario[usuario], conforme solicitado segue o link para cadastrar nova senha do sistema.</p>";
				$link = "$CAMINHO/sistema/cadastro_senha.php?token=$hash_novo";
				$titulo_botao = "Link";
				$rodape = "<p>&nbsp;</p><p>Origem: ".$_SERVER['REMOTE_ADDR'].", enviado em " . date("d/m/Y H:i") . " por $nome</p>";

				$html = recupera_senha_email($titulo,$corpo,$link,$titulo_botao,$rodape);
				$vai = mail($to, html_entity_decode($subject), $html, $headers);
			
				if($vai) {
					
					echo "<script>window.location='index.php?msg_sucesso=".urlencode("Um e-mail foi enviado para o endere&ccedil;o $to")."&type=success'</script>";
				
				

				} else {

					$msg = "Erro ao enviar o email, tente novamente mais tarde.";

				}

			} else {

				$msg = "Erro ao recuperar senha, tente novamente mais tarde.";

			}

		} else {

			$msg = "Email n&atilde;o encontrado.";

		}

	}
}
?>

    <div class="vertical-center">

		<div class="container text-center">
			<div class="img-painel"></div>
			<div class="row vertical-align">
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
							<strong> Informe o email para continuar</strong>
						</div>
						<div class="panel-body">
							<form name="form_login" id="form_login" action="" method="post">
								<div class="row">
									<div class="col-sm-12 col-md-10 col-md-offset-1 ">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-envelope"></i>
												</span> 
												<input class="form-control required" 
													   placeholder="Email"
													   name="email" 
													   type="email" 
													   required 
													   autofocus>
											</div>
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
						<div class="panel-footer ">

							<a href="index.php" class="text-danger"> Login </a>


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