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
<?php if (isset($tempoOn)) : ?>
<script src="<?= $base ?>js/jquery.chrony.min.js"></script>
<?php endif ?>

<link rel="stylesheet" type="text/css" href="<?= $base ?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?= $base ?>css/padrao.css" />

<?php foreach($head as $_l => $_t) echo html_entity_decode($_t)."\n"; ?>

<script type="text/javascript">
$(document).ready(function()
{

<?php
if (!empty($msgFlash)) 	echo '$("#msgFlash").fadeOut(4000);'."\n";
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
			<a href='<?= $base ?>'><?= SISTEMA ?></a>
			<?php if (AMBIENTE!='PRODUÇÃO') : ?>
				(<span style='color: red; letter-spacing: 2px; font-weight: bold;'>
					<?= AMBIENTE ?>
				</span>)
		<?php endif ?>
		</div>
		<div style='float: right;'>
			<span style='float: left; margin-right: 5px'>sua sessão expira em</span>
			<span id="contador"></span>
		</div>

	</div>
	<div id='cab2' class='container-fluid'>
		<div style='float: left;'>
			<?= html_entity_decode($position) ?>
		</div>
		<div style='font-size: 10px; float: right;'>
			<?= $this->element('login_usuario',array('base'=>$base,'module'=>$module)) ?>
		</div>

	</div>
	</div><!-- fim cabeçalho -->
		
	<div id='conteudo' class="container-fluid" style='margin-left: -18px;'>
		<?= $conteudo; ?>

	</div><!-- fim conteudo -->

	<div id="rodape" class="row">
		<?php if (!empty($_SESSION['sql_dump'])) echo $this->element('sql_dump',array('sql_dump'=>$sql_dump,'module'=>$module)) ?>
	</div><!-- fim rodapé -->


</div><!-- fim corpo -->
</body>
</html>
<!-- tempo de execução <?= round((microtime(true)-INICIO),6) ?> segundos -->
