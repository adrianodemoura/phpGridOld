$(document).ready(function()
{
	$("#ajaxPesq").keyup(
		function(event)
		{
			var txt			= encodeURIComponent($("#ajaxPesq").val());
			var url			= $('#ajaxDest').val()+txt;
			console.log(url);
			$('#ajaxDest').load(url, function(resposta, status, xhr)
			{
				if (status=='success')
				{
					$("#ajaxResposta").html("");
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
									table += "\t<td class='ajaxTd' onclick='setItemAjax("+i+"); showLista();'>"+vlr+"</td>\n";
								}
							});
							$.each(tds, function(u, prop)
							{
								console.log(prop);
							});
							table += "</tr>\n";
						}
					});
					table += "</table>\n";
					$("#ajaxResposta").html(table);
				}
			});
		}
	);
});

function showLista()
{
	$("#ajaxForm").fadeOut(); 
	$("#lista").fadeIn(); 
}

function showAjaxForm()
{
	$("#lista").fadeOut(); 
	$("#ajaxForm").fadeIn(); 
	$("#ajaxPesq").focus();
}

function setItemAjax(tr)
{
	$("#ajaxPesq").val('');
	//var ajaxTr		= $("#"+tr+"ajaxTr").children('td').text();
	var ajaxIdDest 	= $("#ajaxCmp").val();
	var ajaxSpDest 	= "ajax"+ajaxIdDest;
	var v = '';
	var l = 0;
	$("#"+tr+"ajaxTr").children('td').each(function()
	{
		v = $(this).html();
		if (l==0) $("#"+ajaxIdDest).val(v);
		$("#"+ajaxSpDest).html(v);
		l++;
	});
}
