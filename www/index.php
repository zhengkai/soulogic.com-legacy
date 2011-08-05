<?php
FilterExt::run("GET", array(
	"category_id" => "int",
));

$oDB = DBz::getInstance("Blog");

$sQuery = "SELECT * "
	."FROM blog "
	."WHERE delete_tag = \"N\" "
	."ORDER BY id DESC "
	."LIMIT 16";

$aTango["list"] = $oDB->getAll($sQuery);

$aTango["titleNoLink"] = empty($_GET["category_id"]);
