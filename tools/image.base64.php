<?php
/*
	本来是想把图片转成 base64 放到 css 内
	可目前都是些 32 位无损 png，体积比较大，还是用原始的方式保持异步加载比较好
 */

$sDir = dirname(__DIR__)."/res/img/";
$sCSSDir = dirname(__DIR__)."/res/";
$aDir = scandir($sDir);

$aCSS = array();
$aImage = array();

foreach (scandir($sCSSDir) as $sFile) {
	$aFilename = pathinfo($sFile) + array("extension" => FALSE);
	$sFile = $aFilename["basename"];
	if ($aFilename["extension"] === "css") {
		$aCSS[] = $sFile;
	}
}

foreach (scandir($sDir) as $sFile) {
	if (substr($sFile, 0, 1) === ".") {
		continue;
	}
	$aFilename = pathinfo($sFile) + array("extension" => FALSE);
	$sFile = $aFilename["basename"];

	if ($aFilename["extension"] === "png") {
		if (substr($aFilename["filename"], -4) === ".org") {
			continue;
		}
	} else if ($aFilename["extension"] === "gif") {
	} else {
		continue;
	}

	$aImage[] = $sFile;
	// base64_encode(file_get_contents($sDir.$aFilename["basename"]))
}

$aSearch = array_map(function($sFile) {
	return "background-image: url(\"/img/".$sFile."\");";
}, $aImage);

$aReplace = array_map(function($sFile) use ($sDir) {
	return "background-image: url(data:image/png;base64,"
		.base64_encode(file_get_contents($sDir.$sFile))
		."); /* ".$sFile." */";
}, $aImage);

foreach ($aCSS as $sCSS) {
	echo "\n".$sCSS." ";
	$sCSSContent = file_get_contents($sCSSDir.$sCSS);
	$iCount = 0;
	$sCSSContent = str_replace($aSearch, $aReplace, $sCSSContent, $iCount);
	echo $iCount < 1 ? "[Fail]" : "[ OK ]";
	file_put_contents($sCSSDir."base64/".$sCSS, $sCSSContent);
}
