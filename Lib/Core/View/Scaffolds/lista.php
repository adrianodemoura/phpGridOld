<?php
	$this->Html->setHead('css','lista');
	$this->Html->setHead('js','jquery.maskedinput.min');
	$this->Html->setHead('js','lista');
?>

<div id='ajaxForm' class='container'>
	<center>
		<h4><span id='ajaxTit'></span></h4>
		<input type='text' 	 name='ajaxPesq' id='ajaxPesq'  value='' style='width: 500px; style="float: left;"' />
		<input type='hidden' name='ajaxDest' id='ajaxDest'  value='' style='width: 800px;' />
		<input type='hidden' name='ajaxCmp'  id='ajaxCmp' 	value='' style='width: 800px;' />
		<input type='button' name='btAjaxFechar' value='Fechar' class='btn btn-default' onclick='showLista();' />
	</center>
	<div id='ajaxResp'>
	</div>
</div>

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
			
			<?php if (isset($marcadores)) : 
				array_push($this->viewVars['onRead'],'$("#cxSel").change(function() 
				{ $("#marcador").val($(this).val()); $("#formLista").submit(); })');
			?>
			
			<select name='cxSel' id='cxSel' >
				<option value=''>-- Aplicar aos Marcadores --</option>
				<?php foreach($marcadores as $_txt => $_link) : ?>
					<option value='<?= $_link ?>'><?= $_txt ?></option>
				<?php endforeach ?>
			</select>
			
			<?php endif ?>
			</div><!-- fim botoes -->

			</div><!-- fim ferrametnas -->

			<?php if (isset($filtros)) : ?>
			<div id='filtros'>
				<div style='width: 22px; text-align: center;'><img src='<?= $base ?>img/bt_filtro.png' title='filtros' >
				</div>
				<form name='formFiltro' id='formFiltro' method='post' action='<?= $base.strtolower($module).'/'.strtolower($controller).'/set_filtro' ?>' >
				<?php 
					foreach($filtros as $_cmp => $_arrProp) : 
					$_arrProp['empty']  = isset($_arrProp['empty']) ? $_arrProp['empty'] : '-- Escolha um Filtro --';
					$_arrProp['options']= isset($esquema[$modelClass][$_cmp]['options']) ? $esquema[$modelClass][$_cmp]['options'] : array();
					array_push($this->viewVars['onRead'], '$("#Filtro'.ucfirst($_cmp).'").click(function() { this.form.submit(); })');
				?>
					<div>
					<select name='filtro[<?= $_cmp ?>]' id='Filtro<?= ucfirst($_cmp) ?>'>
						<option value=''><?= $_arrProp['empty'] ?></option>
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
			$t = $p['tit'];
			if (isset($p['belongsTo']))
			{
				$c = $this->Html->getFieldRel($p['belongsTo']);
				$t = substr($t,0,strlen($t)-2);
			}
			
			$l = $base.strtolower($module.'/'.$controller.'/lista/pag:'
				.$params['pag'].'/ord:'.str_replace('.', '_', $c).'/dir:'.$d);
			?>
			<th>
				<a href='<?= $l ?>'>
					<?= $t ?>
				</a>
			</th>
			<?php endforeach ?>
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
			<?php // loop nas ferramentas
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

			<?php  // loop nos campos para escrever a coluna de cada linha
				foreach($this->viewVars['fields'] as $_l2 => $_cmp) : 
					$a = explode('.',$_cmp);
					$p = $this->viewVars['esquema'][$a['0']][$a['1']];
					$cmp = ($_l+1).'.'.$a['0'].'.'.$a['1'];
					$p['value'] = $_arrMods[$a['0']][$a['1']];
					// campos primários
					foreach($primaryKey as $_l3 => $_cmp3) echo "<input type='hidden' value='".$_arrMods[$a['0']][$_cmp3]."' name='data[".($_l+1)."][".$a['0']."][$_cmp3]' />";
			?>
				<td align='center'>
					<?php
						echo $this->Html->getInput($cmp,$p,$this->data[$_l]);
						if (isset($p['mascara']))  array_push($this->viewVars['onRead'],'$("#'.$this->Html->domId($cmp).'").mask("'.str_replace('#','9',$p['mascara']).'")');
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
