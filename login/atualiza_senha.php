<?php
session_start();
include "../conexao.php";
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
	
	<script type="text/javascript" src="configuracao/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="configuracao/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="configuracao/js/jquery.ui.touch.js"></script>
	<script type="text/javascript" src="configuracao/js/jquery.validate.js"></script>
	<script type="text/javascript" src="configuracao/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="configuracao/js/funcoesLogin.js"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
</head>
<body>

<?php
if($senha_antiga != "" && $senha != "" && $conf_senha != "") {

	require_once "../classes/recaptchalib.php";

	$cap = new GoogleRecaptcha();
	$verified = $cap->VerifyCaptcha($_POST['g-recaptcha-response']);

	if(!$verified) {

		$msg = "O Captcha n&atilde;o foi solucionado corretamente, tente novamente.";

	} else if($senha != $conf_senha) {
	    
	    $msg = "As senhas informadas n&atilde;o conferem.";
	    
	} else if($senha == $senha_antiga) {
	    
	    $msg = "A nova senha n&atilde;o pode ser igual a antiga.";
	    
	} else if(strlen($senha) < 8 || senhaSegura($senha) == false) {
	    
	    $msg = "A senha informada n&atilde;o est&aacute; no padr&atilde;o de seguran&ccedil;a do sistema, releia as instru&ccedil;&otilde;es no &iacute;cone de ajuda!";
	    
	} else {
	    
	    $stUsuario = Conexao::chamar()->prepare("SELECT *
            									   FROM usuario
            									  WHERE login = :login 
            									    AND status_registro = :status_registro");
	    
	    $stUsuario->execute(array("login" => $login, "status_registro" => "A"));
	    $buscaUsuario = $stUsuario->fetch(PDO::FETCH_ASSOC);

		if(crypt($senha_antiga, $buscaUsuario['senha']) === $buscaUsuario['senha']) {

			$custo = "16";
			$hash = crypt($senha, '$2a$' . $custo . '$' . salt() . '$');

			$stUpdate = Conexao::chamar()->prepare("UPDATE usuario
                									   SET data_validade = DATE_ADD(CURRENT_DATE(), INTERVAL 6 MONTH),
                										   senha = :senha
                									 WHERE id = :id");
			
			$stUpdate->bindParam("senha", $hash, PDO::PARAM_STR);
			$stUpdate->bindParam("id", $buscaUsuario['id'], PDO::PARAM_INT);
			
			$update = $stUpdate->execute();

		    if($update) {

				echo "<script>window.location='index.php?msg=".urlencode("Senha atualizada com sucesso! Fa&ccedil;a o login com a nova senha")."&type=success'</script>";

			} else {

				$msg = "Erro ao atualizar a senha, tente novamente mais tarde.";

			}

		} else {

			$msg = "A senha atual informada n&atilde;o confere.";

		}

	}
}
?>

    <div class="vertical-center">

		<div class="container text-center">
			<div class="row vertical-align">
				<div class="col-xs-12 col-sm-6 col-lg-4 col-sm-offset-3 col-lg-offset-4">
				    <?php if($msg) { ?>
				    <p class="alert alert-danger"><?= $msg ?></p>
				    <?php } ?>
					<div class="panel panel-danger">
						<div class="panel-heading">
							<strong> Informe o login para continuar</strong>
						</div>
						<div class="panel-body">
							<form name="form_login" id="form_login" action="" method="post">
								<div class="row">
									<div class="col-sm-12 col-md-10 col-md-offset-1 ">
										<div class="form-group">
                            				<input type="password" name="senha_antiga" id="senha_antiga" class="form-control required" required placeholder="Informe a senha atual...">
                            			</div>
                            			
                            			<div class="form-group">
                            				<div class="input-group">
                        						<input type="password" name="senha" id="senha" class="form-control required" required minlength="8" placeholder="Informe a  nova senha...">
                        						<div class="input-group-btn" title="Ajuda com a senha">
                        					        <button type="button"
                        					        		class="btn btn-primary dropdown-toggle"
                        					        		data-toggle="dropdown"
                        					        		aria-haspopup="true"
                        					        		aria-expanded="false">
                        					        		<i class="fa fa-question-circle"></i> <span class="caret"></span>
                        					        </button>
                        					        <ul class="dropdown-menu dropdown-menu-right">
                        					        	<li><a href="#"><strong>A senha deve ter no m&iacute;nimo :</strong></a></li>
                        					        	<li role="separator" class="divider"></li>
                        					        	<li><a href="#"><i class="fa fa-check-circle"></i> 8 d&iacute;gitos</a></li>
                        					        	<li><a href="#"><i class="fa fa-check-circle"></i> Uma letra mai&uacute;scula</a></li>
                        					        	<li><a href="#"><i class="fa fa-check-circle"></i> Uma letra min&uacute;scula</a></li>
                        					        	<li><a href="#"><i class="fa fa-check-circle"></i> Um n&uacute;mero</a></li>
                        					        	<li><a href="#"><i class="fa fa-check-circle"></i> Um caracter: ! @ # % * _ + = &amp;</a></li>
                        					        </ul>
                        				      	</div>
                        					</div>
                            			</div>
                            			
                            			<div id="mostra_senha" class="hidden">
                            				<div class="progress" style="margin-bottom: 0;">
                            					<div class="progress-bar progress-bar-danger progress-bar-striped" id="progresso_senha" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                            				</div>
                            				<p class="text-center text-danger"><small id="texto_senha"></small></p>
                            			</div>
                            			
                            			<div class="form-group">
                            				<input type="password" name="conf_senha" id="conf_senha" class="form-control required" required placeholder="Confirme a senha digitada...">
                            			</div>
										<div class="form-group">
											<div class="g-recaptcha" data-sitekey="6LfAxQwUAAAAAFGJJaOEZtsp6gT_ikC-9-HyB6w8"></div>
										</div>
										<div class="form-group">
											<button type="submit" class="btn btn-primary btn-block" id="enviar" disabled>Enviar</button>
										</div>
									</div>
								</div>
							</form>
						</div>
	                </div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>