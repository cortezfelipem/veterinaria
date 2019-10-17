function maisAnexo() {
	
	var el = 
		'<div>' +
		
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Descri&ccedil;&atilde;o</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="descricao_anexo[]" class="form-control" placeholder="Informe a descri&ccedil;&atilde;o do anexo...">' + 
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +	
				'<label class="col-sm-2 control-label">Inserir Anexo</label>' +
				'<div class="col-sm-10">' +	
					'<div class="input-group">' +
						'<span class="input-group-btn">' +
							'<span class="btn btn-primary btn-file">' +
								'<i class="fa fa-folder-open"></i> Selecionar&hellip;' +
								'<input type="file" name="anexo[]">' +
							'</span>' +
						'</span>' +
						'<input type="text" class="form-control" readonly placeholder="Selecione um arquivo...">' +
						'<span class="input-group-btn">' +
							'<button type="button" class="btn btn-danger" onclick="jQuery(this).parent().parent().parent().parent().parent().fadeOut(\'slow\', function(){jQuery(this).remove();})"><i class="fa fa-trash"></i></button>' +
						'</span>' +
					'</div>' +
				'</div>' +
			'</div>' +
			
		'</div>';
	         
	$(el).insertAfter('#mais-anexo');
}

function maisFoto() {
	
	var el = 
		'<div>' +
		/*
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Cr&eacute;dito</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="credito[]" class="form-control" placeholder="Informe os cr&eacute;ditos da imagem...">' + 
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Legenda</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="legenda[]" class="form-control" placeholder="Informe a legenda da imagem...">' + 
				'</div>' +
			'</div>' +
		*/	
			'<div class="form-group">' +	
				'<label class="col-sm-2 control-label">Inserir Imagem</label>' +
				'<div class="col-sm-10">' +	
					'<div class="input-group">' +
						'<span class="input-group-btn">' +
							'<span class="btn btn-primary btn-file">' +
								'<i class="fa fa-folder-open"></i> Selecionar&hellip;' +
								'<input type="file" name="imagem[]">' +
							'</span>' +
						'</span>' +
						'<input type="text" class="form-control" readonly placeholder="Selecione uma imagem...">' +
						'<span class="input-group-btn">' +
							'<button type="button" class="btn btn-danger" onclick="jQuery(this).parent().parent().parent().parent().parent().fadeOut(\'slow\', function(){jQuery(this).remove();})"><i class="fa fa-trash"></i></button>' +
						'</span>' +
					'</div>' +
				'</div>' +
			'</div>' +
			
		'</div>';
	         
	$(el).insertAfter('#mais-foto');
}

function maisVideo() {
	
	var el = 
		'<div>' +
		
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">T&iacute;tulo</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="titulo_video[]" class="form-control" placeholder="Informe o t&iacute;tulo do v&iacute;deo...">' + 
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +	
				'<label class="col-sm-2 control-label">Link</label>' +
				'<div class="col-sm-10">' +	
					'<div class="input-group">' +
						'<input type="text" name="link_video[]" class="form-control url" placeholder="Informe o link do v&iacute;deo...">' + 
						'<span class="input-group-btn">' +
							'<button type="button" class="btn btn-danger" onclick="jQuery(this).parent().parent().parent().parent().parent().fadeOut(\'slow\', function(){jQuery(this).remove();})"><i class="fa fa-trash"></i></button>' +
						'</span>' +
					'</div>' +
				'</div>' +
			'</div>' +
			
		'</div>';
	
	$(el).insertAfter('#mais-video');
}

function maisLink() {
	
	var el = 
		'<div>' +
		
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Descri&ccedil;&atilde;o</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="descricao_link[]" class="form-control" placeholder="Informe a descri&ccedil;&atilde;o do link...">' + 
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +	
				'<label class="col-sm-2 control-label">Link</label>' +
				'<div class="col-sm-10">' +	
					'<div class="input-group">' +
						'<input type="text" name="link_link[]" class="form-control url" placeholder="Informe o link...">' + 
						'<span class="input-group-btn">' +
							'<button type="button" class="btn btn-danger" onclick="jQuery(this).parent().parent().parent().parent().parent().fadeOut(\'slow\', function(){jQuery(this).remove();})"><i class="fa fa-trash"></i></button>' +
						'</span>' +
					'</div>' +
				'</div>' +
			'</div>' +
			
		'</div>';
	
	$(el).insertAfter('#mais-link');
}

