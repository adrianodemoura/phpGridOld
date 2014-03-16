$(document).ready(function()
{
	$("#ajaxPesq").keyup(
		function(event)
		{
			var txt			= encodeURIComponent($("#ajaxPesq").val());
			var url			= $('#ajaxDest').val()+txt;
			$('#ajaxDest').load(url, function(resposta, status, xhr)
			{
				if (status=='success')
				{
					$("#ajaxResp").html("");
					var jArrResposta 	= resposta.split('*');
					var table			= '<table border="1px" id="ajaxTab">'+"\n";
					$.each(jArrResposta, function(i, linha)
					{
						var jArrLinha = linha.split(';');
						if (jArrLinha[0])
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
								}
							});
							table += "</tr>\n";
						}
					});
					table += "</table>\n";
					$("#ajaxResp").html(table);
				}
			});
		}
	);
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
	$("#ajaxForm").fadeIn();
	showModal("ajaxForm");
	$("#ajaxPesq").focus();
}

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
		if (l==1) $("#"+ajaxSpDest).html(v);
		l++;
	});
	$("#btSalvarT").toggleClass("btAlerta");
}
