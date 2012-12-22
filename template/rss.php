<?php
//header("content-type: text/xml; charset=UTF-8");
header("content-type: application/rss+xml; charset=UTF-8");

$sFile = dirname(__DIR__)."/rss/feed.rss";
if (is_file($sFile)) {
	readfile($sFile);
	exit;
}

function xml_out($string) {
	$string = str_replace("&", "&#38;", $string);
	$string = str_replace("<", "&#60;", $string);
	$string = str_replace(">", "&#62;", $string);
	$string = str_replace("]", "&#93;", $string);
	return $string;
}

function xmlNode($sName, $sValue, $bXmlOutTag = true, $bCdata = false) {

	settype($sValue, "string");
	settype($sName, "string");

	$sName = xml_out($sName);
	$sReturn = "";
	if (strlen($sValue) == 0) {
		$sReturn .= "<".$sName." />";
	} else {
		if ($bXmlOutTag) {
			$sValue = xml_out($sValue);
		}
		$sReturn .= "<".$sName.">";
		if ($bCdata || strpos($sValue, "\n") !== FALSE) { // 如果是多行的值则使用 CDATA
			$sReturn .= "<![CDATA[".$sValue."]]>";
		} else {
			$sReturn .= $sValue;
		}
		$sReturn .= "</".$sName.">";
	}
	return $sReturn;
}

$sRSS = "";

$sRSS .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
$sRSS .= "<rss version=\"2.0\">"
	."<channel>\n";

$sRSS .= xmlNode("title",          "Soulogic 灵魂逻辑");
$sRSS .= xmlNode("link",           "http://soulogic.com/");
$sRSS .= xmlNode("description",    "硬盘里埋藏着我的光荣与梦想，我要用键盘把他们挖出来");

$sRSS .= xmlNode("language",       "zh-cn");
$sRSS .= xmlNode("generator",      "Soulogic RSS lib");

$sRSS .= xmlNode("lastBuildDate",  date("r"));

foreach ($aTango["list"] as $iArchive => $aRow) {

	if ($iArchive < 398) {
		continue;
	}

	$sXMLItem = "<item>";
	$sXMLItem .= xmlNode("title",       $aRow["title"].(empty($aRow["subtitle"]) ? "" : "——".$aRow["subtitle"]));
	$sXMLItem .= xmlNode("link",        Link::get("archive", $iArchive));

	$sXMLItem .= xmlNode("author",      "zhengkai@gmail.com (郑凯)");
	$aCategory = BlogMenu::getCategory($aRow["folder"]);
	$sXMLItem .= xmlNode("category",    $aCategory["name"]);
	$sXMLItem .= xmlNode("guid",        ($iArchive <= 407) ? md5("soulogic.com_".$iArchive) : "soulogic.blog.".$iArchive);
	$sXMLItem .= xmlNode("pubDate",     date("r", $aRow["date_p"]));

	$sContent = ($aRow["content"] ?: $aRow["preview"])."\n\n[img]http://rss.soulogic.com/track/".$iArchive."[/img]";

	$sRSS .= $sXMLItem
		.xmlNode("description", old_convert($sContent), false, true)
		."</item>\n";
}

$sRSS .= "</channel>\n</rss>";

echo $sRSS;
file_put_contents($sFile, $sRSS);
