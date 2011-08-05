<?php
/*

	$aTango["archive_id"] = $_POST["archive_id"];
	$aTango["edit"] = TRUE;
	$aTango["success"] = $oDB->exec($sQuery);
	*/
?>
<p>文章<?php
echo $aTango["edit"] ? "编辑" : "发布";
echo $aTango["success"] ? "成功" : "失败";
?></p>

<p><?php
echo $aTango["archive_id"];
?></p>

<?php
if ($aTango["success"]) {

	?><p><a href="/archives/<?php
	echo $aTango["archive_id"];
	?>">浏览文章</a></p><?php
}
?>
