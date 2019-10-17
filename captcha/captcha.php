<?php
header('Access-Control-Allow-Origin: *');

session_start();
$valor_set = $_REQUEST["q"];
$_SESSION['validacao'] = true;


if($valor_set == 9){

	$lista = array(0,1,2,3,4,5);
	$numero_escolhido  = array_rand($lista, 1);
	$randomico 		   = $numero_escolhido;
	unset($lista[$randomico]);
	$_SESSION['lista']     = $lista;
	$_SESSION['chave']     = $randomico;
	$_SESSION['contador']  = 3;

	$html = "<div><span style='text-align:center;margin-top:10px;font-size:16px;font-weight:600'>Selecione a letra H</span><br><div class='col-lg-12 col-sm-12 col-xs-12' style='padding-top:20px;padding-right:0;padding-left:0;padding-bottom:20px;text-align:center'>";
	for($i = 0; $i < 6; $i++){
		
		if($i == $randomico){
			$html .= "<a  onclick='getCaptcha(".$i.")'><img src='http://www.augustoiimoveis.com.br/www/site/images/faicon.png' height='38' width='38' style='cursor: pointer;'></a>";
		}else{
			$html .= "<a  onclick='getCaptcha(".$i.")'><img src='http://www.augustoiimoveis.com.br/www/site/images/frontEnd2[278].jpg' height='38' width='38' style='cursor: pointer;'></a>";
		}
	}
	$html .= "</div></div>";
	echo $html;
}


else if($valor_set == $_SESSION['chave']){
	$_SESSION['validacao'] = false;
	echo "<img style='float:right;margin-bottom:20px' src='http://www.augustoiimoveis.com.br/www/site/images/ok.png' height='30' width='30' />
		<script> $('.valida_enviar').removeAttr('disabled');</script>
	";
}

else if($_SESSION['contador'] == 1){
	echo "<span style='float:right;font-size:16px;font-weight:600;margin-top:10px'>Clique no bot&atilde;o abaixo para iniciar nova tentativa</span><br><div style='widht:100%;padding-top:20px'><a  onclick='getCaptcha(9)'><img src='http://www.augustoiimoveis.com.br/www/site/images/reload.png' height='50' width='50' style='border: 1px solid #a9a9a9; padding: 5px;cursor: pointer;margin-top:5px''></a></div>";
}
else{
	
	$lista = $_SESSION['lista'];
	unset($lista[$valor_set]);	
	$numero_escolhido  = array_rand($lista, 1);
	$randomico 		   = $numero_escolhido;
	unset($lista[$randomico]);
	$_SESSION['lista'] = $lista;
	$_SESSION['contador']--;
	$_SESSION['chave'] = $randomico;

	$html = "<div><span style='text-align:center;margin-top:10px;font-size:16px;font-weight:600'>Selecione a letra H</span><br><div class='col-lg-12 col-sm-12 col-xs-12' style='padding-top:20px;padding-right:0;padding-left:0;padding-bottom:20px;text-align:center'>";
	for($i = 0; $i < 6; $i++){

		
		if($i == $randomico){
			$html .= "<a  onclick='getCaptcha(".$i.")'><img src='http://www.augustoiimoveis.com.br/www/site/images/faicon.png' height='38' width='38' style='cursor: pointer;'></a>";
		}else{
			$html .= "<a  onclick='getCaptcha(".$i.")'><img src='http://www.augustoiimoveis.com.br/www/site/images/frontEnd2[278].jpg' height='38' width='38' style='cursor: pointer;'></a>";
		}
	}
	
	$html .= "</div></div>";
	
	echo $html;
}




?>