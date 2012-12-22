<?php

// MCz::flush();

FilterExt::run("GET", array(
	"category_id" => "int",
));

if (!BlogMenu::getCategory($_GET["category_id"])) {
	Page::error("无分类");
}

$aTango["list"] = Cache::get("category", $_GET["category_id"]) ?: array();
