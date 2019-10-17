<script>
	function direcao(valor) {

		window.location='<?= $link ?>&p=<?= $pagina ?>&o=<?= $o ?>&d='+valor;

	}

	function ordem(valor) {

		window.location='<?= $link ?>&p=<?= $pagina ?>&d=<?= $d ?>&o='+valor;

	}
</script>