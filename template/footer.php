<br style="clear: both;" />
</div>

<?php

$aDBLog = DBz::getDebugLog();

$sShow = "";
$bError = FALSE;
$aDBServer = array();
foreach ($aDBLog as $aInfo) {
	if (!empty($aInfo["error"])) {
		$bError = TRUE;
	}
	if ($bSlow = ($aInfo["time"] > 0.1)) {
		$bError = TRUE;
	}

	$aDBServer[$aInfo["server"]] = 1;

	$sShow .= "<div class=\"".$aInfo["server"]."\">";
	$sShow .= "<p style=\"color: silver;\">Server: ".$aInfo["server"]."</p>";
	$sShow .= "<p style=\"color: blue;\">&#160;Query: ".$aInfo["query"]."</p>";
	$sShow .= "<p".($bSlow ? " style=\"color: red;\"" : "").">&#160; Time: ".sprintf("%.05f", $aInfo["time"])."</p>";
	if (!empty($aInfo["error"])) {
		$sShow .= "<p style=\"color: red;\">Error Code: ".$aInfo["error"]["code"]."</p>";
		$sShow .= "<p style=\"color: red;\">Error Message: ".$aInfo["error"]["message"]."</p>";
	}
	$sShow .= "</div>";
}

?>

<div class="footer highlight"><div><div>

<p>2001 - <?php echo date("Y"); ?> <a hidefocus="true" href="http://creativecommons.org/licenses/by/3.0/deed.zh" class="footer">Some Rights Reserved</a>, Template referenced from <a href="http://www.themelab.com/2008/04/01/colourise-free-wordpress-theme-38/">Colourise</a></p>

</div></div></div>

</body>
</html>
