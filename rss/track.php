<?php
Page::noSend();

header('Content-Type: image/png');
header('X-Accel-Redirect: /icon.png');

fastcgi_finish_request();

$lMatch = [];
if (preg_match('#^/track/(\d+)#', $_SERVER['REQUEST_URI'], $lMatch)) {
	$iArchive = $lMatch[1];
	if (Cache::get("archive", $iArchive)) {
		$sQuery = 'INSERT INTO icon_track '
			.'SET blog = '.$iArchive.', '
			.'date_access = CURDATE(), '
			.'time_access = CURTIME(), '
			.'referer = "'.addslashes($_SERVER['HTTP_REFERER']).'", '
			.'referer_site = "'.addslashes(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)).'", '
			.'ip = "'.addslashes($_SERVER["REMOTE_ADDR"]).'"';
		$oDB = DBz::getInstance("Blog");
		$oDB->exec($sQuery);
	}
}
