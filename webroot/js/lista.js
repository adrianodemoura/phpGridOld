$(document).ready(function()
{
	$("#ajaxPesquisa").keypress(
		function(event)
		{
			var divResposta = '#ajaxResposta';
			var url			= $('#ajaxDest').val();
			$('#ajaxDest').load(url, function(resposta, status, xhr)
			{
				if (status=='success')
				{
					var jArrResposta = resposta.split(';');
					$("#ajaxResposta").html(resposta);
					/*$.each(jArrResposta, function(i, linha)
					{
						var jArrLinha = linha.split(',');
						if (jArrLinha[0]) $("<td>"+jArrLinha[0]+"</td><td>"+jArrLinha[1]+"</td>").appendTo(divResposta);
					});*/
				}
			});
		}
	);
});
