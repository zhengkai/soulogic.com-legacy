<?php
$aTango["menu"] = 400;

$oPageNumber = new PageNumber($aTango["total_count"], 100);
$oPageNumber->slice();
$sPageNumber = $oPageNumber->getHTML();

echo $sPageNumber;

?>
<table class="list accessList">
	<tr>
		<th>时间</th>
		<th>文章</th>
		<th>引用页</th>
	</tr>
<?php
$sDatePrev = "";
foreach ($aTango["access"] as $aRow) {

	if (empty($aRow["blog_id"])) {
		continue;
	}

	$sDate = date("Y-m-d", $aRow["date_a"]);

	$sDateShow = date(($sDatePrev === $sDate ? "" : "Y-m-d")." H:i:s", $aRow["date_a"]);
	$sDatePrev = $sDate;

	?>
	<tr>
		<td><?php echo $sDateShow;?></td>
		<td><?php echo "<a href=\"/archives/".$aRow["blog_id"]."\">".$aTango["archive"][$aRow["blog_id"]]["title"];?></a></td>
		<td><?php echo "<a href=\"".$aRow["referer"]."\">".$aRow["referer"];?></a></td>
	</tr>
	<?php
}
?>
</table>
<?php

echo $sPageNumber;
