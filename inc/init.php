<?php
header("Server: Soulogic.com");

settype($_SERVER["HTTP_REFERER"], "string");
settype($_SERVER["REQUEST_URI"], "string");
settype($_SERVER["REMOTE_ADDR"], "string");
settype($_SERVER["SERVER_ADDR"], "string");
settype($_SERVER["TANGO_DEV"], "string");

define("LOCAL_ACCESS", $_SERVER["SERVER_ADDR"] === "127.0.0.1" || $_SERVER["SERVER_ADDR"] === "::ffff:127.0.0.1");
define("TANGO_DEV", ($_SERVER["TANGO_DEV"] === "freya") && LOCAL_ACCESS);

$sPathLib = dirname(__DIR__)."/lib/";

require_once($sPathLib."utility.func.php");
require_once($sPathLib."db/dbz.php");
require_once($sPathLib."pagenumber.php");

require_once($sPathLib."mcz.php");
require_once($sPathLib."cache.php");

require_once(__DIR__."/old_convert.php");

require_once(__DIR__."/blogmenu.php");

require_once(__DIR__."/filter.ext.php");
require_once(__DIR__."/page.php");
require_once(__DIR__."/link.php");


require_once(__DIR__."/config.php");

Page::checkScriptFile();

$aTango =& Page::$aData;

Cache::set("archive", array(
	"db" => "Blog",
	"query" => "SELECT * FROM blog WHERE id = %d",
	"key" => "soulogic.blog.archive.%d",
	"pdo_method" => "getRow",
));

Cache::set("category", array(
	"db" => "Blog",
	"query" => "SELECT id, title, subtitle, original_tag, date_m, date_p "
		."FROM blog "
		."WHERE folder = %d "
		."AND delete_tag = \"N\" "
		."ORDER BY id DESC",
	"key" => "soulogic.blog.category.%d",
));

Cache::set("comment", array(
	"db" => "Blog",
	"query" => "SELECT id, guest, url, email, content, date_a, del_tag "
		."FROM guestbook "
		."WHERE folder = %d",
	"key" => "soulogic.blog.comment.%d",
));

Cache::set("file", array(
	"db" => "Blog",
	"query" => "SELECT * FROM files WHERE id = %d",
	"key" => "soulogic.blog.file.v2.%d",
	"pdo_method" => "getRow",
));

Cache::set("tweets", array(
	"db" => "Blog",
	"query" => "SELECT * FROM blog WHERE id = %d %d",
	"key" => "soulogic.blog.archive.%d",
));
