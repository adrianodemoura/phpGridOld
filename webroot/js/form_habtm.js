/**
 * JS para o elemento ajax_form
 */

/**
 * Exibe o formulário habtm
 *
 */
function showHabtmForm(id)
{
	$("#cmpHabtmCor").val(id);
	setDataHabtm();
	showModal("formHabtm");
	$("#cmpPesqHabtm").focus();
	setListaHabtm();
}

/**
 * Fecha a janela HabtmForm
 *
 */
function hideHabtmForm()
{
	$("#formHabtm").fadeOut(0, function()
	{
		$("#listaHabtm").empty();
		$("#tampaTudo").fadeOut();
	}); 
}

/**
 * Atualiza a div dataHabtm do formulário habtm_form, com os campos HABTM do formulário da lista
 * será criado uma linha para cada campo, que conterá o valor ID e o nome do relacionamento
 */
function setDataHabtm()
{
	var id 		= $("#cmpHabtmCor").val();
	var objId	= id.split('_');
	var tags	= ['input','span'];
	var arr 	= getElemsDiv(id,tags,1);
	var htmlData= '';

	$("#cmpPesqHabtm").focus();

	// montando o html do dataHabtm 
	for(i=0; i<arr.length;i++)
	{
		var idHabtm	= arr[i].input.split('.');
		var inName 	= objId[0]+'['+objId[1]+']'+objId[2]+'['+objId[3]+']'+'['+i+']';
		var inId	= objId[0]+objId[1]+objId[2]+objId[3]+i;

		htmlData += '<div id="divLinhaFormHabtm'+idHabtm['1']+'" class="linhaFormHabtm">';
		htmlData += '<input type="hidden" name="'+inName+'" id="in'+inId+'" value="'+arr[i].input+'" />';
		htmlData += '<div class="delLinhaFormHabtm" onclick="$(\'#divLinhaFormHabtm'+idHabtm['1']+'\').remove();">(x)</div>';
		htmlData += '<span id="sp'+inId+'">'+arr[i].span+'</span> ';
		htmlData += "</div>";
	}

	$("#dataHabtm").html(htmlData);
	return false;
}


/**
 * Atualiza a linha no data habtm
 *
 */
function setLinhaDataHabtm(idHabtm,txtHabtm)
{
	var id 		= $("#cmpHabtmCor").val();
	var objId	= id.split('_');
	var tags	= ['input','span'];
	var vlrIdHabtm = idHabtm;
	var idHabtm = idHabtm.split('.');
	var inName 	= objId[0]+'['+objId[1]+']'+objId[2]+'['+objId[3]+']'+'['+idHabtm['0']+']';
	var inId	= objId[0]+objId[1]+objId[2]+objId[3]+idHabtm['1'];
	var linha 	= '';
	var html 	= '';

	linha += '<div id="divLinhaFormHabtm'+idHabtm['1']+'" class="linhaFormHabtm">';
	linha += '<input type="hidden" name="'+inName+'" id="in'+inId+'" value="'+vlrIdHabtm+'" />';
	linha += '<div class="delLinhaFormHabtm" onclick="$(\'#divLinhaFormHabtm'+idHabtm['1']+'\').remove();">(x)</div>';
	linha += '<span id="sp'+inId+'">'+txtHabtm+'</span> ';
	linha += "</div>";

	// removendo, caso já exista
	$("#divLinhaFormHabtm"+idHabtm['1']).remove();

	// incluindo nova linha no data habtm
	$("#dataHabtm").append(linha);
}

/**
 * Atualiza os campos Habtm na lista, e fecha a janela Habtm
 *
 	<input type="hidden" name="data[1][Usuario][Perfil][0]" id="1UsuarioPerfil0" class="in_perfil lista_input" 
 		value="1.1" />
	<span>ADMINISTRADOR</span>
 */
