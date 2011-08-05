<?php
$aTango["menu"] = 200;

$oPageNumber = new PageNumber($aTango["total_count"]);
$oPageNumber->slice();
$sPageNumber = $oPageNumber->getHTML();

echo $sPageNumber;

foreach ($aTango["comment"] as $iComment => $aRow) {
	?>
<div style="margin: 20px 0;">
<p style="background-color: #123; margin-bottom: 10px; color: silver;"><img style="width: 48px; height: 48px;" src="https://secure.gravatar.com/avatar/<?php echo $aRow["avatar"]; ?>?s=48" align="absmiddle" /> <span style="color: white;"><?php echo $aRow["guest"]; ?></span> 针对 <a href="/archives/<?php echo $aRow["folder"]; ?>"><?php echo $aTango["archive"][$aRow["folder"]]["title"]; ?></a> 说到：</p>
<p style="line-height: 24px; width: 640px; margin-left: 54px;"><?php echo nl2br($aRow["content"]); ?></p>
</div>
	<?php
}

echo $sPageNumber;
