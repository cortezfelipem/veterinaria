function setMask(){
	
	//$("#data").mask("99/99/9999",{placeholder:"mm/dd/yyyy",completed:function(){alert("completed!");}});
	
	$('.telefone').focusout(function() {
		
        var phone, element;
        element = $(this);
        element.unmask();
        phone = element.val().replace(/\D/g, '');
        if (phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
   }).trigger('focusout');
	$('.analytics').focusout(function() {
		var codigo, element;
		element = $(this);
		element.unmask();
		codigo = element.val().replace(/\D/g, '');
		if (codigo.length > 11) {
			element.mask("aa-99999999-?99");
		} else {
			element.mask("aa-99999999-9?9");
		}
	}).trigger('focusout');
   $(".cpf").mask("999.999.999-99");
   $(".cpf2").mask("999.999.999-99");
   $(".cnpj").mask("99.999.999/9999-99");
   $(".cnpj2").mask("99.999.999/9999-99");
   $(".cep").mask("99999-999");
   $(".ano").mask("9999");
   $(".data").mask("99/99/9999");
   $(".mes").mask("99");
   $(".hora").mask("99:99");
   $(".placa").mask("aaa-9999");
   $(".cnj").mask("9999999-99-9999-9-99-9999");
   
}

function unsetMask(){

	$(".telefone").unmask();
   	$(".cpf").unmask();
   	$(".cnpj").unmask();
   	$(".cep").unmask();
   	$(".ano").unmask();
   	$(".data").unmask();
   	$(".hora").unmask();
   	$(".placa").unmask();
   	$(".cnj").unmask();
}

$(setMask);

$(document).on('change', '.btn-file :file', function() {

	var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      
	var input = $(this).parents('.input-group').find(':text'),
	log = numFiles > 1 ? numFiles + ' arquivos selecionados' : label;
  
	if( input.length ) {
		input.val(log);
	} else {
		if( log ) alert(log);
	}
      
    //input.trigger('fileselect', [numFiles, label]);
});

$(document).ready(function () {
	
	$('#menu').metisMenu();

	$('#abre_menu').on("click", function(e) {
		
		e.preventDefault();

		
		
		$('.sidebar').toggle();
		//$('.corpo').toggleClass('corpo_inteiro');
		
	});

	$('.corpo').on("click", function(e) {
		
		$('.sidebar').hide('slow');
		//$('.corpo').toggleClass('corpo_inteiro');
		
	});
	
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
	
	$('.moeda').maskMoney({showSymbol:false,decimal:",",thousands:".",precision: 2});
	$('.peso').maskMoney({showSymbol:false,decimal:",",thousands:".",precision: 3});
	
	$('.lightbox').fancybox({'titlePosition' : 'inside'});
	
    $('.input-group.date').datepicker({
        clearBtn: true,
        language: "pt-BR",
        autoclose: true,
        todayHighlight: true
    });
	
	var config = {
            toolbar: [
          		[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ],
          		[ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
          		[ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
          		[ 'Link', 'Unlink' ],
          		[ 'TextColor', 'BGColor' ],
          		[ 'Maximize' ],
          		[ 'Font', 'FontSize' ]
          	]
        };
	
	$('.editor').ckeditor(config);
	
	$(".validar_formulario").validate();
	 
	$(".validar_formulario_com_senha").validate({
			
		 rules:{
				conf_senha:{
					equalTo: "#senha"
				}
			}
	});
	
	$('.galeriaSortable').sortable({
		
		opacity: 0.7,
		update: function() {
			
			$.ajax({
				
		        type: "POST",
		        url: "ajax/ordem_galeria.php",
		        data: {
		            sort: $(this).sortable('serialize'),
					tabela: $(this).attr("id")
		        },
		        success: function(html) {
		        	
		        	//if(html != "")
		        		//noty({text: html, type: 'error', timeout: false});
		        }
		    }).fail(function( jqXHR, textStatus ) {
				noty({text: "Erro: " + textStatus, type: 'error'});  
			});
		}
	}).disableSelection();
	
});

String.prototype.replaceAll = function(de, para){
    var str = this;
    var pos = str.indexOf(de);
    while (pos > -1){
        str = str.replace(de, para);
        pos = str.indexOf(de);
    }
    return (str);
};


function codificarHtml(texto){
    var htmlCodigo = { 
                  'á' : {'code' : '&aacute;'},
                  'Á' : {'code' : '&Aacute;'},
                  'ã' : {'code' : '&atilde;'},
                  'Ã' : {'code' : '&Atilde;'},
                  'à' : {'code' : '&agrave;'},
                  'À' : {'code' : '&Agrave;'},                       
                  'é' : {'code' : '&eacute;'},
                  'É' : {'code' : '&Eacute;'},
                  'í' : {'code' : '&iacute;'},
                  'Í' : {'code' : '&Iacute;'},
                  'ó' : {'code' : '&oacute;'},
                  'Ó' : {'code ': '&Oacute;'}, 
                  'õ' : {'code' : '&otilde;'},
                  'Õ' : {'code' : '&Otilde;'},
                  'ú' : {'code' : '&uacute;'},
                  'Ú' : {'code' : '&Uacute;'},
                  'ç' : {'code' : '&ccedil;'},
                  'Ç' : {'code' : '&Ccedil;'}                      };
    var acentos = ['á', 'Á', 'ã', 'Ã', 'à', 'À', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'õ', 'Õ', 'ú', 'Ú', 'ç', 'Ç'];
    
    for(var i=0; i<acentos.length; i++){
        if(htmlCodigo [acentos[i]] != undefined){
            texto = texto.replaceAll(acentos[i], htmlCodigo[acentos[i]].code );
        }
    }
    
    return texto;
}

function marcarDesmarcar(input) {
	
	if ($(input).is(":checked")){
		
		$(input).attr('title', 'Desmarcar todos');
		$(".marcar").each(function(){
					
					$(this).prop("checked", true);
				}
		);
	} else {
		
		$(input).attr('title', 'Marcar todos');
		$(".marcar").each(function(){
					
					$(this).prop("checked", false);
				}
		);
	}
}

function excluir(link, chave) {
	
	noty({
		
		text: chave ? 'Deseja realmente remover o registro? Digite a chave de seguran&ccedil;a:<br><input type="password" name="chave_excluir" id="chave_excluir" class="form-control" />' : 'Deseja realmente remover o registro?',
		type: 'alert',
		buttons: [{
			
			addClass: 'btn btn-primary', text: 'Sim', onClick: function($noty) {
				
				if(chave) {
					
					if($("#chave_excluir").val() == "") {
						
						$("#chave_excluir").addClass('error');
						
					} else {
						
						$.ajax({
							type: "POST",
							url: "ajax/chave_seguranca.php",
							data: { chave: $("#chave_excluir").val() },
							success: function(data) {
								
								$noty.close();
								
								if(data == "sucesso") {
									
									window.location = link;
								
								} else {
									
									noty({text: 'Chave de seguran&ccedil;a inv&aacute;lida!', type: 'error'});
									
								}
							}
						}).fail(function( jqXHR, textStatus ) {
							noty({text: "Erro ao enviar: " + textStatus, type: 'error', timeout: false});
						});
						
					}
					
				} else {
					
					window.location = link;
				}
				
			}
		},
	    {
	    	addClass: 'btn btn-danger', text: 'N&atilde;o', onClick: function($noty) {
	        
	    		$noty.close();
	    	}
	    }]
	});
	
	return false;
	
}

function excluirVarios(chave) {
	
	var total = $('.marcar:checked').length;
	if(total > 0) {
		
		noty({
			
			text: 'Deseja realmente remover os ' + total + ' registros marcados?' + (chave ? ' Digite a chave de seguran&ccedil;a:<br><input type="password" name="chave_excluir" id="chave_excluir" class="form-control" />' : ''),
			type: 'alert',
			buttons: [{
				
				addClass: 'btn btn-primary', text: 'Sim', onClick: function($noty) {
					
					if(chave) {
						
						if($("#chave_excluir").val() == "") {
							
							$("#chave_excluir").addClass('error');
							
						} else {
							
							$.ajax({
								type: "POST",
								url: "ajax/chave_seguranca.php",
								data: { chave: $("#chave_excluir").val() },
								success: function(data) {
									
									$noty.close();
									
									if(data == "sucesso") {
										
										document.form_lista.submit();
									
									} else {
										
										noty({text: 'Chave de seguran&ccedil;a inv&aacute;lida!', type: 'error'});
										
									}
								}
							}).fail(function( jqXHR, textStatus ) {
								noty({text: "Erro ao enviar: " + textStatus, type: 'error', timeout: false});
							});
							
						}
						
					} else {
						
						document.form_lista.submit();
					}
					
				}
			},
		    {
		    	addClass: 'btn btn-danger', text: 'N&atilde;o', onClick: function($noty) {
		        
		    		$noty.close();
		    		return false;
		    	}
		    }]
		});
		
	} else {
		
		noty({
			
			text: 'Selecione alguns registros primeiro!',
			type: 'error',
			buttons: [{
				
		    	addClass: 'btn btn-primary', text: 'OK', onClick: function($noty) {
		        
		    		$noty.close();
		    		
		    	}
		    }]
		});
		
		return false;
		
	}
	
	return false;
	
}

function restaurar(tabela, id, link) {
	
	noty({
		
		text: 'Deseja realmente restaurar o registro? Digite a chave de seguran&ccedil;a:<br><input type="password" name="senha_restaurar" id="senha_restaurar" class="form-control" />',
		type: 'alert',
		buttons: [{
			
			addClass: 'btn btn-primary', text: 'Sim', onClick: function($noty) {
				
				if($("#senha_restaurar").val() == "") {
					
					$("#senha_restaurar").addClass('error');
					
				} else {
					
					$.ajax({
						type: "POST",
						url: "ajax/restaurar.php",
						data: { id: id, tabela: tabela, chave: $("#senha_restaurar").val() },
						success: function(data) {
							
							$noty.close();
							
							if(data == "erro") {
								
								noty({text: 'N&atilde;o foi poss&iacute;vel restaurar o registro!', type: 'error'});
								
							} else if(data == "invalido") {
								
								noty({text: 'Chave de seguran&ccedil;a inv&aacute;lida!', type: 'error'});	
							
							} else {
								
								window.location = link;
								
							}
						}
					}).fail(function( jqXHR, textStatus ) {
						noty({text: "Erro ao enviar: " + textStatus, type: 'error', timeout: false});
					});
					
				}
			}
		},
	    {
	    	addClass: 'btn btn-danger', text: 'N&atilde;o', onClick: function($noty) {
	        
	    		$noty.close();
	    	}
	    }]
	});
	
	return false;
	
}

function excluirArquivo(id_, tabela_, coluna_, tipo_, link) {
	
	noty({
		
		text: 'Deseja realmente remover o arquivo?',
		type: 'alert',
		buttons: [{
			
			addClass: 'btn btn-primary', text: 'Sim', onClick: function($noty) {
				
				$noty.close();
				
				$.ajax({
					type: "POST",
					url: "ajax/excluir_arquivo.php",
					data: { id: id_, tabela: tabela_, coluna: coluna_, tipo: tipo_ },
					success: function(data) {
						
						console.log(data);
						
						if(data == "sucesso") {
							
							alerta(link, 'Arquivo removido com sucesso', 'success');
							
						} else {
							
							noty({text: 'N&atilde;o foi poss&iacute;vel excluir o arquivo!', type: 'error'});
							
						}
					}
				}).fail(function( jqXHR, textStatus ) {
					noty({text: "Erro ao enviar: " + textStatus, type: 'error', timeout: false});
				});
				
			}
		},
		{
			addClass: 'btn btn-danger', text: 'N&atilde;o', onClick: function($noty) {
				
				$noty.close();
			}
		}]
	});
	
	return false;
	
}

function alerta(link, mensagem, tipo) {
	
	noty({
		
		text: mensagem,
		type: tipo ? tipo : 'alert',
		buttons: [{
			
			addClass: 'btn btn-primary', text: 'OK', onClick: function($noty) {
				
				window.location = link;
			}
		}]
	});
	
}

function editarGaleriaFoto(id) {

	noty({
		text: '<input type="text" name="credito" id="credito" value="'+$("#credito_alterar_"+id).val()+'" placeholder="Cr&eacute;dito" class="form-control" /><br><br><input type="text" name="legenda" id="legenda" value="'+$("#legenda_alterar_"+id).val()+'" placeholder="Legenda" class="form-control" />',
		type: 'alert',
		buttons: [{
			addClass: 'btn btn-primary', text: 'Salvar', onClick: function($noty) {

				$("#credito_alterar_"+id).val(codificarHtml( $("#credito").val() ) );
				$("#legenda_alterar_"+id).val(codificarHtml( $("#legenda").val() ) );
				$("#span_foto_"+id).text(codificarHtml( $("#legenda").val() ) );

			
				$noty.close();
			}
	    },
	    {
	    	addClass: 'btn btn-danger', text: 'Cancelar', onClick: function($noty) {
	        
	    		$noty.close();
	    	}
	    }]
	});

}

function editarGaleriaVideo(id) {
	
	noty({
		text: '<input type="text" name="titulo" id="titulo_video" value="'+$("#titulo_video_alterar_"+id).val()+'" placeholder="T&iacute;tulo" class="form-control" /><br><br><input type="text" name="link" id="link_video" value="'+$("#link_video_alterar_"+id).val()+'" placeholder="Link" class="form-control url" />',
		type: 'alert',
		buttons: [{
			addClass: 'btn btn-primary', text: 'Salvar', onClick: function($noty) {
				
				$("#titulo_video_alterar_"+id).val($("#titulo_video").val());
				$("#link_video_alterar_"+id).val($("#link_video").val());
				$("#span_video_"+id).text( $("#titulo_video").val() );
				$noty.close();
			}
		},
		{
			addClass: 'btn btn-danger', text: 'Cancelar', onClick: function($noty) {
				
				$noty.close();
			}
		}]
	});
	
}

function editarGaleriaLink(id) {
	
	noty({
		text: '<input type="text" name="descricao" id="descricao" value="'+$("#descricao_link_alterar_"+id).val()+'" placeholder="Descri&ccedil;&atilde;o" class="form-control" /><br><br><input type="text" name="link" id="link" value="'+$("#link_link_alterar_"+id).val()+'" placeholder="Link" class="form-control url" />',
		type: 'alert',
		buttons: [{
			addClass: 'btn btn-primary', text: 'Salvar', onClick: function($noty) {
				
				$("#descricao_link_alterar_"+id).val($("#descricao").val());
				$("#link_link_alterar_"+id).val($("#link").val());
				$noty.close();
			}
		},
		{
			addClass: 'btn btn-danger', text: 'Cancelar', onClick: function($noty) {
				
				$noty.close();
			}
		}]
	});
	
}

function editarGaleriaAnexo(id) {
	
	noty({
		text: '<input type="text" name="descricao" id="descricao" value="'+$("#descricao_alterar_"+id).val()+'" placeholder="Descri&ccedil;&atilde;o" class="form-control" />',
		type: 'alert',
		buttons: [{
			addClass: 'btn btn-primary', text: 'Salvar', onClick: function($noty) {
				
				$("#descricao_alterar_"+id).val($("#descricao").val());
				$("#span_anexo_"+id).text( $("#descricao").val() );

				$noty.close();
			}
		},
		{
			addClass: 'btn btn-danger', text: 'Cancelar', onClick: function($noty) {
				
				$noty.close();
			}
		}]
	});
	
}

function popup(link, titulo) {
	
	var el = 
		'<div class="modal fade" id="modal" tabindex="-1" role="dialog">' +
			'<div class="modal-dialog modal-lg" role="document">' +
				'<div class="modal-content">' +
					'<div class="modal-header bg-primary">' +	
						'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + 
						'<h4 class="modal-title" id="modal_titulo"></h4>' +
					'</div>' +
					'<div class="modal-body" id="modal_corpo"></div>' +
				'</div>' +
			'</div>' +
		'</div>';
	
	if($('#modal').length < 1)
		$('body').append(el);
	
	$('#modal_titulo').html(titulo);
	$('#modal_corpo').load(link + '&time=' + $.now(), function(response, status, xhr){
		
		if (status == "error") {
    		
    		var msg = "Desculpe, houve um erro: ";
    	    jQuery(this).html(msg + xhr.status + " " + xhr.statusText);
    	}
		
	});
	$('#modal').modal('show');
	
}

function postPopup(link, form) {
	
	jQuery.ajax({
        type: "POST",
        url: link,
        data: jQuery(form).serializeArray(),
        success: function(data) {
        	jQuery("#modal_corpo").html(data);
        }
    });
	
	return false;
}

function logoff(){
	
	noty({
	  text: 'Deseja realmente sair?',
	  type: 'alert',
	  buttons: [
	    {addClass: 'btn btn-primary', text: 'Sim', onClick: function($noty) {

	    	var cookies = document.cookie.split(";");
	    	for(var i=0; i < cookies.length; i++) {
	    	    
	    		var equals = cookies[i].indexOf("=");
	    	    var name = equals > -1 ? cookies[i].substr(0, equals) : cookies[i];
	    	    eraseCookie(name);
	    	}
	    	
	        $noty.close();
	        
	        window.location="index.php";
	      }
	    },
	    {addClass: 'btn btn-danger', text: 'N&atilde;o', onClick: function($noty) {
	        
	    	$noty.close();
	      }
	    }
	  ]
	});
}

function createCookie(name,value,days) {
	
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function eraseCookie(name) {
	
	createCookie(name,"",-1);
}

function readCookie(name) {
	
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}



