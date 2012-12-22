<?php
FilterExt::run("POST", array(
	"archive_id" => "int",
	"folder" => "int",
	"title" => "string",
	"subtitle" => "string",
	"author" => "string",
	"provenance_text" => "string",
	"provenance_url" => "string",
	"content" => "longString",
	"original_tag" => "boolean",
	"delete_tag" => "boolean",
));

$aCheck = array(
	"title"   => "标题",
	"folder"  => "分类",
	"content" => "内容",
);
foreach ($aCheck as $sRowname => $sName) {
	if (empty($_POST[$sRowname])) {
		Page::error("未填写 ".$sName);
	}
}

$aRowName = array(
	"title",
	"subtitle",
	"author",
	"folder",
	"provenance_text",
	"provenance_url",
	"content",
);

$sQuery = "SET ";
foreach ($aRowName as $sRowName) {
	$sQuery .= ($sRowName == "content" ? "preview" : $sRowName)
		." = "
		.($sRowName == "folder" ? $_POST[$sRowName] : "\"".addslashes($_POST[$sRowName])."\"").", ";
}
$sQuery .= "original_tag = \"".($_POST["original_tag"] ? "Y" : "N")."\", ";
$sQuery .= "delete_tag = \"".($_POST["delete_tag"] ? "Y" : "N")."\", ";

$sQuery .= "content = \"\", ";
$sQuery .= "date_m = NOW()";

$oDB = DBz::getInstance("Blog");

if ($_POST["archive_id"]) {

	$sQuery = "UPDATE blog ".$sQuery." WHERE id = ".$_POST["archive_id"];
	$aTango["archive_id"] = $_POST["archive_id"];
	$aTango["success"] = $oDB->exec($sQuery);
	$aTango["edit"] = TRUE;

} else {

	$sQuery = "INSERT INTO blog ".$sQuery.", date_p = NOW()";
	$aTango["archive_id"] = $oDB->getInsertID($sQuery);
	$aTango["success"] = $aTango["archive_id"];
	$aTango["edit"] = FALSE;
}

if ($aTango["success"]) {
	Cache::clean("archive", $aTango["archive_id"]);
	$sRSS = __DIR__."/../../rss/feed.rss";
	if (file_exists($sRSS)) {
		unlink($sRSS);
	}
}
