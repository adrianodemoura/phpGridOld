<?php
	$this->Html->setHead('css','lista');
	$this->Html->setHead('js','jquery.maskedinput.min');
	$this->Html->setHead('js','lista');
?>

<?php $this->element('ajax_form'); ?>

<div id='lista' style='display: table; clear: both;'>

<?php $this->element('menu_lista'); ?>

	<div style='position: absolute; width: auto; min-width: 500px; min-height: 200px; 
				isplay: table; margin-left: 135px;'><!-- esquerda -->

		<div>

			<?php $this->element('lista_novo'); ?>

			<div id='ferramentas' style='width: 100%; min-height: 35px; margin: 0px 0px 5px 0px;'><!-- ferramentas_topo -->
				
			<div style='float: left; margin: 0px 10px 0px 0px;'><!-- paginação -->
			<?= $this->element('paginacao') ?>
			</div><!-- fim paginação -->

			<?php 
			if (isset($botoesLista)) : ?>
			<div style='float: left; margin: 0px 10px 0px 0px;'><!-- botoes -->
			<?php
			foreach($botoesLista as $_l => $_arrProp)
			{
				if (!empty($_arrProp))
				{
					echo "<input";
					foreach($_arrProp as $_tag => $_vlr) echo " $_tag='$_vlr'";
					echo " /> \n";
				}
			}
			?>
			</div>
			<?php endif ?>
			
			<?php if (isset($marcadores)) : ?>
			<div style='float: left; margin: 9px 0px 0px 0px;'><!-- marcadores -->
			<select name='cxSel' id='cxSel' >
				<option value=''>-- Aplicar aos Marcadores --</option>
				<?php foreach($marcadores as $_txt => $_link) : ?>
					<option value='<?= $_link ?>'><?= $_txt ?></option>
				<?php endforeach ?>
			</select>
			
			<?php endif ?>
			</div><!-- fim marcadores -->

			</div><!-- fim ferramentas_topo -->

			<?php if (isset($filtros)) : ?>
			<div id='filtros'><!-- filtros -->
				<div style='width: 22px; text-align: center;'><img src='<?= $base ?>img/bt_filtro.png' title='filtros' >
				</div>
				<form name='formFiltro' id='formFiltro' method='post' action='<?= $base.strtolower($module).'/'.strtolower($controller).'/set_filtro' ?>' >
				<?php 
					foreach($filtros as $_cmp => $_arrProp) : 
					array_push($this->viewVars['onRead'], 
						'$("#Filtro'.ucfirst($_cmp).'").change(function() { this.form.submit(); })');
				?>
					<div>
					<select name='filtro[<?= $_cmp ?>]' id='Filtro<?= ucfirst($_cmp) ?>'>
						<option value=''><?= $_arrProp['emptyFiltro'] ?></option>
						<?php
							foreach($_arrProp['options'] as $_vlr => $_show) : 
							$s = isset($_SESSION['Filtros'][$module][$controller][$_cmp]) 
								? $_SESSION['Filtros'][$module][$controller][$_cmp] 
								: '';
							if ($_vlr==$s && strlen($s)>0) $s = 'selected="selected"';
						?>
						<option <?= $s ?> value='<?= $_vlr ?>'><?= $_show ?></option>
						<?php endforeach ?>
					</select>
					</div>
				<?php endforeach ?>
				</form>
			</div>
		<?php endif ?>
		</div>

		<div id='tabela' style='min-height: 200px; min-width: 500px;'><!-- tabela -->
		<table id='tabLista'><!-- linhas -->
		<form name='formLista' id='formLista' method='post' action='<?= $base.strtolower($module).'/'.strtolower($controller).'/salvar' ?>' >
		<input type='hidden' name='urlRetorno' value='<?= $urlRetorno ?>' style='width: 300px;' />
		<input type='hidden' name='marcador' value='' id='marcador' style='width: 300px;' />
		<?php foreach($this->data as $_l => $_arrMods) : ?>

		<?php if (!$_l) : ?>
		<tr><!-- cabeçalho das linhas -->
			<th>
			<input type='checkbox' name='cxAll' id='cxAll' title='clique aqui para selecionar todos'
				<?php
				array_push($this->viewVars['onRead'], '$("#cxAll").click(function(event) 
				{
					if (this.checked==true)
						$(".cxLista").each(function() { this.checked = true; });
					else
						$(".cxLista").each(function() { this.checked = false; });
				})');
			?>
			/>
			</th>
			<th colspan='<?= count($ferramentas); ?>'>
			#
			</th>

			<?php
			foreach($this->viewVars['fields'] as $_l2 => $_cmp) : 
			$c = $_cmp;
			$a = explode('.',$_cmp); 
			$d = ($params['dir']=='asc') ? 'desc' : 'asc';
			$p = $this->viewVars['esquema'][$a['0']][$a['1']];
			$i = isset($this->viewVars['esquema'][$a['0']][$a['1']]['key']) ? true : false; // é indice
			$t = $p['tit'];
			if (isset($p['belongsTo']))
			{
				$c = $this->Html->getFieldRel($p['belongsTo']);
			}

			$l = $base.strtolower($module.'/'.$controller.'/lista/pag:'
				.$params['pag'].'/ord:'.str_replace('.', '_', $c).'/dir:'.$d);
			?>
			<th class="th<?= $this->Html->domId($a['1']) ?>">
				<?php if ($i) : ?>
				<a href='<?= $l ?>'><?= $t ?></a>
				<?php else : ?>
				<?= $t ?>
				<?php endif ?>
			</th>
			<?php endforeach ?>
		</tr>
		<?php endif ?>

		<?php if (isset($erros[$_l])) : ?>
		<tr>
			<td colspan='100' class='td_lista_erro'>
				<?= $erros[$_l] ?>
			</td>
		</tr>
		<?php endif ?>

		<tr><!-- linha a linha -->
			<td>
				<?php
					$ids = '';
					foreach($primaryKey as $_l3 => $_cmp3) 
					{
						if ($_l3) $ids .= ',';
						$ids .= $_cmp3.'='.$_arrMods[$modelClass][$_cmp3];
					}
				?>
				<input type='checkbox' class='cxLista' name='cx[<?= $ids ?>]' id='cx<?= ($_l+1) ?>' />
			</td>

			<?php // loop nas ferramentas de cada linha
			foreach($ferramentas as $_fer => $_prop) : 
				$_prop['title'] = isset($_prop['title']) ? $_prop['title'] : $_fer;
				$arqBt 			= 'bt_'.$_fer.'.png';
				if (strpos($_prop['link'], '*')) // substituindo o campo pelo valor do campo
				{
					foreach($primaryKey as $_l2 => $_cmp)
					{ 
						$v = $_arrMods[$modelClass][$_cmp];
						$_prop['link'] = str_replace('*'.$_cmp.'*', $v, $_prop['link']);
					}
				}
				?>
				<td>
					<a href='<?= $_prop['link'] ?>' 
					<?php if (isset($_prop['onclick'])) : ?>
						onclick="<?= $_prop['onclick'] ?>"
					<?php endif; ?>
					>
						<img src='<?= $base ?>img/<?= $arqBt ?>' title='<?= $_prop['title'] ?>' /> 
					</a>
				</td>
			<?php endforeach ?>

			<?php  // loop nos campos para escrever a coluna de cada linha
				foreach($primaryKey as $_l3 => $_cmp3) // campos primários
				{
					echo "<input type='hidden' value='".$_arrMods[$a['0']][$_cmp3]
						."' name='data[".($_l+1)."][".$modelClass."][".$_cmp3."]' />";
				}
				foreach($this->viewVars['fields'] as $_l2 => $_cmp) : 
					$a = explode('.',$_cmp);
					$p = $this->viewVars['esquema'][$a['0']][$a['1']];
					$cmp = ($_l+1).'.'.$a['0'].'.'.$a['1'];
					$p['value'] = $_arrMods[$a['0']][$a['1']];
			?>
				<td align='center' id='td<?= $this->Html->domId(($_l+1).'.'.$a['1']) ?>' class="td<?= $this->Html->domId($a['1']) ?>">
					<?php
						echo $this->Html->getInput($cmp,$p,$this->data[$_l]);
						if (isset($p['mascara']))
						{
							array_push($this->viewVars['onRead'],'$("#'.$this->Html->domId($cmp).'").mask("'.str_replace('#','9',$p['mascara']).'")');
						}
					?>
				</td>
			<?php endforeach ?>
		</tr>
		
		<?php endforeach ?>
		</form>
		</table><!-- fim linhas -->
		<div style='margin: 0px 0px 0px 12px; font-size: 14px;'>
			Total de registros: <?= number_format($paginacao['tot'],0,',','.'); ?>
		</div>
		</div><!-- fim tabela -->

	</div><!-- fim esquerda -->

</div><!-- fim lista -->
