<?php
function utf8_ucs2be($sChar) {
	$sChar = iconv("UTF-8", "UCS-2BE//IGNORE", mb_substr($sChar, 0, 1));
	return bin2hex($sChar);
}

function ord_utf8($sChar) {
	return hexdec(utf8_ucs2be($sChar));
}

