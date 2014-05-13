<?php
	if (!isset($_SESSION['Usuario']))
	{
		Header('Location: '.$base.'sistema/usuarios/login');
		die('sem autenticação, nem a pau juvenal !!!');
	}
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<base href="<?= $base ?>" />
<title><?= (isset($tituloPagina)) ? $tituloPagina : 'site' ?></title>
<meta charset="UTF-8">

<script type="text/javascript">
	var base = '<?= $base ?>';
	var aqui = '<?= $aqui ?>';

</script>

<script type="text/javascript" src="<?= $base ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?= $base ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $base ?>js/padrao.js"></script>
<?php if (isset($tempoOn)) : ?>
<script src="<?= $base ?>js/jquery.chrony.min.js"></script>
<?php endif ?>

<link rel="stylesheet" type="text/css" href="<?= $base ?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?= $base ?>css/padrao.css" />

<?php foreach($head as $_l => $_t) echo html_entity_decode($_t)."\n"; ?>

<script type="text/javascript">
	<!-- propriedades dos campos -->
<?php
	$c = '';
	foreach($esquema[$modelClass] as $_cmp => $_arrProp)
	{
		$t = isset($_arrProp['tit']) ? $_arrProp['tit'] : $_cmp;
		$p = array();

		if (isset($_arrProp['key']) && $_arrProp['key']=='PRI') array_push($p, array('primary'=>1));
		if (isset($_arrProp['notEmpty'])) array_push($p,array('obrigatorio'=>1));

		if (!empty($p) && empty($c)) $c .= "{ $_mod : { ";

		if (!empty($p))
		{
			$c .= " $_cmp : { ";
			foreach($p as $_l => $_arrProp)
			{
				foreach($_arrProp as $_cmp => $_vlr)
				{
					if ($_l) $c .= ', ';
					$c .= "$_cmp : $_vlr";
				}
			}
			$c .= '},'."\n";
		}
	}
	if (!empty($c)) echo "\tvar campos = ".trim($c,',').'}};'."\n\n";
?>

	<!-- onRead jQuery -->
	$(document).ready(function()
	{

	<?php
	if (!empty($msgFlash)) 	echo '$("#msgFlash").fadeOut(10000);'."\n";
	if (!empty($this->viewVars['onRead'])) 	foreach($this->viewVars['onRead'] as $_l => $_line) echo $_line.";\n";
	?>

	<?php if (isset($tempoOn)) :  ?>
	$('#contador').chrony({ 
		minute: <?= $tempoOn ?>, displayHours: false, finish: function() 
		{
			window.location='<?= $base.'sistema/usuarios/sair'; ?>'
		}
	});
	<?php endif; ?>

});
</script>

</head>

<body>

<div id="corpo" class="container-fluid">

<?php if (!empty($msgFlash)) : ?>
	<div class="row offset1">
		<div id='msgFlash' class='<?= $msgFlash['class'] ?>'>
			<?= $msgFlash['txt'] ?>

		</div>
	</div>
<?php endif ?>

	<div id="cabecalho" class="row">
	<div id='cab1' class='container-fluid'>
		<div style='float: left;'>
			<a href='<?= $base ?>' style='float: left; margin: 0px 5px 0px 0px;'><?= SISTEMA ?></a>
			<?php if (AMBIENTE!='PRODUÇÃO') : ?><span style="float: right; color: red; letter-spacing: 2px; font-weight:bold">(<?= AMBIENTE ?>)</span><?php endif ?>
		</div>
		<div style='float: right;'>
			<span style='float: left; margin-right: 5px'>sua sessão expira em</span>
			<span id="contador"></span>
		</div>

	</div>
	
	<div id='cab2' class='container-fluid'>
		<div style='float: left;'>

			<div style='float: left; color: #000; text-align: center; width: 200px;'>
			<form name='formModulo' method='post' action='<?= getBase() ?>sistema/usuarios/set_modulo/'>
			<select name='data[modulo]' id='TModulo' title='Clique aqui para trocar o módulo ...' 
					onchange='this.form.submit()' style='width: 180px; border: 0px; background-color: #fff; padding: 1px 3px 2px 3px; font-size: 15px; letter-spacing: 2px;'>
				<?php foreach($modulos as $_modulo => $_arrCmps) : ?>
				<option value='<?= strtolower($_modulo) ?>' <?php if ($_modulo==strtoupper($module)) echo ' selected="selected"'; ?> >
					<?= $_arrCmps['titulo'] ?>
				</option>
				<?php endforeach ?>
			</select>
			</form>
			</div>

			<div style='float: left;'>
				<?= $tituloController.' :: '.$tituloAction ?>
			</div>
		</div>

		<div style='font-size: 10px; float: right;'>
			<?= $this->element('login_usuario',array('base'=>$base,'module'=>$module)) ?>
		</div>
		
		<?= $this->element('padrao_ferramentas'); ?>

	</div>
	</div><!-- fim cabeçalho -->
		
	<div id='conteudo'>
		<?= $conteudo; ?>

	</div><!-- fim conteudo -->
	
	<?php if ($_SESSION['Usuario']['perfil']=='ADMINISTRADOR') : ?>
	<?= $this->element('permissoes'); ?>
	<?php endif ?>

	<div id="rodape" class="row">
		<?php if (!empty($_SESSION['sqldump'])) echo $this->element('sql_dump',array('sql_dump'=>$sql_dump,'module'=>$module)) ?>
	</div><!-- fim rodapé -->


</div><!-- fim corpo -->
<div id='tampaTudo'>tampaTudo</div>
</body>
</html><?= debug($this->data); ?>
<!-- tempo de execução <?= round((microtime(true)-INICIO),6) ?> segundos -->
