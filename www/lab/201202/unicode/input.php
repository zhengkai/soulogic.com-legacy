<?php
exit;
if ($_POST && array_key_exists("char", $_POST) && is_string($_POST["char"])) {
	$_IN["char"] = iconv("UTF-8", "UTF-8//IGNORE", $_POST["char"]);
} else {
	$_IN["char"] = "char,";
}

//$lChar = str_split($_IN["char"]);
$lChar = preg_split('//u', $_IN["char"], -1, PREG_SPLIT_NO_EMPTY);

$aTango["char"] = array_unique($lChar);

$aTango["input"] = $_IN["char"];

/*
$lCharRange = range(1, 65535);
$lChar = array();

foreach ($lCharRange as $iChar) {
	$lChar[$iChar] = iconv("UCS-2", "UTF-8//IGNORE", pack("s", $iChar));
}
 */
