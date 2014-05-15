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
}

/**
 * Fecha a janela HabtmForm
 *
 */
function hideHabtmForm()
{
	$("#formHabtm").fadeOut(0, function() { $("#tampaTudo").fadeOut(); }); 
}

/**
 * Retorna os elementos, conforme as tags, de uma div
 * e ainda ordena por umda das tags definida em order
 */
function getElemsDiv(id,tags,order)
{
	var arr   	= [];
	var ordem	= tags[order];

	for(i=0; i<tags.length;i++)
	{
		var tag = tags[i];
		var l 	= 0;
		$('#'+id+' '+tag).each(function()
		{
			if (arr[l]==undefined) arr[l] = {};
			if (arr[l][tag]==undefined) arr[l][tag] = {};
			vlr = ($(this).val()=='') ? $(this).text() : $(this).val();
			arr[l][tag] = vlr;
			l++;
		});
	};

	// ordenando o objeto
	arr.sort(function(a,b)
	{
		return a[ordem].localeCompare(b[ordem]);
	});

	return arr;
}

/**
 * Atualiza a div dataHabtm do formulário habtm_form, com os campos HABTM do formulário da lista
 * será criado uma linha para cada campo, que conterá o valor ID e o nome do relacionamento
 */
function setDataHabtm(pag)
{
	var id 		= $("#cmpHabtmCor").val();
	var objId	= id.split('_');
	var tags	= ['input','span'];
	var arr 	= getElemsDiv(id,tags,1);
	var htmlData= '';

	$("#cmpPesqHabtm").focus();

	// montando o html do dataHabtm //habtm_3_Usuario_Perfil
	for(i=0; i<arr.length;i++)
	{
		var inName 	= objId[0]+'['+objId[1]+']'+objId[2]+'['+objId[3]+']'+'['+i+']';
		var inId	= objId[0]+objId[1]+objId[2]+objId[3]+i;
		htmlData += '<div id="divLinhaFormHabtm'+i+'" class="linhaFormHabtm">';
		htmlData += '<div class="delLinhaFormHabtm" onclick="$(\'#divLinhaFormHabtm'+i+'\').remove();">(x)</div>&nbsp;';
		htmlData += '<span id="sp'+inId+'">'+arr[i].span+'</span> ';
		htmlData += '<input type="hidden" name="'+inName+'" id="in'+inId+'" value="'+arr[i].input+'" />';
		htmlData += "</div>";
	}

	$("#dataHabtm").html(htmlData);
	return false;
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

	$("#btSalvarT").addClass('btAlerta');
	$("#"+id).html(html);
	hideHabtmForm();
}