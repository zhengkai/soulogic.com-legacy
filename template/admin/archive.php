<?php
$aTango["menu"] = 101;

$oPageNumber = new PageNumber($aTango["total_count"]);
$oPageNumber->slice();
$sPageNumber = $oPageNumber->getHTML();

echo $sPageNumber;
?>
<table class="archiveList list">
	<tr>
		<th>编号</th>
		<th>标题</th>
		<th>分类</th>
		<th></th>
		<th>上传日期</th>
	</tr>
<?php
foreach ($aTango["archive"] as $iArchive => $aRow) {
	?>
	<tr>
		<td><?php echo $iArchive; ?></td>
		<td><a href="/admin/compose.php?archive_id=<?php echo $iArchive; ?>"><?php echo $aRow["title"]; ?></a></td>
		<td><?php echo $aRow["folder"]; ?></td>
		<td><a href="/archives/<?php echo $iArchive; ?>">预览</a></td>
		<td><?php echo date("Y-m-d H:i:s", $aRow["date_p"]); ?></td>
	</tr>
	<?php
}
?>
</table>
<?php
echo $sPageNumber;

