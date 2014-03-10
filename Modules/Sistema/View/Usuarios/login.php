<?php 
	$this->head('css','index');
	$this->head('js','index');
?>
<div class='container-fluid'>
	<br />
	<center>
	<!-- <img src='<?= $base ?>img/angu.png' width='130px' /> -->
	<h1><?= SISTEMA ?> - <span style='color: red;'>beta</span></h1>
	</center>
	<br />
</div>

<div id='login' class="container">
	<form id='formLogin' method='post' action='' class="form-horizontal">


		<?= $this->Html->getInput('Usuario.email',array('value'=>'admin@admin.com.br','class'=>'form-control','placeholder'=>'e-mail','autofocus'=>'autofocus')); ?>
		<?= $this->Html->getInput('Usuario.senha',array('value'=>'admin','type'=>'password','placeholder'=>'senha','class'=>'form-control')); ?>

	<div style='line-height: 34px; margin: 20px 0px 0px 0px;'>
		<?= $this->Html->getInput('btEnviar',array('name'=>'btEnviar','idDiv'=>'divEnviar','type'=>'submit','value'=>'Enviar', 'class'=>'btn btn-large btn-primary')); ?>

		<div id='reg'>
			<a href='<?= $base ?>sistema/usuarios/esqueci_a_senha'>esqueci a senha</a> |
			<a href='<?= $base ?>sistema/usuarios/registro'>registrar</a>
		</div>
	</div>
	</form>
</div>

<div class="container">
<center><a href='<?= $base ?>'>Volta para a página inicial</a></center>
</div>
