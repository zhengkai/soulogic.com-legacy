<?php
FilterExt::run("GET", array(
	"archive_id" => "int",
));

Page::noEscapeHTML();

if (!$aTango["archive"] = Cache::get("archive", $_GET["archive_id"])) {
	Page::error("查无此文章");
}

if ($aTango["archive"]["delete_tag"] !== "N") {
	Page::error("该文章已被删除");
}

// 来访计数

$aRefDomain = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST) ?: "";
$aIgnoreDomain = array(
	"",
	"soulogic.com",
	"soulogic.blog",
);

if (!in_array($aRefDomain, $aIgnoreDomain)
	&& !LOCAL_ACCESS
	&& MCz::add("soulogic.access.".$_SERVER["REMOTE_ADDR"], 1, 3600 * 22)) {

	$oDB = DBz::getInstance("Blog");

	$sQuery = "INSERT DELAYED INTO counter "
		."SET blog_id = ".$_GET["archive_id"].", "
		."ip = \"".addslashes($_SERVER["REMOTE_ADDR"])."\", "
		."referer = \"".addslashes($_SERVER["HTTP_REFERER"])."\", "
		."referer_host = \"\", "
		."date_a = NOW()";
	$oDB->exec($sQuery);
}

// 上一篇 | 下一篇

$oDB = DBz::getInstance("Blog");

$sQuery = "SELECT id, title FROM blog "
	."WHERE id > ".$_GET["archive_id"]." "
	."AND delete_tag = \"N\" "
	."ORDER BY id ASC "
	."LIMIT 1";
$aTango["next"] = $oDB->getRow($sQuery);

$sQuery = "SELECT id, title FROM blog "
	."WHERE id < ".$_GET["archive_id"]." "
	."AND delete_tag = \"N\" "
	."ORDER BY id DESC "
	."LIMIT 1";
$aTango["prev"] = $oDB->getRow($sQuery);

// 相关文章

$iKeep = 5;
$iTotalKeep = $iKeep * 2;

$sQuery = "SELECT id, title FROM blog "
	."WHERE folder = ".$aTango["archive"]["folder"]." "
	."AND id > ".$_GET["archive_id"]." "
	."AND delete_tag = \"N\" "
	."ORDER BY id ASC "
	."LIMIT ".$iTotalKeep;
$aNext = array_reverse($oDB->getAll($sQuery), TRUE);
$iNext = count($aNext);

$sQuery = "SELECT id, title FROM blog "
	."WHERE folder = ".$aTango["archive"]["folder"]." "
	."AND id < ".$_GET["archive_id"]." "
	."AND delete_tag = \"N\" "
	."ORDER BY id DESC "
	."LIMIT ".$iTotalKeep;
$aPrev = $oDB->getAll($sQuery);
$iPrev = count($aPrev);

if ($iPrev < $iKeep) {
	$iKeepNext = $iTotalKeep - $iPrev;
} else {
	$iKeepNext = min($iNext, $iKeep);
}
$aNextShow = array_slice($aNext, count($aNext) - $iKeepNext, $iKeepNext, TRUE);

if ($iNext < $iKeep) {
	$iKeepPrev = $iTotalKeep - $iNext;
} else {
	$iKeepPrev = $iKeep;
}
$aPrevShow = array_slice($aPrev, 0, $iKeepPrev, TRUE);

$aThis = array(
	$aTango["archive"]["id"] => $aTango["archive"]["title"],
);

$aTango["comment"] = Cache::get("comment", $_GET["archive_id"]) !== array();
$aTango["related"] = $aNextShow + $aThis + $aPrevShow;
