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