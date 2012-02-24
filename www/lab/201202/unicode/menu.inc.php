<div class="menu">
<?php
function type_name($sType, $lType, $sSelect) {
	$sName = $lType[$sType];
	$aName = explode(" ", $sName);
	$sCN = array_pop($aName);
	$aName = array(
		"en" => implode(" ", $aName),
		"cn" => $sCN,
	);
	$bSelect = $sSelect == $sType;
	$sClass = $bSelect ? " class=\"select\"" : "";
	$sHref = $bSelect ? "#" : "?type=".$sType;
	echo "<a href=\"".$sHref."\"".$sClass.">"
		.$aName["en"]."<br /><span>".$aName["cn"]."</span></a>";
}
foreach ($aMenu as $sType => $lSub) {
	?>
	<div>
	<?php
	$aName = type_name($sType, $lType, $sSelect);
	foreach ($lSub as $sType) {
		$aName = type_name($sType, $lType, $sSelect);
	}
	echo "</div>";
}
?>
</div>

