<?php
	// nome do arquivo
	$arqCsv = isset($this->viewVars['arqCsv']) ? $this->viewVars['arqCsv'] : 'arquivo';

	header('Content-type: application/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename="'.$arqCsv.'.csv"');

	echo $conteudo;
?>