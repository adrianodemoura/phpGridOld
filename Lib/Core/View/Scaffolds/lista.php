<?php
	$this->Html->setHead('css','lista');
	$this->Html->setHead('js','lista');
	$this->Html->setHead('js','jquery.maskedinput.min');
?>

<?php $this->element('ajax_form'); ?>

<div id='lista'>

	<div style='width: 200px; min-height: 500px; float: left; margin: 0px 3px 0px 0px;'><!-- menu lista -->
		<?php $this->element('menu_lista'); ?>

	</div><!-- fim menu lista -->

	<div style='display: table;'><!-- direita -->

		<?php $this->element('tabela_novo'); ?>

		<div class='ferramentas'><!-- ferramentas_topo -->
			
			<?php if (isset($this->viewVars['paginacao'])) : ?>
			<?= $this->element('paginacao') ?>
			<?php endif ?>

			<?php 
			if (isset($this->viewVars['botoesLista'])) : ?>
			<?php
			foreach($this->viewVars['botoesLista'] as $_l => $_arrProp)
			{
				if (!empty($_arrProp))
				{
					echo "<input"; foreach($_arrProp as $_tag => $_vlr) echo " $_tag='$_vlr'"; echo " /> \n";
				}
			}
			?>
			<?php endif ?>
				
			<?php if (isset($this->viewVars['marcadores'])) : ?>
			<select name='cxSel' id='cxSel' >
				<option value=''>-- Aplicar aos Marcadores --</option>
				<?php foreach($this->viewVars['marcadores'] as $_txt => $_link) : ?>
					<option value='<?= $_link ?>'><?= $_txt ?></option>
				<?php endforeach ?>
			</select>
			<?php endif ?>

		</div><!-- fim ferramentas_topo -->

		<?php if (isset($this->viewVars['filtros']) && !empty($this->viewVars['filtros'])) : ?>
		<div class='filtro'>
			<?php echo $this->element('filtro'); ?>
		</div><!-- fim filtro -->
		<?php endif ?>

		<div class='tabela'><!-- tabela -->
		<?php echo $this->element('tabela'); ?>
		</div><!-- fim tabela -->

	</div><!-- fim direita -->

</div><!-- fim lista -->
