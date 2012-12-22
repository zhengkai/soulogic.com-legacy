<?php
FilterExt::run("POST", array(
	"content" => "longString",
));

$sContent = $_POST["content"];

if (empty($sContent)) {
	Page::jump("tweet.php");
}

$iType = 0;
if (substr($sContent, 0, 1) == "\"") {
	$iType = 1;
	$sContent = substr($sContent, 1);
}

$sContent = preg_replace("/\n{2,}/", "\n\n", $sContent);

$iLength = mb_strlen($sContent);

$sExt = FALSE;
if ($iLength > 50) {
	$sExt = mb_substr($sContent, 50);
	$sContent = mb_substr($sContent, 0, 50);
}

$oDB = DBz::getInstance("PDA");

$sQuery = "INSERT INTO msg "
	."SET content = \"".addslashes($sContent)."\", "
	."length = ".$iLength.", "
	."itype = ".$iType.", "
	."datetime_c = NOW()";
$iPlan = $oDB->getInsertID($sQuery);

if (!$iPlan) {
	Page::error("插入失败");
}

if ($sExt) {
	$sQuery = "INSERT INTO msg_content "
		."SET id = ".$iPlan.", "
		."content_ext = \"".addslashes($sExt)."\"";
	$oDB->exec($sQuery);
}

Page::jump("tweet.php");
