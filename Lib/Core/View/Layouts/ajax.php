<?php
	if (isset($data))
	{
		 foreach($data as $_l => $_arrMods)
		{
			foreach($_arrMods as $_mod => $_arrCmps)
			{
				$l = 0;
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					if ($l) echo ';';
					echo $_vlr;
					$l++;
				}
			}
			if ($debug) echo "<br />";
			echo "*\n";
		}
	}
?>

<?php //echo $this->element('sql_dump',array('sql_dump'=>$sql_dump,'module'=>$module)) ?>
