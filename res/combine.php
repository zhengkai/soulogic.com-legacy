<?php
$aConfig = array(
	"remove_comment" => TRUE, // 去掉注释
	"use_compiler" => FALSE, // 使用 yui / google closure compiler 优化
);

require_once("../lib/utility.func.php");

header("Cache-Control: max-age=315360000, must-revalidate");

register_shutdown_function(function() {
	if (!defined("GAME_OVER")) {
//		header("HTTP/1.0 404 Not Found");
	}
});

$aType = array(
	"css" => "text/css; charset=utf-8",
	"js"  => "application/javascript; charset=utf-8",
);

/*

	检查输入参数是否正确

 */

$sPattern = "/^\\/combo\\/(.*)_v(\d+)_([0-9a-f]{8})\\.(css|js)$/";
if (!preg_match($sPattern, $_SERVER["REQUEST_URI"], $aMatch)) {

	// url 格式不符

	exit;
}

list(, $sRes, $iVersion, $sHash, $sType) = $aMatch;

if ($sHash !== hash("crc32", $sRes."O0WPmINmROtIBL2AO3Fu".$iVersion)) {

	// 无效 hash，可能是篡改 url
	exit;
}

if ($sType === "css" && $sRes === "default") {

	$aRes = scandir(__DIR__);
	$aFirst = array(
		"init.css",
		"common.css",
	);
	$aRes = array_filter($aRes, function($sFile) use ($aFirst) {
		if (in_array($sFile, $aFirst)) {
			return FALSE;
		}
		if (pathinfo($sFile, PATHINFO_EXTENSION) !== "css") {
			return FALSE;
		}
		return TRUE;
	});
	$aRes = array_merge($aFirst, $aRes);

} else {

	$aRes = explode(",", $sRes);

	$aRes = array_map(function($sFile) use ($sType) {
		if (!preg_match("/^(([a-z0-9_\\-]+\\.)*[a-z0-9_\\-]+\\/)*([a-z0-9_\\-]+\\.)*[a-z0-9_\\-]+$/", $sFile)) {

			// 文件名要求只包含小写字母、数字、“-_.”三种符号
			// 如不符合规则，有可能是注入，抛弃

			exit;
		}
		return $sFile.".".$sType;
	}, $aRes);
}

header("Content-Type: ".$aType[$sType]);

/*

	判断压缩格式

 */

$sCompress = "plain";
if (isset($_SERVER["HTTP_ACCEPT_ENCODING"])) {

	$aAcceptEncoding = explode(",", $_SERVER["HTTP_ACCEPT_ENCODING"]);
	$aAcceptEncoding = array_map("trim", $aAcceptEncoding);

	if (in_array("deflate", $aAcceptEncoding)) {

		$sCompress = "deflate";
		header("Content-Encoding: ".$sCompress);

	} else if (in_array("gzip", $aAcceptEncoding)) {

		$sCompress = "gzip";
		header("Content-Encoding: ".$sCompress);
	}
}

$sCacheBase = dirname(__DIR__)."/cache/res/".getResVersion()."_".$sHash."_".md5($sRes);

$sFileCompile = $sCacheBase.".compile";
$sCacheFinal = ($sCompress === "plain" ? $sFileCompile : $sCacheBase.".".$sCompress);

/*

	如果已有相应 cache，直接返回

 */

if (file_exists($sCacheFinal)) {
	header("Content-Length: ".filesize($sCacheFinal));
	readfile($sCacheFinal);
	exit;
}

/*

	没 cache，生成

 */

$sContent = "";

foreach ($aRes as $sFile) {
	$sFile = __DIR__."/".$sFile;
	if (!is_file($sFile)) {
		exit;
	}
	$sSub = file_get_contents($sFile);
	if ($sType === "css") {
		$sSub = str_replace("@charset \"utf-8\";", "", $sSub);
	}
	$sContent .= $sSub."\n\n";
}

$sFilePlain = $sCacheBase.".plain";

if ($aConfig["remove_comment"] && $sType == "css") {
	$sContent = str_replace(array("\r\n", "\r"), "\n", $sContent);
	$sContent = preg_replace("/(\\/\\*.*?\\*\\/)/s", "", $sContent);
	$sContent = preg_replace("/\\n{3,}/", "\n", $sContent);
	$sContent = trim($sContent);
}
file_put_contents($sFilePlain, $sContent);

if ($aConfig["use_compiler"]) {
	if ($sType === "js") {
		$sCmd = "/usr/bin/java -jar ".__DIR__."/misc/google.closure.compiler/compiler.jar "
			."--compilation_level=SIMPLE_OPTIMIZATIONS "
			."--js ".$sFilePlain." "
			."--js_output_file ".$sFileCompile;
	} else {
		$sCmd = "/usr/bin/java -jar ".__DIR__."/misc/yuicompressor-2.4.2/build/yuicompressor-2.4.2.jar "
			."--charset utf-8 --type ".$sType." "
			.$sFilePlain." "
			."-o ".$sFileCompile;
	}
	exec($sCmd);
	$sContent = file_get_contents($sFileCompile);
}

if ($sCompress !== "plain") {
	switch ($sCompress) {
		case "deflate":
			$sContent = gzdeflate($sContent);
			file_put_contents($sCacheFinal, $sContent);
			break;
		case "gzip":
			$sContent = gzencode($sContent);
			file_put_contents($sCacheFinal, $sContent);
			break;
		default:
			exit;
			break;
	}
}

header("Content-Length: ".strlen($sContent));

echo $sContent;

define("GAME_OVER", TRUE);
