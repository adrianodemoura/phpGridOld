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
	$("#"+janela).fadeIn(2000); 
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
