<?php
$aTango["menu"] = 300;

$oPageNumber = new PageNumber($aTango["total_count"]);
$oPageNumber->slice();
$sPageNumber = $oPageNumber->getHTML();

echo $sPageNumber;
?>

<form class="upload" method="POST" action="upload.do.php" enctype="multipart/form-data">
<input type="file" name="file" /><button type="submit">上传</button>
</form>

<table class="uploadList list">
	<tr>
		<th>编号</th>
		<th>文件名</th>
		<th>下载数</th>
		<th>文件大小</th>
		<th>上传日期</th>
	</tr>
<?php
foreach ($aTango["upload"] as $iID => $aFile) {
	?>
	<tr>
		<td><?php echo $iID; ?></td>
		<td><a href="/upload/<?php echo $iID; ?>"><?php echo $aFile["name"]; ?></a></td>
		<td><?php echo $aFile["counter"]; ?></td>
		<td><?php echo $aFile["filesize"]; ?></td>
		<td><?php echo date("Y-m-d H:i:s", $aFile["date_c"]); ?></td>
	</tr>
	<?php
}
?>
</table>
<?php
echo $sPageNumber;


