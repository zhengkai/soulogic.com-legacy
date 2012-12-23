<?php
Page::setBodyClass("index");

PageHeader::addCSS("index.css");

BlogMenu::setTitle("Soulogic 灵魂逻辑");

Page::setCanonical("http://soulogic.com/");

// dump($aTango["list"]);

?>
<div class="profile">
<p>郑凯</p>
<p>网名 Platinum</p>
<p>信箱 zhengkai@gmail.com</p>
<p>82 年生于黑龙江，长于辽宁，<br />现居北京</p>
<p>已婚，高中文化，无前科</p>
<p>于 2000 年末第一次接触 PHP，<br />19 岁生日之前开始以此为职业，至今。</p>
<p>缺点：木讷、偏执、刻薄、恶毒</p>
<p>优点：木讷、偏执、刻薄、恶毒、傲慢</p>
<p>用百度者，祝福你们全家世世代代用百度</p>
</div>

<div class="indexList">
<p class="title">最新网志</p>
<?php
foreach ($aTango["list"] as $iArchive => $aArchive) {
	?>
	<p><a href="<?php echo Link::get("archive", $iArchive); ?>"><?php echo $aArchive["title"]; ?></a></p>
	<?php
}
?>
</div>
