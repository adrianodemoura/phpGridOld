$(document).ready(function()
{
	$('#formLiNo').submit(function() 
	{
		var retorno = true;
		var msgErro	= '';
		$.each(campos, function(index, object) 
		{
		    $.each(object, function(cmp, arrProp)
		    {
		    	var pri = arrProp['primary'];
		    	var obr = arrProp['obrigatorio'];
		    	if (pri==undefined && obr!=undefined)
		    	{
		    		for(var i=0; i<1; i++)
		    		{
		    			var id = "#"+i+index+cmp.capitalize();
		    			vlr = $(id).val();
		    			if (vlr!=undefined)
		    			{
			    			if (vlr.length==0)
			    			{
			    				msgErro = 'O Campo '+cmp+', é de preenchimento obrigatório !!!';
			    				$(id).focus();
			    			}
			    		} else console.log(vlr+' '+id);
		    		}
		    	}
		    })
		}); 
		if (msgErro.length>0)
		{
			alert(msgErro);
			return false;
		} else return true;
	});

	//
	$(document).on("click",function()
	{
		//$(".clFormPes").fadeOut();
		//console.log(this.id);
		//console.log($(".clFormPes").css("visibility"));
	});
	
	// executa a pesquisa pelo campo do formPes
	$(".cmpFormPes").keydown(function(e)
	{
		var e 			= e || window.event;
		var idCmp 		= this.id;
		var cmp			= idCmp.replace(/cmpFormPes/g, '');
		var tip			= $('#tipFormPes'+cmp).val();
		if (tip==true) tip='=';
		var divCmp		= 'divFormPes'+cmp;
		var url			= aqui;
		var corta		= url.indexOf('/pes:',0);
		if (corta>0)
		{
			url = url.substring(0,corta);
		}
		url += '/pes:'+cmp+tip;

		if (e.keyCode==27)
		{
			$('#'+divCmp).fadeOut();
			$('#'+idCmp).val('');
		} else if(e.keyCode==13)
		{
			url += $('#'+idCmp).val();
			document.location.href= url;
			console.log(corta+' '+url);
		}
	});
	
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

/**
 * Exibe o formulário de pesquisa de um campo
 * 
 * @return	void
 */
function showFormPesquisa(cmp)
{
	var idDiv = 'divFormPes'+cmp;
	var idCmp = 'cmpFormPes'+cmp;
	$("#"+idCmp).val();
	$("#"+idDiv).fadeIn();
	$("#"+idCmp).focus();
}
