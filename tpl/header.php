<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8" />
<title><?php
echo BlogMenu::getTitle();
?></title><?php
echo PageHeader::get();

if (PageHeader::getWebfont()) {
	?><link rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Sans+Mono" type="text/css" /><?php
}
?>
<link rel="alternate" href="http://soulogic.com/rss" type="application/rss+xml" title="RSS" />
<?php
if ($sCanonical = Page::getCanonical()) {
	?><link rel="canonical" href="<?php echo $sCanonical; ?>" /><?php
}
if (Page::isFollowOnly()) {
	?><meta name="robots" content="noindex,follow" /><?php
	echo "\n";
}
?>
<link rel="me" type="text/html" href="http://www.google.com/profiles/zhengkai" />
<script type="text/javascript">
var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-98185-1']); _gaq.push(['_trackPageview']); _gaq.push(['_trackPageLoadTime']); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = 'https://ssl.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();
</script>
</head>

<body<?php
if ($sClass = Page::getBodyClass()) {
	echo " class=\"".$sClass."\"";
}
?>>

<div class="topBar highlight">
	<div><div>&#160;</div></div><div class="bg">&#160;</div>
</div>

<header>

<div class="siteTitle">
<?php
	if (Page::inAdmin()) {
		?><p style="float: right; width: 500px; padding: 20px 0; font-size: 24px; color: #00ff00; font-family: '微软雅黑';">管理后台</p><?php
	}

	if (!empty($aTango["titleNoLink"])) {
		?><h1>Soulogic 灵魂逻辑</h1><?PHP
	} else {
		?><a href="/">Soulogic 灵魂逻辑</a><?PHP
	}
	?>
</div>
<nav>
	<div><?PHP

if (Page::inAdmin()) {

	$aCategory = array(
		100 => array(
			"name" => "撰写 Compose",
			"url" => "compose.php",
		),
		101 => array(
			"name" => "文章 Archive",
			"url" => "archive.php",
		),
		200 => array(
			"name" => "评论 Comment",
			"url" => "comment.php",
		),
		300 => array(
			"name" => "文件 Upload",
			"url" => "upload.php",
		),
		400 => array(
			"name" => "访问 Access",
			"url" => "access.php",
		),
		500 => array(
			"name" => "说 Tweet",
			"url" => "tweet.php",
		),
	);

	$iSelected = 0;
	settype($aTango["menu"], "integer");

	echo "<ul>";

	foreach ($aCategory as $iKey => $aRow) {
		if (!empty($aRow["hide"])) {
			continue;
		}

		$aName = explode(" ", $aRow["name"], 2);

		echo "<li".($iKey == 100 ? " class=\"hr\"" : "").">"
			."<a".($iKey == $aTango["menu"] ? " class=\"select\"" : "")." href=\"".$aRow["url"]."\">".htmlspecialchars($aName[0])." "
			.(empty($aName[1]) ? "" : "<span>".htmlspecialchars($aName[1])."</span>")
			."</a></li>";
	}
	echo "</ul>";

} else {

	$iSelected = BlogMenu::getSelectedCategory();
	$aCategory = BlogMenu::getCategory();

	echo "<ul>";
	foreach ($aCategory as $iKey => $aRow) {
		if (!empty($aRow["hide"])) {
			continue;
		}

		$aName = explode(" ", $aRow["name"], 2);

		$sURL = "/category_".$iKey;
		if ($iKey == 13) {
			$sURL = "/tweets";
		}

		echo "<li".(($iKey == 7 || $iKey == 13 || $iKey == 2) ? " class=\"hr\"" : "").">"
			."<a".($iKey == $iSelected ? " class=\"select\"" : "")." href=\"".$sURL."\">".htmlspecialchars($aName[0])." "
			.(empty($aName[1]) ? "" : "<span>".htmlspecialchars($aName[1])."</span>")
			."</a></li>";
	}
	echo "</ul>";
}
?><div class="fill"></div></div>
</nav>
</header>

<div class="outBox">
