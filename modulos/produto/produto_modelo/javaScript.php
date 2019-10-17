<script>
function direcao(valor) {

	window.location='<?= $link ?>&p=<?= $pagina ?>&o=<?= $o ?>&d='+valor;
	
}

function ordem(valor) {

	window.location='<?= $link ?>&p=<?= $pagina ?>&d=<?= $d ?>&o='+valor;
	
}
$("#pesquisa_cancelar").click(function(){
     $("input:text").val("");
     $("#status_registro_0").click();
});

$(function() {
    if(  $("input:text").val() != ''){
    	$("#pesquisa_tabela").click();
    }
});

</script>