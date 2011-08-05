<?php
FilterExt::run("GET", array(
	"page" => "int",
));

$oDB = DBz::getInstance("Blog");

// 评论

$sQuery = "SELECT count(*) FROM counter";
$aTango["total_count"] = intval($oDB->getSingle($sQuery));

$oPageNumber = new PageNumber($aTango["total_count"], 100);

$sQuery = "SELECT * "
	."FROM counter "
	."ORDER BY id ".$oPageNumber->getLimit(TRUE);
$aTango["access"] = $oDB->getAll($sQuery);
$aTango["access"] = $oPageNumber->transList($aTango["access"]);

// 评论所涉及的文章

$aArchive = array_map(function($aRow) {
	return $aRow["blog_id"];
}, $aTango["access"]);

$aArchive = array_unique($aArchive);

$sQuery = "SELECT id, title, subtitle FROM blog WHERE id IN (".implode(", ", $aArchive).")";
$aTango["archive"] = $oDB->getAll($sQuery);
