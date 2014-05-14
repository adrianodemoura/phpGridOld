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
 * Atualiza o o formulário habtm (dataHabtm do elemento form_habtm) com os valores do campo habtm da lista
 *
 */
function setDataHabtm()
{
	var id 		= $("#cmpHabtmCor").val();
	var objId	= id.split('_');
	var tags	= ['input','span'];
	var arr 	= getElemsDiv(id,tags,1);
	var htmlData= '';

	// montando o html do dataHabtm //habtm_3_Usuario_Perfil
	for(i=0; i<arr.length;i++)
	{
		var inName 	= objId[0]+'['+objId[1]+']'+objId[2]+'['+objId[3]+']'+'['+i+']';
		var inId	= objId[0]+objId[1]+objId[2]+objId[3]+i;
		htmlData += '<span>'+arr[i].span+'</span>';
		htmlData += '<input type="hidden" name="'+inName+'" id="'+inId+'" value="'+arr[i].input+'" />';
		htmlData += "\n<br />";
	}

	$("#dataHabtm").html(htmlData);

}

/**
 * Atualiza os campos Habtm na lista, e fecha a janela Habtm
 *
 */
function setHabtm()
{

	hideHabtmForm();
}