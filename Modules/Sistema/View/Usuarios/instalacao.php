<div class='container'>
<div class='alert'>

<?php if (isset($msg)) : ?>
<p><center><?= $msg ?></center></p>
<pre>
Lembre-se do usuário e senha padrão

e-mail: admin@admin.com.br
senha: admin
</pre>

<p>clique <a href='<?= $this->base ?>'>aqui</a> para iniciar o <b><?= SISTEMA ?></b></p>
</center>
<?php endif ?>

<?php if (isset($msgErro)) : ?>
<center><?= $msgErro ?></center>
<?php endif ?>

</div>
</div>
