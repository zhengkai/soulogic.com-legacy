<?php
Page::noEscapeHTML();
Page::noTemplate();

$oDB = DBz::getInstance("Blog");

$sQuery = "SELECT * "
	."FROM blog "
	."WHERE delete_tag = \"N\" AND rss_tag = \"Y\""
	."ORDER BY id "
	."DESC LIMIT 30";
$aTango["list"] = $oDB->getAll($sQuery);

