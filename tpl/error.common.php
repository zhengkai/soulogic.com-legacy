<div style="-moz-border-radius:10px; -webkit-border-radius:10px; margin: 0 auto 30px; width: 400px; padding: 16px 40px; border: 1px solid red; background-color: #311;">
<p style="font-size: 16px; padding: 0 20px 5px; margin: 0 0 0 -20px; width: 400px; font-weight: bold; color: red; border-bottom: 1px solid red;">错误</p>
<p style="font-size: 14px; margin-top: 10px; font-weight: bold; color: red; margin-top: 10px; line-height: 24px;"><?php echo nl2br(htmlFilter($aTango["error"])); ?></p>
</div>

<p style="text-align: center; font-size: 14px; margin-bottom: 200px;"><?php
if (!empty($_SERVER["HTTP_REFERER"])) {
	echo "<a href=\"javascript:history.back(1);\">返回前一页</a>";
} else {
	echo "<a href=\"/\">返回首页</a>";
}
?></p>
