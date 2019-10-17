<?php

if ($_POST) {
    include_once "sql.php";
}


try {
    $queryConfiguracao = Conexao::chamar()->prepare("SELECT cliente_configuracao.*
                                                       FROM cliente_configuracao
                                                      LIMIT 1");
    $queryConfiguracao->execute();
    $buscaConfiguracao = $queryConfiguracao->fetch(PDO::FETCH_ASSOC);
    $id = $buscaConfiguracao['id'];
} catch (PDOException $e) {

    echo alerta("danger", "<i class=\"fa fa-ban\"></i> N&atilde;o foi poss&iacute;vel carregar o registro.");

    echo "<script>console.log('Erro: " . addslashes($e->getMessage()) . "')</script>";

}
?>

<form class="form-lista" id="form_lista" action="<?= $caminhoTela ?>" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>" />
    <div class="form-group">
        <label for="">
        </label>
        <div class="col-sm-10">
            <label><input type="checkbox" name="habilitar_loja_virtual"
                          value="S" <?= ($buscaConfiguracao['habilitar_loja_virtual'] == "S" ? "checked" : ""); ?> />&nbsp;Habilitar
                Loja Virtual</label>
        </div>
    </div>

    <p class="clearfix"></p>

    <nav class="pull-right hidden-xs hidden-sm">
        <button type="submit" class="btn btn-success pull right"><i class="fa fa-floppy-o"></i> Salvar</button>
        <a href="<?= $caminhoTela ?>" class="btn btn-warning pull right"><i class="fa fa-ban"></i> Cancelar</a>
    </nav>
    <nav class="visible-xs visible-sm">
        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> Salvar</button>
        <a href="<?= $caminhoTela ?>" class="btn btn-warning btn-block"><i class="fa fa-ban"></i> Cancelar</a>
    </nav>

    <p class="clearfix"></p>

</form>