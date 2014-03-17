$(document).ready(function()
{
	$("#lista #tabela select").change(function()
	{
		$(this).css('color','#fff');
		$(this).css('background-color','red');
		$("#btSalvarT").addClass('btAlerta');
	});

	$("#lista #tabela input").change(function()
	{
		$(this).css('color','#fff');
		$(this).css('background-color','red');
		$("#btSalvarT").addClass('btAlerta');
	});
	
	// primeira página
	$("#ajaxP1").click(function()
	{
		$('#ajaxPagi').html(1);
		setAjaxTab();
	});
	// página anterior
	$("#ajaxPA").click(function()
	{
		var pag = $('#ajaxPagi').html();
		pag = pag-1; if (pag<1) pag=1;
		$('#ajaxPagi').html(pag);
		setAjaxTab();
	});
	// página próxima
	$("#ajaxPP").click(function()
	{
		var pag = $('#ajaxPagi').html();
		pag = parseInt(pag)+1; if (pag>100000) pag=1;
		$('#ajaxPagi').html(pag);
		setAjaxTab();
	});

	// ao digitar
	$("#ajaxPesq").keyup(function(event)
	{
		$('#ajaxPagi').html(1);
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
	$('#ajaxPagi').html(1);
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
	var pag = $('#ajaxPagi').html();
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
			
			if (tem==0) $('#ajaxPagi').html(parseInt(pag));
			$("#ajaxResp").html(table);
		}
	});
	return false;
}
