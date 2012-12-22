<?php
/*
	author: Zheng Kai <zhengkai@gmail.com>

	query 不限大小写，但字段名不得用关键字 and or 及纯数字
 */

define("DEBUG", FALSE);

if (PHP_SAPI == "cli") {
	$bCli = TRUE;
} else {
	echo "<pre>";
	$bCli = FALSE;
}

require_once(__DIR__."/tree_store.class.php");
require_once(__DIR__."/set_check.class.php");
require_once(__DIR__."/misc.inc.php");

if (empty($_POST["query"])) {
	if (!$bCli) {
		echo "no query submit, load default data file\n\n\n";
	}
	$aQueryList = file(__DIR__."/data.txt", FILE_IGNORE_NEW_LINES);
} else {
	if (strlen($_POST["query"]) > 3000000) {
		echo "query too long";
		exit;
	}
	file_put_contents("test/".date("Ymd_His").".txt", $_POST["query"]);
	$aQueryList = explode("\n", str_replace(array("\r\n", "\r"), "\n", $_POST["query"]));
	if (count($aQueryList) < 2) {
		?>格式错误，每行一个条件语句，至少两行才能比较。多组条件用空行分隔<?PHP
		exit;
	}
}

$sQuery = current($aQueryList);

register_shutdown_function(function() {
	echo "\n\n\n\nMemory: ".number_format(memory_get_usage())." / Max ".number_format(memory_get_peak_usage())."\n";
	echo "\nAuthor: Zheng Kai (zhengkai@gmail.com) soulogic.com";

});

$iCount = 0;
$sQueryPrev = "";
foreach ($aQueryList as $iLine => $sQuery) {

	if (empty($sQuery)) {
		$iCount = 0;
		continue;
	}
	$iCount++;

	if ($iCount == 1) {
		$sQueryPrev = $sQuery;
	}
	if ($iCount != 2) {
		continue;
	}

	echo "\n\n";
	echo sprintf("%5d", $iLine).": Query A \"".$sQueryPrev."\"\n\n";
	echo sprintf("%5d", $iLine + 1).": Query B \"".$sQuery."\"\n\n";
	echo "\n";

	$f = microtime(TRUE);
	$oA = new TreeStore($sQueryPrev);
	$aA = $oA->get();
	$oB = new TreeStore($sQuery);
	$aB = $oB->get();
	$fTransfer = microtime(TRUE) - $f;

	$f = microtime(TRUE);
	$bSubset = SetCheck::isSubset($aA, $aB);
	$fCompare = microtime(TRUE) - $f;

	echo "transfer time = ".sprintf("%0.06f", $fTransfer)."\n";
	echo " compare time = ".sprintf("%0.06f", $fCompare)."\n";
	echo "\n";
	if ($bSubset) {
		echo "[  OK  ] Query B is the subset of the Query A";
	} else {
		echo "[ Fail ] Not match because ".SetCheck::$sMessage;
	}
	echo "\n\n";

	$iCount = 0;
}
