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
	var inputs 	= $('#'+id+' input');
	$('#'+id+' input').each(function()
	{
		console.log($(this).val());
	});
	$('#'+id+' span').each(function()
	{
		console.log($(this).text());
	});
	//console.log(inputs);
}
/**
 * Atuza o campo Habtm na lista, e fecha a janela Habtm
 *
 */
function setHabtm()
{

	hideHabtmForm();
}