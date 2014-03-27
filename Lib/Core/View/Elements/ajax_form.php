<style>
#ajaxForm
{
	position: absolute;
	left: 50%;
	top: 50%;
	width: 440px;
	height: auto;
	margin-top: -350px;
	margin-left: -220px;
	display: none;
	z-index: 9999;
	padding: 20px;
	background-color: #fff;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
}
#ajaxTab .ajaxTr
{
	/*background-color: #ddd;*/
}
#ajaxTab .ajaxTd
{
	padding: 1px 4px 1px 4px;
	/*background-color: #fff;*/
	cursor: pointer;
	font-size: 11px;
}
#ajaxPagi
{
	background-color: #ddd;
}
#ajaxPagi div
{
	display: block;
	float: left;
	text-align: center;
	cursor: pointer;
	border: 1px solid #ccc;
	padding: 5px 15px 5px 15px;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
}
#ajaxPagi div:hover
{
	background-color: #ddd;
}
#ajaxPagi .ajaxPi
{
	margin-left: 3px;
}
#ajaxPagi #ajaxPC
{
	background-color: #eee;
}

</style>
<div id='ajaxForm'>
	<center>
		<h4><span id='ajaxTit'>
		</span></h4>

		<div id='ajaxPagi'>
			<div id='ajaxP1' class='ajaxPi' title='primeira página'>&laquo;</div>  
			<div id='ajaxPA' class='ajaxPi' title='pagina anterior'>&lsaquo;</div> 
			<div id='ajaxPC' class='ajaxPi' title='pagina corrente'>1</div> 
			<div id='ajaxPP' class='ajaxPi' title='próxima página'>&rsaquo;</div> 
			<div id='ajaxPU' class='ajaxPi' title='próxima página'>&raquo;</div> 
		</div>

		<input type='button' name='btAjaxFechar' value='Fechar' class='btn btn-primary' onclick='showLista();' />
		<br /><br />
		<input type='text' 	 name='ajaxPesq' id='ajaxPesq'  value='' style='width: 400px; style="float: left;"' />
		<br /><br />
		<input type='hidden' name='ajaxDest' id='ajaxDest'  value='' style='width: 800px;' />
		<input type='hidden' name='ajaxCmp'  id='ajaxCmp' 	value='' style='width: 800px;' />
	</center>

	<div id='ajaxResp'>
	</div>

	<div id='ajaxTo' title='total'>
	</div>

</div>