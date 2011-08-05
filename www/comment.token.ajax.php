<?php
FilterExt::run("POST", array(
	"archive_id" => "int",
));

if (empty($_POST["archive_id"])) {
	Page::error("Attack");
}

$iTime = time();

$aTango["time"] = $_SERVER["REQUEST_TIME"];
$aTango["token"] = sha1(
	$_POST["archive_id"]
	.$_SERVER["HTTP_REFERER"]
	.$_SERVER["REQUEST_TIME"]
	.$_SERVER["HTTP_USER_AGENT"]
	.COMMENT_TOKEN_SALT
);
