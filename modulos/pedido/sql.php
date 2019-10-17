<?php



function enviar_email($id_cliente){

	$stPedido = Conexao::chamar()->prepare("
											SELECT pedido.id, pedido.status, pedido.data, pedido.codigo_rastreio, pedido_cadastro.nome, pedido_cadastro.email
											FROM pedido
											LEFT JOIN pedido_cadastro
											ON pedido_cadastro.id = pedido.id_cadastro
											WHERE pedido.id = :id
											LIMIT 1
										   ");
	$stPedido->bindValue(':id', $id_cliente , PDO::PARAM_INT);
	$stPedido->execute();
	$pedido = $stPedido->fetch(PDO::FETCH_ASSOC);

	$nome = "Atualiza&ccedil;&atilde;o do pedido";
	$remetente = $pedido['email'];

	if(empty($pedido['email']) && filter_var($pedido['email'], FILTER_VALIDATE_EMAIL) === false) $to = $remetente;
	else $to = $pedido['email'];

	/*$headers = "From: $nome <$dados_empresa[email]> \r\n";
	$headers.= "Return-Path: $nome <$dados_empresa[email]> \r\n";
	$headers .= "Sender: Tac Informatica <$dados_empresa[email]> \n";
	$headers .= "Organization: Tac Informatica \n";
	$headers.= "Content-Type: text/html; charset=ISO-8859-1 ";
	$headers.= "MIME-Version: 1.0 ";

*/
	$subject = $nome;

  	$html  = "<p>Olá $pedido[nome],</p>";
	$html .= "<p>Pedido:<b>$pedido[id]</b> </p>";
	$html .= "<p>Realizado em <b>".formata_data_hora($pedido[data])."</b></p>";
	$html .= "<p>Status: <b>".statusPedido($pedido[status])."</b></p>";
	$html .= "<p>Código de rastreamento: <b>".($pedido[codigo_rastreio] ? $pedido[codigo_rastreio] : 'Sem código')."</b></p>";
	
	$headers = "From: Tac Informatica - Pedidos <nao_responda@tacinformatica.com.br>\r\n";
    $headers .= "Return-Path: $pedido[nome] <nao_responda@tacinformatica.com.br> \r\n";
    $headers .= "MIME-Version: 1.1 \r\n";
	$headers  .= "Content-Type: text/html; charset=utf-8 \n";

	$vai = mail($to, html_entity_decode("Atualiza&ccedil;&atilde;o do Pedido"),  html_entity_decode($html), $headers);

	return $vai;


}


try {

	if(empty($id)) {
	    
		$stCadastro = Conexao::chamar()->prepare("INSERT INTO pedido 
		                                                  SET data = :data,
		                                                  	  tipo_frete = :tipo_frete,
		                                                  	  valor_frete = :valor_frete,
		                                                  	  codigo_rastreio = :codigo_rastreio,
		                                                  	  status = :status");

		$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);
		$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);
		$stCadastro->bindValue("descricao", $descricao, PDO::PARAM_STR);
		$cadastro = $stCadastro->execute();

		if($cadastro) {

			$id = Conexao::chamar()->lastInsertId();
			logAcesso("I", $tela, $id);

			echo "<script>alerta('$caminhoTela', 'Registro cadastrado com sucesso', 'success');</script>";

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel cadastrar o registro.");

		}

	} else {
	    
		$stCadastro = Conexao::chamar()->prepare("UPDATE pedido 
		                                            SET  codigo_rastreio = :codigo_rastreio,
		                                                 status = :status
		                                           WHERE id = :id");


	    $stCadastro->bindValue("codigo_rastreio", $codigo_rastreio, PDO::PARAM_STR);
		$stCadastro->bindValue("status", $status, PDO::PARAM_STR);
		$stCadastro->bindValue("id", $id, PDO::PARAM_INT);
		$cadastro = $stCadastro->execute();

		if($cadastro) {
			if(enviar_email($id)){
				logAcesso("A", $tela, $id);
				echo alerta("success", "<i class=\"fa fa-check\"></i> Registro alterado com sucesso.");
			} else {
				echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel enviar o email ao cliente.");
			}

		} else {

			echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

		}

	}

} catch (PDOException $e) {

    echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");
	echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

}




