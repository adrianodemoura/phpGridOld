String.prototype.capitalize = function() 
{
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function showModal(janela)
{
	var maskHeight 	= $(document).height();
	var maskWidth 	= $(window).width();
	$('#tampaTudo').css({'width':maskWidth,'height':maskHeight});
	$('#tampaTudo').fadeTo("slow",0.8);	
	$("#"+janela).fadeIn(1000); 
}

function setPermissao(id)
{
	var btPer 	= id.substring(2);
	var tipo	= id.substring(0,2);
	var url		= base+'sistema/usuarios/set_permissao';
	url += '/modulo:'+$('#PermissaoModule').val();
	url += '/controller:'+$('#PermissaoController').val();
	url += '/permissao:'+btPer;
	url += '/tipo:'+tipo;

	if (tipo=='ok')
	{
		$("#ok"+btPer).fadeOut(0,function() 
		{ 
			$('#ajaxDest').load(url, function(resposta, status, xhr)
			{
				if (status=='success')
				{
					console.log(resposta);
					$("#fa"+btPer).fadeIn(1); 
				}
			});
		});
	} else
	{
		$("#fa"+btPer).fadeOut(0, function()
		{
			$('#ajaxDest').load(url, function(resposta, status, xhr)
			{
				if (status=='success')
				{
					console.log(resposta);
					$("#ok"+btPer).fadeIn(1);
				}
			});
		});
	}
}

function setPerfil(idPerfil)
{
	var url = base+'sistema/usuarios/set_perfil/perfil:'+idPerfil;
	console.log(url);
	document.location.href= url;
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
 * Exibe o formulário ajax
 *
 */
function showAjaxForm()
{
	$('#ajaxPC').html(1);
	$('#ajaxPesq').val('');
	setAjaxTab();
	$("#ajaxForm").fadeIn();
	showModal("ajaxForm");
	$("#ajaxPesq").focus();
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
			//console.log(resposta);
			var jArrResposta 	= resposta.split('*');
			var table			= '<table border="1px" id="ajaxTab">'+"\n";
			$.each(jArrResposta, function(i, linha)
			{
				var jArrLinha = linha.split(';');
				if (jArrLinha[0].length>0)
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

/**
 * Fecha o formulário ajax, e volta pra página anterior
 *
 */
function showLista()
{
	var pagAnt = $("pagAnter").text();
	if (pagAnt==undefined) pagAnt = 'lista';
	$("#ajaxResp").html("");
	$("#tampaTudo").fadeOut();
	$("#ajaxForm").fadeOut(); 
	$("."+pagAnt).fadeIn(); 
}
