<?php

	$pagA = ($this->viewVars['paginacao']['pag']-1);
	if ($pagA<1) $pagA = 1;

	$pagP = ($this->viewVars['paginacao']['pag']+1);
	if ($pagP>$this->viewVars['paginacao']['pagU']) $pagP = $this->viewVars['paginacao']['pagU'];

	$link1 = $this->viewVars['base'].strtolower($module.'/'.$controller.'/lista/pag:1/ord:'.$this->viewVars['params']['ord'].'/dir:'.$this->viewVars['params']['dir']);
	$linkA = $this->viewVars['base'].strtolower($module.'/'.$controller.'/lista/pag:'.$pagA.'/ord:'.$this->viewVars['params']['ord'].'/dir:'.$this->viewVars['params']['dir']);
	$linkP = $this->viewVars['base'].strtolower($module.'/'.$controller.'/lista/pag:'.$pagP.'/ord:'.$this->viewVars['params']['ord'].'/dir:'.$this->viewVars['params']['dir']);
	$linkU = $this->viewVars['base'].strtolower($module.'/'.$controller.'/lista/pag:'.$this->viewVars['paginacao']['pagU'].'/ord:'.$this->viewVars['params']['ord'].'/dir:'.$this->viewVars['params']['dir']);

?>
<ul class="pagination" style='margin: 0px; padding: 0px;'>
  <li><a href="<?= $link1 ?>">&laquo;</a></li>
  <li><a href="<?= $linkA ?>">&lsaquo;</a></li>

  <div style='float: left; line-height: 26px; margin: 2px 2px 0px 2px; '>
  <input type='text' disabled='disabled' value='<?= $this->viewVars['paginacao']['pag'] ?>' style='border: 1px solid #ccc; width: 40px; text-align: center;' />
  </div>

  <li><a href="<?= $linkP ?>">&rsaquo;</a></li>
  <li><a href="<?= $linkU ?>">&raquo;</a></li>
</ul>
