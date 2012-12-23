<?php
// 获取 tango/res 目录的版本号
function getResVersion() {
	return 2;
	/*
	$sSVN = file_get_contents(dirname(__DIR__)."/res/.svn/entries", FALSE, NULL, 0, 1500);
	$aTmp = explode("\n", $sSVN, 5);
	return trim($aTmp["3"]);
	 */
}

function utf8sanitize($sContent) {
	if (!is_string($sContent)) {
		return $sContent;
	}
	return iconv("UTF-8", "UTF-8//IGNORE", $sContent);
}

function md5WithSalt($sString) {
	return md5("xN1ksnja".$sString);
}

function dumpStr() {
	$sOut = "";
	foreach (func_get_args() as $mVar) {
		if (is_array($mVar)) {
			$sDump = print_r($mVar, TRUE);
		} else {
			$sDump = var_export($mVar, TRUE);
		}
		if (PHP_SAPI != "cli") {
			$sDump = "<pre style=\"font-size: 14px; overflow: auto; max-height: 600px; font-family: &#034;DejaVu Sans Mono&#034;, &#034;微软雅黑&#034; line-height: 22px; margin: 4px auto; max-width: 940px; min-width: 300px; border: 1px solid teal; background-color: #efe; color: teal; padding: 9px;\">\n"
				.htmlspecialchars(trim($sDump))."\n</pre>";
		}
		$sOut .= $sDump;
	}
	return $sOut;
}

function htmlFilter($sText) {
	return htmlentities($sText, ENT_QUOTES, "UTF-8");
}

function dump() {
	echo call_user_func_array("dumpStr", func_get_args());
}

function setCookieZ($sKey, $sValue) {
	$_COOKIE[$sKey] = $sValue;
	setcookie($sKey, $sValue, INT_MAX, "/");
	return TRUE;
}

function dec2hex($iNumber) {
	return sprintf("%08s", dechex($iNumber));
}

function dec2bin($iNumber) {
	return sprintf("%04s", pack("N", $iNumber));
}

function bin2dec($sNumber) {
	return hexdec(bin2hex($sNumber));
}

function array2bin($aNumber) {
	return implode("", array_map("dec2bin", $aNumber));
}

function bin2array($sNumber) {
	return array_map("bin2dec", str_split($sNumber, 4));
}

function debugLog($sContent = "", $sFileName = "/tmp/php_debug.txt") {
    $sMode = "a";
    if (file_exists($sFileName)) {
        clearstatcache();
        $iFilesize = filesize($sFileName);
        if (($iFilesize < 1)||($iFilesize > 900000000)) { // 900M
            $sMode = "w";
        }
    }
    if ($hFile = fopen($sFileName, $sMode."b")) {
        fwrite($hFile, $sContent);
        fclose($hFile);
    }
}

function date2822($iTime = NULL) {
	if ($iTime === NULL) {
		$iTime = $_SERVER["REQUEST_TIME"];
	}
	return substr(date("r", $iTime), 0, -5)."GMT";
}

function headExpires($iTime = 86400) {
	if ($iTime < 1) {
		return FALSE;
	}
	if (PHP_SAPI == "cli") {
		return FALSE;
	}
	header("Date: ".date2822());
	header("Cache-Control: max-age=".$iTime);
	header("Expires: ".date2822($_SERVER["REQUEST_TIME"] + $iTime));
}

function getURL($sURL, $sReferer = "") {
	$hCURL = curl_init($sURL);
	curl_setopt($hCURL, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($hCURL, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)");
	curl_setopt($hCURL, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($hCURL, CURLOPT_ENCODING, "gzip,deflate");
	$sContent = curl_exec($hCURL);
	curl_close($hCURL);
	return $sContent;
}

/*
function wstrtotime($mDatetime) {
	if (is_integer($mDatetime)||(is_string($mDatetime)&&preg_match("/^[0-9]{1,10}$/", $mDatetime)&&($mDatetime > 10)&&($mDatetime < INT_MAX))) {
		return $mDatetime;
	}
	return strtotime($mDatetime);
}
 */
