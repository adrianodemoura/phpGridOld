<?php
	$this->Html->setHead('css','lista');
	$this->Html->setHead('js','lista');
	$this->Html->setHead('js','jquery.maskedinput.min');
?>

<?php $this->element('ajax_form'); ?>

<div id='lista' class='row'>

	<div class='col-md-2'><!-- menu lista -->
		<?php $this->element('menu_lista'); ?>

	</div><!-- fim menu lista -->

	<div class='col-md-10'><!-- direita -->

		<?php $this->element('tabela_novo'); ?>

		<div class='row ferramentas'><!-- ferramentas_topo -->
			
			<?php if (isset($this->viewVars['paginacao'])) : ?>
			<div><!-- paginação -->
			<?= $this->element('paginacao') ?>
			</div><!-- fim paginação -->
			<?php endif ?>

			<?php 
			if (isset($this->viewVars['botoesLista'])) : ?>
			<div class=''><!-- botoes -->
			<?php
			foreach($this->viewVars['botoesLista'] as $_l => $_arrProp)
			{
				if (!empty($_arrProp))
				{
					echo "<input"; foreach($_arrProp as $_tag => $_vlr) echo " $_tag='$_vlr'"; echo " /> \n";
				}
			}
			?>
			</div><!-- fim botoes -->
			<?php endif ?>
				
			<?php if (isset($this->viewVars['marcadores'])) : ?>
			<div class=''><!-- marcadores -->
			<select name='cxSel' id='cxSel' >
				<option value=''>-- Aplicar aos Marcadores --</option>
				<?php foreach($this->viewVars['marcadores'] as $_txt => $_link) : ?>
					<option value='<?= $_link ?>'><?= $_txt ?></option>
				<?php endforeach ?>
			</select>
			</div><!-- fim marcadores -->
			<?php endif ?>

		</div><!-- fim ferramentas_topo -->

		<?php if (isset($this->viewVars['filtros']) && !empty($this->viewVars['filtros'])) : ?>
		<div class='row filtro'>
			<?php echo $this->element('filtro'); ?>
		</div><!-- fim filtro -->
		<?php endif ?>

		<div class='row tabela'><!-- tabela -->
		<?php echo $this->element('tabela'); ?>
		</div><!-- fim tabela -->

	</div><!-- fim direita -->

</div><!-- fim lista -->