<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<title><?= (isset($tituloPagina)) ? $tituloPagina : 'site' ?></title>
<meta charset="UTF-8">

<script type="text/javascript">
var base = '<?= $base ?>';

</script>
	
<script type="text/javascript" src="<?= $base ?>js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $base ?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?= $base ?>css/publico.css" />

<?php foreach($head as $_l => $_t) echo html_entity_decode($_t)."\n"; ?>

<script type="text/javascript">
$(document).ready(function()
{
<?php 
	if (isset($onRead)) foreach($onRead as $_l => $_line) echo $_line.";\n";
	if (isset($msgFlash) && !empty($msgFlash)) echo '$("#msgFlash").fadeOut(4000)'.";\n";

?>
});
</script>

</head>

<body>
<div id="corpo" class="container-fluid">

	<?php if (isset($msgFlash) && !empty($msgFlash)) : ?>
	<div class="row">
		<div id='msgFlash' class='<?= $msgFlash['class'] ?>'>
			<?= $msgFlash['txt'] ?>

		</div>
	</div>
	<?php endif ?>

	<div id="cabecalho" class="row">
		<div class='text-right' style="margin-right: 5px;">
			<?= $this->element('login_usuario') ?>

		</div>
	</div><!-- fim cabeçalho -->
		
	<div id='conteudo' class="row">
		<?= $conteudo; ?>

	</div><!-- fim conteudo -->

	<div id="rodape" class="row">
		<div class="col-md-12">
			<?= $this->element('sql_dump',array('sql_dump'=>$sql_dump,'module'=>$module)) ?>
		</div>
	</div><!-- fim rodapé -->

</div><!-- fim corpo -->
</body>
</html>
