<?php
require_once(dirname(__DIR__)."/inc/init.php");
Page::noSend();

$aStatus = MCz::getStats();
$aStatus = current($aStatus);
foreach ($aStatus as $sKey => $sValue) {
	echo $sKey.": ".$sValue."\n";
}