function maisEndereco() {
	
	var el = 
		'<div>' +
		
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Endere&ccedil;o</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="endereco[]" class="form-control" placeholder="Informe o endere&ccedil;o...">' + 
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Bairro</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="bairro[]" class="form-control" placeholder="Informe o bairro...">' + 
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Estado</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="estado[]" class="form-control" placeholder="Informe o estado...">' + 
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Municipio</label>' +
				'<div class="col-sm-10">' +	
					'<input type="text" name="municipio[]" class="form-control" placeholder="Informe o municipio...">' + 
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +	
				'<label class="col-sm-2 control-label">CEP</label>' +
				'<div class="col-sm-10 col-lg-2">' +	
					'<div class="input-group">' +
						'<input type="text" name="cep[]" class="form-control cep" placeholder="Informe o cep...">' + 
						'<span class="input-group-btn">' +
							'<button type="button" class="btn btn-danger" onclick="jQuery(this).parent().parent().parent().parent().parent().fadeOut(\'slow\', function(){jQuery(this).remove();})"><i class="fa fa-trash"></i></button>' +
						'</span>' +
					'</div>' +
				'</div>' +
			'</div>' +
			
			'<p class="clearfix"></p>' +
			
		'</div>';
	
	$(el).insertAfter('#mais-endereco');
	$(setMask);
	
}

function maisTelefone() {
	
	var el = 
		'<div>' +
		
			'<div class="form-group">' +
				'<label class="col-sm-2 control-label">Tipo</label>' +
				'<div class="col-sm-10 col-lg-2">' +	
				'<select name="tipo[]" class="form-control">'+
	        		'<option value="">&raquo;&nbsp;Selecione</option>'+
	        		'<option value="Comercial">Comercial</option>'+
	        		'<option value="Residencial">Residencial</option>'+
	        		'<option value="Celular">Celular</option>'+
	        		'<option value="Recado">Recado</option>'+
	        		'<option value="Fax">Fax</option>'+
	        	'</select>'+
				'</div>' +
			'</div>' +
			
			'<div class="form-group">' +	
				'<label class="col-sm-2 control-label">Telefone</label>' +
				'<div class="col-sm-10 col-lg-2">' +	
					'<div class="input-group">' +
						'<input type="text" name="telefone[]" class="form-control telefone" placeholder="Informe o n&uacute;mero...">' + 
						'<span class="input-group-btn">' +
							'<button type="button" class="btn btn-danger" onclick="jQuery(this).parent().parent().parent().parent().parent().fadeOut(\'slow\', function(){jQuery(this).remove();})"><i class="fa fa-trash"></i></button>' +
						'</span>' +
					'</div>' +
				'</div>' +
			'</div>' +
			
			'<p class="clearfix"></p>' +
			
		'</div>';
	
	$(el).insertAfter('#mais-telefone');
	$(setMask);
	
}

function maisCategoria() {
	
	var el = 
		'<div>' +
		
			'<div class="form-group">' +	
				'<label class="col-sm-2 control-label">Categoria</label>' +
				'<div class="col-sm-10">' +	
					'<div class="input-group">' +
						'<input type="text" name="categoria[]" class="form-control" placeholder="Informe a descri&ccedil;&atilde;o...">' + 
						'<span class="input-group-btn">' +
							'<button type="button" class="btn btn-danger" onclick="jQuery(this).parent().parent().parent().parent().parent().fadeOut(\'slow\', function(){jQuery(this).remove();})"><i class="fa fa-trash"></i></button>' +
						'</span>' +
					'</div>' +
				'</div>' +
			'</div>' +
			
		'</div>';
	
	$(el).insertAfter('#mais-categoria');
}

function maisSubcategoria() {
	
	var el = 
		'<div>' +
		
			'<div class="form-group">' +	
				'<label class="col-sm-2 control-label">Subcategoria</label>' +
				'<div class="col-sm-10">' +	
					'<div class="input-group">' +
						'<input type="text" name="subcategoria[]" class="form-control" placeholder="Informe a descri&ccedil;&atilde;o...">' + 
						'<span class="input-group-btn">' +
							'<button type="button" class="btn btn-danger" onclick="jQuery(this).parent().parent().parent().parent().parent().fadeOut(\'slow\', function(){jQuery(this).remove();})"><i class="fa fa-trash"></i></button>' +
						'</span>' +
					'</div>' +
				'</div>' +
			'</div>' +
			
		'</div>';
	
	$(el).insertAfter('#mais-subcategoria');
}
