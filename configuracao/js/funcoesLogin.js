$(document).ready(function(){

	$('#form_login').validate();
	
	$('#form_usuario').validate({
        rules:{
            senha: {
                required: true,
                minlength: 8
            },
            senha_antiga: {
                required: true
            },
            conf_senha:{
                required: true,
                equalTo: "#senha"
            }
        },
        messages:{
            senha: {
                required: "",
                minlength: "O campo senha deve conter no m&iacute;nimo 8 caracteres."
            },
            senha_antiga: {
                required: ""
            },
            conf_senha:{
                required: "",
                equalTo: "As senhas n&atilde;o conferem."
            }
        }

    });
	
	$("#senha").on("keyup", function(){

		$("#mostra_senha").removeClass("hidden");
		$("#enviar").attr("disabled", "disabled");

		var forca = 0;
		var senha = $("#senha").val();
		var mostra = $("#progresso_senha");
		var texto = $("#texto_senha");

		if((senha.length >= 4) && (senha.length <= 7)){
			forca += 10;
		} else if(senha.length>7){
			forca += 25;
		}
		if(senha.match(/[a-z]+/)){
			forca += 10;
		}
		if(senha.match(/[A-Z]+/)){
			forca += 20;
		}
		if(senha.match(/\d+/)){
			forca += 20;
		}
		if(senha.match(/\W+/)){
			forca += 25;
		}

		mostra.html(forca + "%");
		mostra.css({ width: forca + "%" });

		if(forca < 30) {

			mostra.removeClass('progress-bar-warning progress-bar-success');
			mostra.addClass('progress-bar-danger');
			texto.removeClass('text-warning text-primary text-success');
			texto.addClass('text-danger');
			texto.html('Muito Fraca, misture letras e n&uacute;meros');

		} else if((forca >= 30) && (forca < 60)){

			mostra.removeClass('progress-bar-danger progress-bar-success');
			mostra.addClass('progress-bar-warning');
			texto.removeClass('text-danger text-primary text-success');
			texto.addClass('text-warning');
			texto.html('Ainda Fraca, utilize alguns s&iacute;mbolos, como ! @ # % * _ + =');

		} else if((forca >= 60) && (forca < 85)){

			mostra.removeClass('progress-bar-danger progress-bar-success progress-bar-warning');
			texto.removeClass('text-danger text-warning text-success');
			texto.addClass('text-primary');
			texto.html('Fraca, misture letras mai&uacute;sculas e min&uacute;sculas');

		} else if(senha.length < 8) {

			mostra.removeClass('progress-bar-danger progress-bar-success progress-bar-warning');
			texto.removeClass('text-danger text-warning text-success');
			texto.addClass('text-primary');
			texto.html('A senha deve conter pelo menos 8 caracteres');

		} else {

			mostra.removeClass('progress-bar-danger progress-bar-warning');
			mostra.addClass('progress-bar-success');
			texto.removeClass('text-danger text-warning text-primary');
			texto.addClass('text-success');
			texto.html('Senha Segura - Envie agora');
			$("#enviar").removeAttr("disabled");

		}
	});
	
});