$(document).ready(function()
{
	//
	$(document).on("click",function()
	{
		//$(".cmpPesquisa").fadeOut();
		//console.log(this.id);
	});
	
	// execua a pesquisa Ajax, do campo em questão
	/*$(".cmpPesquisa").keydown(function(e)
	{
		var cmpPes 		= this.id;
		var cmpPesRes 	= cmpPes+'Res';
		var e 			= e || window.event;

		if (e.keyCode==27)
		{
			$('#'+cmpPes).fadeOut();
			$('#'+cmpPesRes).fadeOut();
		} else
		{
			$('#'+cmpPesRes).html($('#'+cmpPes).val());
		}
	});*/
	
	$("#cxSel").change(function() 
	{ 
		if ($(this).val())
		{
			$("#marcador").val($(this).val()); 
			$("#formLista").attr("action",$(this).val());
			$("#formLista").submit(); 
		}
	});

	$("#lista .tabela select").change(function()
	{
		$(this).addClass('inAlerta');
		$("#btSalvarT").addClass('btAlerta');
	});

	$("#lista .tabela .lista_input").change(function()
	{
		$(this).addClass('inAlerta');
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

	// validação do form
	$('#formLista').submit(function() 
	{
		var msgErro	= '';
		var erros	= 0;
		$.each(campos, function(index, object) 
		{
		    $.each(object, function(cmp, arrProp)
		    {
		    	var pri = arrProp['primary'];
		    	var obr = arrProp['obrigatorio'];
		    	if (pri==undefined && obr!=undefined)
		    	{
		    		for(var i=1; i<21; i++)
		    		{
		    			var id = "#"+i+index+cmp.capitalize();
		    			vlr = $(id).val();
		    			if (vlr!=undefined)
		    			{
							if (vlr.length==0)
							{
								msgErro = 'O Campo '+cmp+', é de preenchimento obrigatório !!!';
								erros++;
							}
						}
		    		}
		    	}
		    });
		});
		if (erros>0)
		{
			alert(msgErro);
			return false;
		} else return true;
	});
});

function showLista()
{
	$("#ajaxResp").html("");
	$("#tampaTudo").fadeOut();
	$("#ajaxForm").fadeOut(); 
	$(".lista").fadeIn(); 
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
		if (l==0) $("#"+ajaxIdDest).val($(this).html()); // atualiza o input hidden com o valor do id do campo
		else
		{
			if (v) v += '/';
			v += $(this).html();
		}
		l++;
	});

	try
	{
		$("#"+ajaxSpDest).html(v);
		$("#"+ajaxSpDest).addClass('inAlerta');
		$("#btSalvarT").addClass('btAlerta');
	} catch(err)
	{
		alert('ocorreu algum erro ao tentar configurar o item !!!');
	}

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
