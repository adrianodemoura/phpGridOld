$(document).ready(function()
{
	$("#cxSel").change(function() 
	{ 
		if ($(this).val())
		{
			$("#marcador").val($(this).val()); 
			$("#formLista").attr("action",$(this).val());
			$("#formLista").submit(); 
		}
	});

	$("#lista #tabela select").change(function()
	{
		$(this).css('color','#fff');
		$(this).css('background-color','red');
		$("#btSalvarT").addClass('btAlerta');
	});

	$("#lista #tabela input:text").change(function()
	{
		$(this).css('color','#fff');
		$(this).css('background-color','red');
		$("#btSalvarT").addClass('btAlerta');
	});
	
	$("#ajaxP1").click(function()	// primeira página
	{
		$('#ajaxPC').html(1);
		setAjaxTab();
	});

	$("#ajaxPA").click(function()	// página anterior
	{
		var pag = $('#ajaxPC').html();
		pag = pag-1; if (pag<1) pag=1;
		$('#ajaxPC').html(pag);
		setAjaxTab();
	});

	$("#ajaxPP").click(function()	// página próxima
	{
		var pag = $('#ajaxPC').html();
		pag = parseInt(pag)+1; if (pag>100000) pag=1;
		$('#ajaxPC').html(pag);
		setAjaxTab();
	});

	$("#ajaxPesq").keyup(function(event) // ao digitar
	{
		$('#ajaxPC').html(1);
		setAjaxTab();
	});
});

function showLista()
{
	$("#ajaxResp").html("");
	$("#tampaTudo").fadeOut();
	$("#ajaxForm").fadeOut(); 
	$("#lista").fadeIn(); 
}

function showAjaxForm()
{
	$('#ajaxPC').html(1);
	$('#ajaxPesq').val('');
	setAjaxTab();
	$("#ajaxForm").fadeIn();
	showModal("ajaxForm");
	$("#ajaxPesq").focus();
}
/**
 * Configura o item de campo belongsTo, que encontra na lista, com a escolha do ajaxForm
 */
function setItemAjax(tr)
{
	$("#ajaxPesq").val('');
	var ajaxIdDest 	= $("#ajaxCmp").val();
	var ajaxSpDest 	= "ajax"+ajaxIdDest;
	var v = '';
	var l = 0;
	$("#"+tr+"ajaxTr").children('td').each(function()
	{
		v = $(this).html();
		if (l==0) $("#"+ajaxIdDest).val(v);
		try
		{
			if (l==1)
			{
				$("#"+ajaxSpDest).html(v);
				$("#"+ajaxSpDest).css("font-weight", "bold" );
				$("#"+ajaxSpDest).css("color", "#fff" );
				$("#"+ajaxSpDest).css("background-color", "red" );
			}
		} catch(err)
		{
			alert('ocorreu algum erro ao tentar configurar o item !!!');
		}
		l++;
	});
	$("#btSalvarT").addClass("btAlerta");
}

function setAjaxTab()
{
	var txt	= encodeURIComponent($("#ajaxPesq").val());
	var url	= $('#ajaxDest').val();
	var pag = $('#ajaxPC').html();
	var tem = 0; // tem valor
	url += txt+'/pag:'+pag

	$('#ajaxDest').load(url, function(resposta, status, xhr)
	{
		if (status=='success')
		{
			$("#ajaxTo").html("");
			$("#ajaxResp").html("");
			var jArrResposta 	= resposta.split('*');
			var table			= '<table border="1px" id="ajaxTab">'+"\n";
			$.each(jArrResposta, function(i, linha)
			{
				var jArrLinha = linha.split(';');
				if (jArrLinha[0].length>1)
				{
					table += "<tr class='ajaxTr' id='"+i+"ajaxTr'>\n";
					var tds = [];
					$.each(jArrLinha, function(o, vlr)
					{
						if (vlr)
						{
							table += "\t<td class='ajaxTd' ";
							if (o==0) table += "style='display: none;' ";
							table += "onclick='setItemAjax("+i+"); showLista();'>"+vlr+"</td>\n";
							tem = 1;
						}
					});
					table += "</tr>\n";
				}
			});
			table += "</table>\n";
			
			if (tem==0) $('#ajaxPC').html(parseInt(pag));
			$("#ajaxResp").html(table);
		}
	});
	return false;
}
