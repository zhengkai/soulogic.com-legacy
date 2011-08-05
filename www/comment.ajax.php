<?php
FilterExt::run("POST", array(
	"archive_id" => "int",
));

if (empty($_POST["archive_id"])) {
	Page::error("查无id");
}

$aComment = Cache::get("comment", $_POST["archive_id"]);

ksort($aComment, SORT_NUMERIC);
$aComment = array_values($aComment);

$aComment = array_map(function($aRow) {

	if ($aRow["del_tag"] !== "N") {
		return FALSE;
	}
	unset($aRow["del_tag"]);

	$aRow["email"] = md5(strtolower(trim($aRow["email"])));

	$aRow["date"] = date("F j, Y h:i A", $aRow["date_a"]);
	unset($aRow["date_a"]);

	if (empty($aRow["guest"]) || $aRow["guest"] === "Anonym") {
		$aRow["guest"] = FALSE;
	}

	return $aRow;

}, $aComment);

$aTango["comment"] = $aComment;
