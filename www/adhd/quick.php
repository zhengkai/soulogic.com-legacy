<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>成人多动症（注意缺陷障碍，ADD/ADHD）快速测试</title>
<link rel="canonical" href="http://soulogic.com/adhd/quick.php" />
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
	'你是否经常在具有挑战性的部分已经完成之后，困扰于了结一个项目的所有细节？',
	'你是否经常在当你必须完成一个需要组织协调的任务时感到难以理清事情的顺序？',
	'你是否经常有记不住任命和义务的问题？',
	'当你有一项任务需要很多时间思考时，你是否经常避免或推迟起步？',
	'你是否经常在你不得不坐很长时间的情况下，玩弄或扭动你的手或脚？',
	'你是否经常感觉过度活跃并强迫去做事，就像被一个马达驱动？',
);

$aAnswer = array(
	'从不',
	'很少',
	'有时',
	'经常',
	'非常经常',
);
?>
<div style="width: 960px; margin: 0 auto;">
<h1>成人多动症（注意缺陷障碍，ADD/ADHD）快速测试</h1>

<p class="author">Jasper/Goldberg Adult ADD/ADHD Screening Quiz<br />
by Larry Jasper & Ivan Goldberg<br /><br />
英文原版 <a href="http://psychcentral.com/quizzes/adultaddquiz.htm">http://psychcentral.com/quizzes/adultaddquiz.htm</a>，翻译：Tarkus，页面：Platinum</p>

<p class="description">此问卷用于帮助确定你是否需要找心理健康专业人员进行诊断和针对成人多动症或成人注意缺陷障碍的治疗。基于成人ADHD症状自评量表(ASRS-v1.1)。我们还有一份<a href="./index.php">稍长一些的成人多动症测试</a>。</p>
<p class="description"><b>说明：</b> 这是一个快速测试，有6个题目。请回答下列问题，用每个问题下的量度评估自己。当你回答问题时，选择<b>过去的6个月里</b>，最能准确描述你的感受和指导你自身行为的答案 </b>。</p>

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
<p>根据你完成的这份自评问卷，表明你目前没有患成人多动症。不过，你不应该将此作为任何的诊断和诊疗推荐意见。你经历着人生的正常起落。<br /><img src="http://psychcentral.com/images/adhd_free.gif" /></p>
<p>根据你对这份ADHD筛查测试的回答，你很有可能患有成人多动症。与你答案相似的人被诊断为典型ADHD或ADD病例，并需要寻求专业治疗方案。<br />你不应该将此作为任何的诊断和诊疗推荐意见。不过，尽快向受过训练的心理健康专业人员寻求进一步的诊断以排除成人多动症是明智的，并且对你有利。<br /><img src="http://psychcentral.com/images/adhd_mild.gif" /></p>
<p>根据你对这份ADHD筛查测试的回答，你似乎患有成人多动症。与你答案相似的人被诊断为典型ADHD或ADD病例，并需要寻求专业治疗方案。<br />你不应该将此作为任何的诊断和诊疗推荐意见。不过，立即向受过训练的心理健康专业人员寻求进一步的诊断是明智的，并且对你有利。<br /><img src="http://psychcentral.com/images/adhd_serious.gif"></p>
</div>

<script>
var aLevel = [12, 16];
</script>
<div>
</body>
</html>
