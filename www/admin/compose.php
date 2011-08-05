<?php
FilterExt::run("GET", array(
	"archive_id" => "int",
));

$aTango["archive"] = array(
	"id" => 0,
	"title" => "",
	"subtitle" => "",
	"author" => "",
	"preview" => "",
	"content" => "",
	"provenance_text" => "",
	"provenance_url" => "",
	"original_tag" => TRUE,
	"delete_tag" => FALSE,
	"folder" => 0,
);

if (empty($_GET["archive_id"])) {
	exit;
}

$oDB = DBz::getInstance("Blog");

$sQuery = "SELECT ".implode(", ", array_keys($aTango["archive"]))." "
	."FROM blog "
	."WHERE id = ".$_GET["archive_id"];

if (!$aTango["archive"] = $oDB->getRow($sQuery)) {
	Page::error("无法获取指定文章");
}

if ($aTango["archive"]["content"]) {
	$aTango["archive"]["preview"] = $aTango["archive"]["content"];
}
unset($aTango["archive"]["content"]);

$aTango["archive"]["original_tag"] = ($aTango["archive"]["original_tag"] == "Y");
$aTango["archive"]["delete_tag"]   = ($aTango["archive"]["delete_tag"]   == "Y");

