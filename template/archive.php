<?php
Page::setBodyClass("archive");

PageHeader::addJS("comment.js");

$aArchive =& $aTango["archive"];

BlogMenu::setTitle($aArchive["title"]." - Archive - Soulogic");
BlogMenu::setSelectedCategory($aArchive["folder"]);

$sURL = Link::get("archive", $aArchive["id"]);

Page::setCanonical($sURL);

?>
<div style="width: 640px; float: left;">
<h1<?php
	if ($aArchive["original_tag"] !== "Y") { echo " class=\"copy\""; }
?>><?php echo $aArchive["title"]; ?></h1>
<p class="archiveDate">Posted on <?php echo date("F j, Y h:i A", $aArchive["date_p"]); ?></p>
<?php
if ($aArchive["original_tag"] === "Y") {
	?><p class="cc_copyright">作者：郑凯<br />
<a rel="nofollow" href="http://creativecommons.org/licenses/by/3.0/deed.zh">版权声明</a>：可以任意转载，转载时请务必以超链接形式标明文章原始出处和作者信息<br />
<a class="self" href="<?php echo $sURL; ?>"><?php echo $sURL; ?></a>
</p><?php
}
?>
<div class="content"><?php

$bLine = FALSE;

if ($aArchive["author"]) {
	echo "<p>作者：".$aArchive["author"]."</p>";
}

if ($aArchive["provenance_text"] || $aArchive["provenance_url"]) {
	$bLine = TRUE;
	if ($aArchive["provenance_url"]) {
		$aArchive["provenance_text"] = $aArchive["provenance_text"] ? : $aArchive["provenance_url"];
		$sShow = "<a href=\"http://".$aArchive["provenance_url"]."\" />".$aArchive["provenance_text"]."</a>";
	} else {
		$sShow = $aArchive["provenance_text"];
	}
	echo "<p>via ".$sShow."</p>";
}

if ($bLine) {
	echo "<hr />";
}

echo old_convert($aArchive["content"] ?: $aArchive["preview"]);
?></div>
</div>

<div class="sidebar">

<p class="title">相邻文章</p>

<div>
<?php

if ($aTango["next"]) {
	?><p class="next"><a href="<?php echo Link::get("archive", $aTango["next"]["id"]); ?>"><?php echo $aTango["next"]["title"]; ?></a></p><?php
}
if ($aTango["prev"]) {
	?><p class="prev"><a href="<?php echo Link::get("archive", $aTango["prev"]["id"]); ?>"><?php echo $aTango["prev"]["title"]; ?></a></p><?php
}

?>
</div>

<p class="title">同类文章</p>
<ul>
<?php
foreach ($aTango["related"] as $iArchive => $sTitle) {
	echo "<li>";
	if ($iArchive == $aArchive["id"]) {
		echo "<span>".$sTitle."</span>";
	} else {
		echo "<a href=\"".Link::get("archive", $iArchive)."\">".$sTitle."</a>";
	}
	echo "</li>";
}
?>
</ul>
</div>

<p class="commentTitle<?php echo $aTango["comment"] ? "" : " hide"; ?>">访客评论</p>
<div id="comment"<?php echo $aTango["comment"] ? "" : " class=\"hide\""; ?>>
<div class="loading">
</div>
</div>

<p class="commentTitle">添加评论</p>
<form id="commentForm" class="postComment">
<input type="hidden" name="archive_id" value="<?php echo $aArchive["id"]; ?>" />
<p>名字/信箱/网址 均非必填项，信箱不会公开，只用于站长联系及显示 gravatar 头像<br />不要在留言中使用 &#60;a&#62; 或 [URL] 标签，否则会被判定为 spam</p>
<table>
	<tr>
		<td class="rowname">名字 &#160;</td>
		<td><input type="text" name="name" maxlength="20" /></td>
	</tr>
	<tr>
		<td class="rowname">信箱 &#160;</td>
		<td><input type="text" name="email" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="rowname">网址 &#160;</td>
		<td><input type="text" name="url" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="rowname">评论<span> *</span></td>
		<td><textarea name="content"></textarea></td>
	</tr>
	<tr>
		<td class="rowname">&#160;</td>
		<td><button type="submit">发布评论</button></td>
	</tr>
</table>
</form>