function setHabtmLista()
{
	var id 		= $("#cmpHabtmCor").val();
	var objId	= id.split('_');
	var data 	= getElemsDiv('dataHabtm',['span','input'],0);
	var html 	= '';

	for(i=0; i<data.length; i++)
	{
		var inName 	= 'data['+objId[1]+']['+objId[2]+']['+objId[3]+']'+'['+i+']';
		var inId	= objId[1]+objId[2]+objId[3]+i;
		if (i>0) html += ', ';
		html += '<input type="hidden" name="'+inName+'" id="'+inId+'" value="'+data[i].input+'" />';
		html += '<span>'+data[i].span+'</span>';
	}

	if (!data.length)
	{
		var inName 	= 'data['+objId[1]+']['+objId[2]+']['+objId[3]+']'+'[0]';
		var inId	= objId[1]+objId[2]+objId[3]+'0';
		if (i>0) html += ', ';
		html += '<input type="hidden" name="'+inName+'" id="'+inId+'" value="0.0" />';
	} else 
	{
		//for($i=0; $i<$t; $i++) $input .= '&nbsp;'; $input .= "&nbsp;&nbsp;&nbsp;&nbsp;";
		for(i=0; i<data.length; i++) html += '&nbsp;';
		html += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}

	$("#"+id).addClass('inAlerta');
	$("#btSalvarT").addClass('btAlerta');
	$("#"+id).html(html);
	hideHabtmForm();
}

/**
 * Atualiza a div listaHabtm com a paginação dos campos de relacionamento
 *
 */
function setListaHabtm(pag)
{
	var id 		= $("#cmpHabtmCor").val();
	var objId	= id.split('_');
	var model	= objId[2];
	var cmp 	= objId[3];
	var url 	= base;
	var html 	= '';
	var c 		= $("#xHPC").text();

	switch(pag)
	{
		case '1':
			c = 1;
			break;
		case 'A':
			c = parseInt(c)-1;
			if (!c) c = 1;
			break;
		case 'P':
			c = parseInt(c)+1;
			break;
		case 'U':
			c = '*';
	}

	// montando a url de destino
	if (campos[model][cmp]['optionsFk']['cadastro'] != undefined)
	{
		url += campos[model][cmp]['optionsFk']['cadastro'];
		url += '/get_options/pag:'+c;
		if (campos[model][cmp]['optionsFk']['key']!=undefined)
		{
			url += '/'+campos[model][cmp]['optionsFk']['key']+':'+$("#cmpPesqHabtm").val();
		}
		if (campos[model][cmp]['optionsFk']['fields']!=undefined)
		{
			url += '/fields:'+campos[model][cmp]['optionsFk']['fields'];
		}
		if (campos[model][cmp]['optionsFk']['ord']!=undefined)
		{
			url += '/ord:'+campos[model][cmp]['optionsFk']['ord'];
		}

		// recuperando a lista
		//console.log(url);
		$('#ajaxDest').load(url, function(resposta, status, xhr)
		{
			if (status=='success')
			{

				$("#listaHabtm").html(" ... aguarde ...");
				var tam = parseInt(resposta.length);
				if (c=='*') c = 1;
				if (tam<5)
				{
					c = parseInt(c)-1;
				} else
				{
					/*console.log(url);
					console.log(tam);*/
					//console.log(resposta);
				}
				$("#xHPC").text(c);

				//console.log(resposta);
				var jArrResposta = resposta.split('*');
				$.each(jArrResposta, function(i, linha)
				{
					var jArrLinha = linha.split(';');
					//console.log(jArrLinha);
					html += '<div class="linhaListaHabtm">';
					var idHabtmR = 0;
					$.each(jArrLinha, function(e, coluna)
					{
						if (e==0)
						{
							idHabtmR = coluna;
						} else if(e==1) 
						{
							idHabtm = objId['1']+'.'+parseInt(idHabtmR);
							html += '<span class="linhaListaCol1"';
							html += ' onclick="setLinhaDataHabtm(\''+idHabtm+'\',\''+coluna+'\');"';
							html += '>'+coluna+'</span>';
						}
					});
					
					html += '</div>';
				});

				if(html.length<40) html = 'a pesquisa retornou vazio ...'

				// atualizando a listaHabtm
				$("#listaHabtm").html(html);
			}
		});
	} else
	{
		html = 'Este campo não possui configuração optionsFK ...';
	}

	// atualizando a página
	$("#xHPC").text(c);

	// atualizando a listaHabtm
	$("#listaHabtm").html(html);
}