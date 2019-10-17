<?php
include "../sistema/conexao.php";

try {

    $stConf = Conexao::chamar()->query("SELECT *
									      FROM chave_seguranca
									     WHERE id_cliente = '$idCliente'");

    $conf = $stConf->fetch(PDO::FETCH_ASSOC);
    
    if (crypt($chave, $conf['chave']) === $conf['chave']) {
        
        echo "sucesso";
        
    }  else {

    	echo "invalido";
    }

} catch (PDOException $e) {

	echo $e->getMessage();

}