<?php $linksMenu = isset($this->viewVars['linksMenu']) ? $this->viewVars['linksMenu'] : null; ?>

<div style='position: absolute; width: 130px; min-height: 200px;'><!-- menu -->

<?php if (isset($linksMenu)) : ?>

	<div style='position: absolute; width: 130px;'>

	<?php foreach($linksMenu as $_con => $_arrOpcs) : ?>

		<a href='<?= $_arrOpcs['link'] ?>' class='list-group-item<?php if ($_con==$controller) echo ' active'; ?>'>
			<?= $_arrOpcs['tit'] ?>
		</a>

	<?php endforeach; ?>

	</div>

<?php endif; ?>

</div><!-- fim menu -->