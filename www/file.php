<?php
Page::noSend();

FilterExt::run("GET", array(
	"file_id" => "int",
));

if (empty($_GET["file_id"])) {
	header("HTTP/1.1 404 Not Found", TRUE, 404);
	exit;
}

if (!$aFile = Cache::get("file", $_GET["file_id"])) {
	header("HTTP/1.1 404 Not Found", TRUE, 404);
	exit;
}

if (!LOCAL_ACCESS
	&& MCz::add("soulogic.file.access.".$_SERVER["REMOTE_ADDR"], 1, 3600 * 22)) {

	$oDB = DBz::getInstance("Blog");

	$sQuery = "UPDATE LOW_PRIORITY files SET counter = counter + 1 WHERE id = ".$_GET["file_id"];
	$oDB->exec($sQuery);
}

header("Cache-Control: max-age=315360000, must-revalidate");
header("Content-Disposition: inline; Filename=\"".$aFile["name"]."\"");
header("Content-type: application/octet-stream");
echo $aFile["content"];
