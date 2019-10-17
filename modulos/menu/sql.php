<?php

if (!empty($id)) {
    try {
        $habilitar_loja_virtual = "N";
        if (isset($_POST['habilitar_loja_virtual'])
            && !empty($_POST['habilitar_loja_virtual'])
        ) {
            $habilitar_loja_virtual = "S";
        }

        $stAtualizacao = Conexao::chamar()->prepare("UPDATE cliente_configuracao
                                                        SET habilitar_loja_virtual = :habilitar_loja_virtual
                                                      WHERE id = :id");

        $stAtualizacao->bindValue("habilitar_loja_virtual", $habilitar_loja_virtual, PDO::PARAM_STR);
        $stAtualizacao->bindValue("id", $id, PDO::PARAM_INT);
        $cadastro = $stAtualizacao->execute();
    } catch (PDOException $e) {
        echo "<script>console.log('Erro: " . addslashes($e->getMessage()) . "'</script>";
    }
    if ($cadastro) {
        logAcesso("A", $tela, $id);

        echo alerta("success", "<i class\"fa fa-check\"></i> Registro alterado com sucesso.");
        return;
    }
}
echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel alterar o registro.");

?>