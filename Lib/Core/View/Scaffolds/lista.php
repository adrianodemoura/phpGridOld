<?php
	$this->Html->setHead('js','jquery.maskedinput.min');
?>
<div id='lista'>

<div class='row-fluid' style='margin: 0px 0px 0px 131px; padding: 0px 0px 10px 0px;'>
	<input type='button' class='btn btn-primary' name='ExibirNovo' 
		id='btNovo' value='Novo' onclick='$("#btNovo").fadeOut(); 
			$("#btFecNo").fadeIn(); 
			$("#btSalvarN").fadeIn(); 
			$("#tabNovo").fadeIn(); 
			$("#btSalvarT").fadeOut(); 
			$("#tabLista").fadeOut(); ' />
	
	<input type='button' class='btn' name='CancerNovo' 
		id='btFecNo' value='Cancelar' onclick='$("#btFecNo").fadeOut(); 
			$("#btSalvarN").fadeOut(); 
			$("#tabNovo").fadeOut(); 
			$("#btNovo").fadeIn(); 
			$("#btSalvarT").fadeIn(); 
			$("#tabLista").fadeIn(); ' />

	<input type='button' class='btn btn-success' name='SalvarNovo' 
		id='btSalvarN' value='Salvar Novo' onclick='$("#formLiNo").submit();' />

	<input type='button' class='btn btn-success' name='SalvarTodos' 
		id='btSalvarT' value='Salvar Todos' onclick='$("#formLista").submit();' />

</div>

<div class='row-fluid'>

	<?php if (isset($linksMenu)) : ?>
		<div style='position: absolute; width: 130px;'>
		<?php foreach($linksMenu as $_con => $_arrOpcs) : ?>
			<a href='<?= $_arrOpcs['link'] ?>' class='list-group-item<?php if ($_con==$controller) echo ' active'; ?>'><?= $_arrOpcs['tit'] ?></a>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div style='margin-left: 131px;'>

	<div><!-- filtros -->
	</div>

	<div>
		<table id='tabNovo' style='display: none;'><!-- novo -->
		<form name='formLiNo' id='formLiNo' method='post' action='<?= $base.strtolower($module).'/'.strtolower($controller).'/salvar' ?>' >
		<input type='hidden' name='urlRetorno' value='<?= $urlRetorno ?>' style='width: 300px;' />

		<tr><!-- cabeçalho novo -->
			<?php
				foreach($this->viewVars['fields'] as $_l2 => $_cmp) : 
				$a = explode('.',$_cmp);
				$p = $this->viewVars['esquema'][$a['0']][$a['1']];
				if (!isset($p['edicaoOff'])) :
			?>
			<th>
				<?= $this->viewVars['esquema'][$a['0']][$a['1']]['tit'] ?>
			</th>
			<?php endif; endforeach ?>
		</tr>
		<tr><!-- input novo -->
			<?php
				foreach($this->viewVars['fields'] as $_l2 => $_cmp) : 
				$a = explode('.',$_cmp);
				$p = $this->viewVars['esquema'][$a['0']][$a['1']];
				if (!isset($p['edicaoOff'])) :
			?>
			<td align='center'>
			<?php
				$cmp = $_l.'.'.$a['0'].'.'.$a['1'];
				$vlr = isset($p['default']) ? $p['default'] : '';
				echo $this->Html->getInput($cmp,array('value'=>$vlr),$p);
				if (isset($p['mascara'])) array_push($this->viewVars['onRead'],'$("#'.$this->Html->domId($cmp).'").mask("'.str_replace('#','9',$p['mascara']).'")');
			?>
			</td>
			<?php endif; endforeach ?>
		</tr>
		</form>
		</table><!-- fim novo -->

		<table id='tabLista'><!-- linhas -->
		<form name='formLista' id='formLista' method='post' action='<?= $base.strtolower($module).'/'.strtolower($controller).'/salvar' ?>' >
		<input type='hidden' name='urlRetorno' value='<?= $urlRetorno ?>' style='width: 300px;' />
		<?php foreach($this->data as $_l => $_arrMods) : ?>

		<?php if (!$_l) : ?>
		<tr><!-- cabeçalho -->
			<?php foreach($this->viewVars['fields'] as $_l2 => $_cmp) : $a = explode('.',$_cmp); ?>
			<th>
				<?= $this->viewVars['esquema'][$a['0']][$a['1']]['tit'] ?>
			</th>
			<?php endforeach ?>
		</tr>
		<?php endif ?>

		<tr>
			<?php 
				foreach($this->viewVars['fields'] as $_l2 => $_cmp) : 
				$a = explode('.',$_cmp);
				$p = $this->viewVars['esquema'][$a['0']][$a['1']];
				
				// campos id
				foreach($primaryKey as $_l3 => $_cmp3) echo "<input type='hidden' value='".$_arrMods[$a['0']][$_cmp3]."' name='data[".($_l+1)."][".$a['0']."][$_cmp3]' />";
			?>
			<td align='center'>
				<?php
					$opcs = array();
					$opcs['value'] = $_arrMods[$a['0']][$a['1']];
					$cmp = ($_l+1).'.'.$a['0'].'.'.$a['1'];
					echo $this->Html->getInput($cmp,$opcs,$p);
					if (isset($p['mascara']))  array_push($this->viewVars['onRead'],'$("#'.$this->Html->domId($cmp).'").mask("'.str_replace('#','9',$p['mascara']).'")');
				?>
			</td>
			<?php endforeach ?>
		</tr>
		<?php endforeach ?>
		</form>
		</table><!-- fim linhas -->
		</div>
	</div>
</div>
</div>