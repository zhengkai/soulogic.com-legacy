<?php
Page::setBodyClass("tweets");

$aTango["menu"] = 500;
?>
<form class="compose" method="POST" action="tweet.do.php">
<textarea name="content" style="height: 200px;">
</textarea>
<p style="text-align: left;"><button>Post</button></p>
</form>
<?php
$sDatePrev = "";
foreach ($aTango["plan"] as $iID => $aRow) {
	$sDate = date("Y-m-d", $aRow["datetime_c"]);
	$sTime = date("H:i", $aRow["datetime_c"]);
	$sDateShow = "";
	if ($sDatePrev != $sDate) {
		$sDateShow = "<p class=\"date\">".$sDate."</p>";
		$sDatePrev = $sDate;
	}
	?>
<div class="msg">
<div class="datetime"><?php echo $sDateShow; ?><p class="time"><?php echo $sTime; ?></p></div>
<div class="srcTag<?php if (intval($aRow["itype"]) === 1) { echo " copy"; } ?>">&#160;</div>
<div class="content"><?php echo nl2br($aRow["content"]); ?></div>
</div>
	<?php

}

