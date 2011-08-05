<?php
FilterExt::run("GET", array(
	"page" => "int",
));

$oDB = DBz::getInstance("Blog");

// 评论

$sQuery = "SELECT count(*) FROM blog";
$aTango["total_count"] = intval($oDB->getSingle($sQuery));

$oPageNumber = new PageNumber($aTango["total_count"]);

$sQuery = "SELECT id, title, subtitle, author, comment, provenance_text, provenance_url, original_tag, delete_tag, visits, folder, date_o, date_m, date_p "
	."FROM blog "
	."ORDER BY id ".$oPageNumber->getLimit(TRUE);
$aTango["archive"] = $oDB->getAll($sQuery);
$aTango["archive"] = $oPageNumber->transList($aTango["archive"]);
