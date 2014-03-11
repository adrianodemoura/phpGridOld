<?php
	$this->Html->setHead('js','jquery.maskedinput.min');
?>
<div id='lista' style='display: table; clear: both;'>

	<div style='position: absolute; width: 130px; min-height: 200px;'><!-- menu -->
	<?php if (isset($linksMenu)) : ?>
		<div style='position: absolute; width: 130px;'>
		<?php foreach($linksMenu as $_con => $_arrOpcs) : ?>
			<a href='<?= $_arrOpcs['link'] ?>' class='list-group-item<?php if ($_con==$controller) echo ' active'; ?>'><?= $_arrOpcs['tit'] ?></a>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>
	</div><!-- fim menu -->

	<div style='position: absolute; width: auto; min-width: 500px; min-height: 200px; display: table; margin-left: 135px;'><!-- esquerda -->

		<div>
			<div id='novo' style='position: absolute;  min-height: 30px; display: none;'><!-- novo -->
			<div style='margin: 0px 0px 5px 0px;'>
				<input type='button' class='btn' name='CancerNovo' id='btFecNo' value='Cancelar' onclick='$("#novo").fadeOut(); $("#ferramentas").fadeIn(); $("#tabela").fadeIn();' />
				<input type='button' class='btn btn-success' name='SalvarNovo' id='btSalvarN' value='Salvar Novo' onclick='$("#formLiNo").submit();' />
			</div>

			<table id='tabNovo'>
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
			</table>
			</div><!-- fim novo -->

			<div id='ferramentas' style='width: 100%; min-height: 35px; margin: 0px 0px 5px 0px;'><!-- ferramentas -->
				
			<div style='float: left; margin: 0px 10px 0px 0px;'><!-- paginação -->
			<?= $this->element('paginacao') ?>
			</div><!-- fim paginação -->
				
			<div style='float: left; margin: 0px 10px 0px 0px;'><!-- botoes -->
			<input type='button' class='btn btn-primary' name='ExibirNovo' id='btNovo' value='Novo' onclick='$("#novo").fadeIn(); $("#ferramentas").fadeOut(); $("#tabela").fadeOut();' />
			<input type='button' class='btn btn-success' name='SalvarTodos' id='btSalvarT' value='Salvar Todos' onclick='$("#formLista").submit();' />
			</div><!-- fim botoes -->

			<div style='line-height: 30px; display: table;'><!-- filtros -->
				aqui vai os filtros
			</div><!-- fim filtros -->

			</div><!-- fim ferrametnas -->
		</div>

		<div id='tabela' style='min-height: 200px; min-width: 500px;'><!-- tabela -->
		<table id='tabLista'><!-- linhas -->
		<form name='formLista' id='formLista' method='post' action='<?= $base.strtolower($module).'/'.strtolower($controller).'/salvar' ?>' >
		<input type='hidden' name='urlRetorno' value='<?= $urlRetorno ?>' style='width: 300px;' />
		<?php foreach($this->data as $_l => $_arrMods) : ?>

		<?php if (!$_l) : ?>
		<tr><!-- cabeçalho -->
			<th colspan='<?= count($ferramentas); ?>'>
			#
			</th>

			<?php
			foreach($this->viewVars['fields'] as $_l2 => $_cmp) : 
			$a = explode('.',$_cmp); 
			$d = ($params['dir']=='asc') ? 'desc' : 'asc';
			$l = $base.strtolower($module.'/'.$controller.'/lista/pag:'.$params['pag'].'/ord:'.str_replace('.', '_', $_cmp).'/dir:'.$d);
			?>
			<th>
				<a href='<?= $l ?>'>
					<?= $this->viewVars['esquema'][$a['0']][$a['1']]['tit'] ?>
				</a>
			</th>
			<?php endforeach ?>
		</tr>
		<?php endif ?>

		<tr><!-- ferramentas -->
			<?php
			foreach($ferramentas as $_fer => $_prop) : 
			$_prop['title'] = isset($_prop['title']) ? $_prop['title'] : $_fer;
			$arqBt 			= 'bt_'.$_fer.'.png';
			if (strpos($_prop['link'], '*'))
			{
				foreach($primaryKey as $_l2 => $_cmp)
				{ 
					$v = $_arrMods[$modelClass][$_cmp];
					$_prop['link'] = str_replace('*'.$_cmp.'*', $v, $_prop['link']);
				}
			}
			?>
			<td>
				<a href='<?= $_prop['link'] ?>'>
					<img src='<?= $base ?>img/<?= $arqBt ?>' title='<?= $_prop['title'] ?>' /> 
				</a>
			</td>
			<?php endforeach ?>

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
		<div style='margin: 0px 0px 0px 12px; font-size: 10px;'>
			Total de registros: <?= number_format($paginacao['tot'],0,',','.'); ?>
		</div>
		</div><!-- fim tabela -->

	</div><!-- fim esquerda -->

</div><!-- fim lista -->