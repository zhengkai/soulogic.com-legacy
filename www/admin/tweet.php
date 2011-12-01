<?php
FilterExt::run("GET", array(
	"page" => "int",
));

$oDB = DBz::getInstance("PDA");

// 评论

$sQuery = "SELECT * FROM msg ORDER BY id DESC LIMIT 10";

$aTango["plan"] = $oDB->getAll($sQuery);

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

