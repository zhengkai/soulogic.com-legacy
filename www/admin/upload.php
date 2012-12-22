<?php
FilterExt::run("GET", array(
	"page" => "int",
));

$oDB = DBz::getInstance("Blog");

$sQuery = "SELECT count(*) FROM files";
$aTango["total_count"] = intval($oDB->getSingle($sQuery));

$oPageNumber = new PageNumber($aTango["total_count"]);

$sQuery = "SELECT id, name, header, date_c, counter, filesize, delete_tag "
	."FROM files "
	."ORDER BY id ".$oPageNumber->getLimit(TRUE);
$aTango["upload"] = $oDB->getAll($sQuery);
$aTango["upload"] = $oPageNumber->transList($aTango["upload"]);
