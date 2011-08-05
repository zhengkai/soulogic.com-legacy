<?php
Page::setBodyClass("tweets");

$aRow = current($aTango["plan"]);
$sDate = date("Y-m-d", $aRow["datetime_c"]);
$aRow = end($aTango["plan"]);
$sEnd = date("Y-m-d", $aRow["datetime_c"]);
if ($sDate !== $sEnd) {
	$sDate .= " to ".$sEnd;
}
BlogMenu::setTitle("自言自语 from ".$sDate." - Soulogic");

BlogMenu::setSelectedCategory(13);

$oPageNumber = new PageNumber($aTango["total_count"]);
$oPageNumber->slice();
$sPageNumber = $oPageNumber->getHTML(array(
	"href" => "/tweets/%d",
));

$sURL = Link::get("tweets", $oPageNumber->getPageNow());
Page::setCanonical($sURL);

echo $sPageNumber;

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

echo $sPageNumber;
