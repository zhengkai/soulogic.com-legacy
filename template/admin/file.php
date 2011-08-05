<?php
$aTango["menu"] = 200;

foreach ($aTango["comment"] as $iComment => $aRow) {
	?>
<div>

<p><img src="http://www.gravatar.com/avatar/<?php echo $aRow["avatar"]; ?>?s=48" align="absmiddle" /> </p>
</div>
	<?php
}

// dump($aTango);
