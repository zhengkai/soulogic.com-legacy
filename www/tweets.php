<?php
if (!isset($_GET["page"])) {
	$_GET["page"] = 2147483647; // 最大页码
}

FilterExt::run("GET", array(
	"page" => "int",
));

$oDB = DBz::getInstance("PDA");

$sQuery = "SELECT count(*) FROM msg";
$aTango["total_count"] = intval($oDB->getSingle($sQuery));

$oPageNumber = new PageNumber($aTango["total_count"]);

$sQuery = "SELECT * "
	."FROM msg "
	."ORDER BY id ".$oPageNumber->getLimit(TRUE, TRUE);
$aTango["plan"] = $oDB->getAll($sQuery);
$aTango["plan"] = $oPageNumber->transList($aTango["plan"]);

$aLong = array_keys(array_filter($aTango["plan"], function($aRow) {
	return $aRow["length"] > 50;
}));

if (!empty($aLong)) {

	$sQuery = "SELECT * FROM msg_content WHERE id IN (".implode(", ", $aLong).")";
	$aExt = $oDB->getAll($sQuery);

	foreach ($aExt as $iID => $sExt) {
		$aTango["plan"][$iID]["content"] .= $sExt;
	}
}
