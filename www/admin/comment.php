<?php
FilterExt::run("GET", array(
	"page" => "int",
));

$oDB = DBz::getInstance("Blog");

// 评论

$sQuery = "SELECT count(*) FROM guestbook";
$aTango["total_count"] = intval($oDB->getSingle($sQuery));

$oPageNumber = new PageNumber($aTango["total_count"]);

$sQuery = "SELECT * "
	."FROM guestbook "
	."ORDER BY id ".$oPageNumber->getLimit(TRUE);
$aTango["comment"] = $oDB->getAll($sQuery);
$aTango["comment"] = $oPageNumber->transList($aTango["comment"]);

$aTango["comment"] = array_map(function($aRow) {
	$aRow["avatar"] = md5(strtolower(trim($aRow["email"])));
	return $aRow;
}, $aTango["comment"]);

// 评论所涉及的文章

$aArchive = array_map(function($aRow) {
	return $aRow["folder"];
}, $aTango["comment"]);

$aArchive = array_unique($aArchive);

$sQuery = "SELECT id, title, subtitle FROM blog WHERE id IN (".implode(", ", $aArchive).")";
$aTango["archive"] = $oDB->getAll($sQuery);
