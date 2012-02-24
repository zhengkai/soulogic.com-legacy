<?php
require_once __DIR__."/common.inc.php";

$sSelect = $_GET["type"];
if (!array_key_exists($sSelect, $lType)) {
	$sSelect = FALSE;
}

require_once __DIR__."/menu.inc.php";

if ($sSelect) {
	$lMatch = array();
	for ($i = 1; $i < 65536; $i++) {
		$sChar = iconv("UCS-2", "UTF-8//IGNORE", pack("s", $i));
		if (preg_match("/^\\p{".$sSelect."}$/u", $sChar)) {
			$lMatch[] = $i;
		}
	}
	$iCount = count($lMatch);
} else {
	$iCount = 65535;
}

$iPagePerNumber = 350;
$iPageMax = ceil($iCount / $iPagePerNumber) - 1;
if ($iPage < 0) {
	$iPage = 0;
} else if ($iPage > $iPageMax) {
	$iPage = $iPageMax;
}

$iStart = $iPage * $iPagePerNumber;
$iTop = $iStart + $iPagePerNumber;

if ($sSelect) {
	$lMatch = array_slice($lMatch, $iStart, $iPagePerNumber, TRUE);
} else {
	$lMatch = range($iStart, ($iTop > $iCount) ? (65535 % $iPagePerNumber) : $iPagePerNumber);
}

$iTop = $iTop > $iCount ? $iCount : $iTop;
$iStart = $iCount ? $iStart + 1 : 0;
?>
<p>共计 <?php echo $iCount; ?> 个，正在显示 <?php echo $iStart." - ".$iTop; ?> 个</p>

<div class="group">
<?php
$iSlice = 10;
for ($i = 0, $j = count($lMatch); $i < $j; $i += $iSlice) {
	$lGroup = array_slice($lMatch, $i, $iSlice, TRUE);
	echo "<table class=\"list\">\n";
	foreach ($lGroup as $iChar) {
		$sChar = iconv("UCS-2", "UTF-8//IGNORE", pack("s", $iChar));
		echo "<tr>";
		echo "<td>&#38;#".sprintf("%03d", ord_utf8($sChar)).";</td>";
		echo "<td>U+".utf8_ucs2be($sChar)."</td>";
		echo "<td>".$sChar."</td>";
		echo "</tr>\n";
	}
	echo "</table>\n";
}
?>
</div>

