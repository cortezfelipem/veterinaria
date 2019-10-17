<?php
include "../sistema/conexao.php";

try {

	$stLogin = Conexao::chamar()->prepare("SELECT login
											 FROM usuario
											WHERE login = :login
											  AND status_registro = :status_registro");

	$stLogin->execute(array("login" => $login, "status_registro" => "A"));
	$qryLogin = $stLogin->fetchAll(PDO::FETCH_ASSOC);

	if(count($qryLogin) > 0 || strlen($login) < 5) { ?>
		<span class="text-danger"><i class="fa fa-ban"></i> Login Indispon&iacute;vel</span>
	<?php } else { ?>
		<span class="text-success"><i class="fa fa-check"></i> Login Dispon&iacute;vel</span>
	<?php } ?>

<?php
} catch (PDOException $e) {

	echo $e->getMessage();

}