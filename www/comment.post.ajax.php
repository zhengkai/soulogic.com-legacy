<?php
FilterExt::run("POST", array(
	"archive_id" => "int",
	"name"  => "string",
	"email" => "string",
	"url"   => "string",
	"content" => "longString",
	"time"  => "int",
	"token" => "string",
));

// check token

if ($_POST["time"] < ($_SERVER["REQUEST_TIME"] - 100)) {
	Page::error("old");
}

$sToken = sha1(
	$_POST["archive_id"]
	.$_SERVER["HTTP_REFERER"]
	.$_POST["time"]
	.$_SERVER["HTTP_USER_AGENT"]
	.COMMENT_TOKEN_SALT
);

if ($_POST["token"] !== $sToken) {
	Page::error("token");
}

// check other input

if (!$_POST["archive_id"]) {
	Page::error("archive_id");
}

if (!strlen($_POST["content"])) {
	Page::error("content");
}

$aTrans = Array(
	'０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
	'５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
	'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
	'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
	'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
	'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
	'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
	'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
	'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
	'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
	'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
	'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
	'ｙ' => 'y', 'ｚ' => 'z',
	'－' => '-', '＠' => '@', '．' => '.', '／' => '/',
);

$aTransSearch  = array_keys($aTrans);
$aTransReplace = array_values($aTrans);

$_POST["url"]   = str_replace($aTransSearch, $aTransReplace, $_POST["url"]);
$_POST["email"] = str_replace($aTransSearch, $aTransReplace, $_POST["email"]);

$_POST["url"] = str_replace(array("http://", "https://"), "", $_POST["url"]);

if (!strpos($_POST["email"], "@") || !strpos($_POST["email"], ".")) {
	$_POST["email"] = "";
}

if (!strpos($_POST["url"], ".")) {
	$_POST["url"] = "";
}

// check archive_id valid

$oDB = DBz::getInstance("Blog");

$sQuery = "SELECT id FROM blog "
	."WHERE id = ".$_POST["archive_id"]." "
	."AND delete_tag = \"N\"";
if (!$oDB->getSingle($sQuery)) {
	Page::error("查无此文章");
}

// insert

$sQuery = "INSERT INTO guestbook "
	."SET folder = ".$_POST["archive_id"].", "
	."guest = \"".addslashes($_POST["name"])."\", "
	."email = \"".addslashes($_POST["email"])."\", "
	."url = \"".addslashes($_POST["url"])."\", "
	."content = \"".addslashes($_POST["content"])."\", "
	."ip = \"".addslashes($_SERVER["REMOTE_ADDR"])."\", "
	."date_a = NOW()";
if (!$oDB->getInsertID($sQuery)) {
	Page::error("system");
	exit;
}

Cache::clean("comment", $_POST["archive_id"]);

$aTango["success"] = TRUE;
