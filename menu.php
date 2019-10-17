
<?
function buscaSubmenu($id){

	$submenu = array();
	 /*$stSubMenu = Conexao::chamar()->prepare("SELECT *
											 FROM controle_menu
											 WHERE id_menu = :id_menu
											 AND status_registro = :status_registro
											 ORDER BY ordem, id");
$stSubMenu->execute(array("id_menu" => $id, "status_registro" => "A"));*/
   $stSubMenu = Conexao::chamar()->prepare("SELECT controle_menu.* 
											  FROM controle_menu_usuario 
										 LEFT JOIN controle_menu
											    ON controle_menu.id = controle_menu_usuario.id_menu 
											WHERE controle_menu_usuario.id_usuario = :id_usuario
											 AND controle_menu.id_menu = :id_menu
											 AND controle_menu.status_registro = :status_registro
											 ORDER BY controle_menu.ordem, controle_menu.id");
	$stSubMenu->execute(array("id_menu" => $id, "status_registro" => "A", "id_usuario" => $_COOKIE["id_usuario"]));

	$qrySubMenu = $stSubMenu->fetchAll(PDO::FETCH_ASSOC);
	foreach ( $qrySubMenu as $sub) {
		$sub['menu'] = buscaSubMenu($sub['id']);
		array_push($submenu, $sub);
	}

	return $submenu;
}

$menu_principal = array();

$stMenu = Conexao::chamar()->prepare("SELECT *
									  FROM controle_menu
									  WHERE id_menu IS NULL
									  AND status_registro = :status_registro
									  ORDER BY ordem");

$stMenu->execute(array("status_registro" => "A"));
$qryMenu = $stMenu->fetchAll(PDO::FETCH_ASSOC);

foreach ($qryMenu as $menu) {
	$menu['menu'] = buscaSubMenu($menu['id']);
	array_push($menu_principal, $menu);
}
//echo '<script>console.log('.json_encode($menu_principal).')</script>';

?>


 <nav id="sidebar">
        <div class="sidebar-header text-center">
                <a href="<?= $CAMINHO ?>/inicio.php" >
					<!-- <img style="padding-top: 30px; padding-bottom: 30px; margin:0 auto; width: 88%" src="<?= $caminhoImg ?>/logo.png" alt="Controle Municipal" /> -->
				</a>
        </div>
		
		<? foreach ($menu_principal as $indice => $item) { 
		   		if(count($item['menu']) > 0 ){
		?>
			
				<a data-toggle="collapse" href="#ul_lista_<?=$indice?>">
					<p class="indice-menu-personalizado">
						<i class="<?=$item['icone']?> pull-left" style="font-size: 20px;"></i>
						<? echo $item['descricao']?>
					</p>
				</a>
                <ul class="list-unstyled components collapse" id="ul_lista_<?=$indice?>">
                   
					<? foreach ($item['menu'] as $item2) { ?>
						<? 	$link = "inicio.php?&t=" . $item2['id'] . "&time=" . time(); ?>
						<? if(count($item2['menu']) > 0) { ?>
							<li>
							<a href="#" class="a-menu" data-target="#menu_<?=$item2['id']?>" data-toggle="collapse" aria-expanded="false"><i class="<?=$item2['icone']?>"></i> <?=$item2['descricao']?> </a>
								<ul class="collapse list-unstyled" id="menu_<?=$item2['id']?>">
									<?
										foreach ($item2['menu'] as $item3) {
										$link3 = "inicio.php?&t=" . $item3['id'] . "&time=" . time(); 
									?>
										<li>
											<a href="<?=$link3?>">
											 	&nbsp;&nbsp;<i class="<?=$item3['icone']?>"></i>
											 	<?=$item3['descricao']?>
											</a>
									 	</li>
									<? } ?>
								</ul>
							</li>
						<? } else { ?>

							<li>
								<a href="<?=$link?>">
								 	<i class="<?=$item2['icone']?>"></i>
								 	<?=$item2['descricao']?>
								</a>
						 	</li>


						<? } ?>

					<? } ?>
				
				<? } else {	?>
				 	<ul class="list-unstyled components collapse" id="ul_lista_<?=$indice?>">
				 		<li><a href="<?="inicio.php?&t=" . $item['id'] . "&time=" . time(); ?>"><?=$item['descricao']?></a></li>
				 	</ul>

			 	<? } ?>
			 	</ul>

				
		<? } ?>
				
		
		<a data-toggle="collapse" data-target="#ul_lista_configuracao" >
			<p  class="indice-menu-personalizado">
				<i class="fa fa-sign-out pull-left" style="font-size: 20px;"></i>
				Sistema
			</p>
		</a>
		<ul class="list-unstyled components collapse" id="ul_lista_configuracao">
			 	 	 
			 	 	<li><a href="#">Email: <?= $buscaAdministrador['email'] ?></a></li>
			 	 	<li><a href="#">Usu&aacute;rio: <?= $buscaAdministrador['usuario'] ?></a></li>
					<li><a href="#" >Cliente: <?= $buscaAdministrador['nome_fantasia'] ?></a></li>
					<li><a href="#" >Cnpj: <?= $buscaAdministrador['cnpj'] ?></a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#" onclick="logoff()">Sair</a></li>
                    
                    

        </ul>
	
</nav>