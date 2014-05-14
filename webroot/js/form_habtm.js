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
 * Atualiza o o formulário habtm com os valores do campo habtm da lista
 *
 */
function setDataHabtm()
{
	var id 		= $("#cmpHabtmCor").val();
	var data 	= $("#"+id).html();
	var objInputs= [];
	var l 		= 0;
	$('#'+id+' input').each(function()
	{
		if (objInputs[l]==undefined) objInputs[l] = {};
		if (objInputs[l]['ids']==undefined) objInputs[l]['ids'] = {};
		objInputs[l]['ids'] = $(this).val();
		l++;
	});
	l = 0;
	$('#'+id+' span').each(function()
	{
		if (objInputs[l]['span']==undefined) objInputs[l]['span'] = {};
		objInputs[l]['span'] = $(this).text();
		l++;
	});

	for(i=0; i<objInputs.length;i++)
	{
		console.log(objInputs[i]);
	}
}

/**
 * Atuza o campo Habtm na lista, e fecha a janela Habtm
 *
 */
function setHabtm()
{

	hideHabtmForm();
}