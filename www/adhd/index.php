<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>成人多动症（注意缺陷障碍，ADD/ADHD）测试</title>
<link rel="canonical" href="http://soulogic.com/adhd/" />
<link rel="stylesheet" href="style.css" type="text/css" />
<script type="text/javascript" src="script.js"></script>
</head>

<body>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-98185-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?PHP
$aQuestion = array(
	'在家中，在工作中或在学校中，我发现我的头脑总是在回避无趣的或困难的任务。',
	'我发现很难阅读书面材料，除非它很有趣或很简单。',
	'特别是在集体活动中，我发现很难持续关注于正进行的谈话。',
	'我脾气暴躁，易发怒。',
	'我比较敏感，为小烦恼感到沮丧。',
	'我说话时不经考虑，过后又为说出的话感到后悔。',
	'我没经充分考虑可能会导致的坏结果就快速做出决定。',
	'因为我先说后想的趋向，导致我的人际关系很难进展。',
	'我的情绪时高时低。',
	'我对理清一系列任务或活动的顺序有困难。',
	'我很容易变得沮丧。',
	'我看上去脸皮很薄，很多事情都能打击到我。',
	'我几乎总是在忙于某事。',
	'比起坐着不动，动着让我感到舒服些。',
	'在与人交谈时，别人还没问完我就开始回答。',
	'我经常同时进行多个项目，而且很多都半途而废。',
	'我头脑里经常出现很多“静态的”或“唧唧歪歪的”。',
	'即使在安静地坐着，我也时常移动手或脚。',
	'在集体活动中，等待轮到自己的过程让我觉得艰难。',
	'我的头脑散乱得已经难以正常运作。',
	'我的头脑就像是成了一个弹球台，思绪在总是在弹来跳去。',
	'我的大脑感觉就像是一台所有频道同时播放的电视机。',
	'我无法停止做白日梦。',
	'我大脑凌乱无序的运作方式，让自己感到痛苦伤心。',
);

$aAnswer = array(
	'完全不是',
	'有一点点',
	'有一点',
	'差不多',
	'相当准确',
	'非常准确',
);
?>
<div style="width: 960px; margin: 0 auto;">
<h1>成人多动症（注意缺陷障碍，ADD/ADHD）筛查测试</h1>

<p class="author">Jasper/Goldberg Adult ADD/ADHD Screening Quiz<br />
by Larry Jasper & Ivan Goldberg<br /><br />
英文原版 <a href="http://psychcentral.com/addquiz.htm">http://psychcentral.com/addquiz.htm</a>，翻译：Tarkus，页面：Platinum</p>

<p class="description">此问卷用于确定你是否需要找心理健康专业人员进行诊断和针对成人多动症或成人注意缺陷障碍的治疗。我们还有一份<a href="./quick">短的成人多动症测试</a>，只有6个问题。</p>
<p class="description"><b>说明：</b> 以下24个题目需要参照<b>你成年生活中最平常的表现和感受</b>。如果你往常有某种行为方式，而最近有所变化，你的回答应该反映往常的情况。在每一题中，选中描述程度上最接近的选项。</p>

<hr />

<form id="quizzes">
<?PHP
foreach ($aQuestion as $iQuestion => $sQuestion) {
	$iQuestion++;
	echo "<div class=\"question\">";
	echo "<p class=\"question\">".sprintf("%02d", $iQuestion).". ".htmlspecialchars($sQuestion)."</p>";
	echo "<p class=\"answer\">";
	foreach ($aAnswer as $iAnswer => $sAnswer) {
		echo "<label><input type=\"radio\" name=\"q".$iQuestion."\" value=\"".$iAnswer."\" />".$sAnswer."<br /></label>";
	}
	echo "</p>";
	echo "</div>";
}
?>
</form>

<p class="button" id="answer"><button>对我的问卷进行评分</button><button>清空问卷</button></p>

<div id="result">
<div class="score">0</div>
<p>根据你完成的这份自评问卷，表明你目前没有患成人多动症。不过，你不应该将此作为任何的诊断和诊疗推荐意见。<br /><img src="http://psychcentral.com/images/adhd_free.gif" /></p>
<p>你似乎遇到了普通人群中常见的注意力集中方面的问题，但是处于向更严重情况发展的边缘。目前并不明确你所遭遇的问题是否严重到需要进一步的诊断和治疗。你不应该将此作为任何的诊断和诊疗推荐意见。如果你的担心的困扰你的日常生活，向受过训练的心理健康专业人员作咨询。<br /><img src="http://psychcentral.com/images/adhd_borderline.gif" /></p>
<p>根据你对这份自评问卷的回答，你看上去有轻度的集中注意力困难的情况。你不应该将此作为任何的诊断和诊疗推荐意见。不过，如果由于这种情况已经困扰了你的日常生活或者你想寻求更深入、更全面的回答，你可以向受过训练的心理健康专业人员作进一步咨询。<br /><img src="http://psychcentral.com/images/adhd_mild.gif" /></p>
<p>根据你对这份自评问卷的回答，你似乎有中度的注意力集中困难的情况。你不应该将此作为任何的诊断和诊疗推荐意见。不过，尽快向受过训练的心理健康专业人员寻求进一步的诊断以排除注意缺陷障碍是明智的，并且对你有利。<br /><img src="http://psychcentral.com/images/adhd_moderate.gif" /></p>
<p>根据你对这份自评问卷的回答，你目前很有可能患有成人多动症。你不应该将此作为任何的诊断和诊疗推荐意见。不过，<b>立即</b>向受过训练的心理健康专业人员寻求进一步的诊断是明智的，并且对你有利。<br /><img src="http://psychcentral.com/images/adhd_serious.gif" /></p>
</div>

<script>
var aLevel = [25, 35, 50, 70];
</script>
<div>
</body>
</html>
