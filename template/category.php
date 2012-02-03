<?php
Page::setBodyClass("category");

$sURL = Link::get("category", $_GET["category_id"]);
Page::setCanonical($sURL);

// Page::followOnly();

$aCategory = BlogMenu::getCategory($_GET["category_id"]);
BlogMenu::setTitle($aCategory["name"]." - Category - Soulogic");
BlogMenu::setSelectedCategory($_GET["category_id"]);

$sDatePrev = "";
foreach ($aTango["list"] as $iArchive => $aArchive) {
	// dump($aArchive);

	$sDateShow = "";
	$sDate = date("Y", $aArchive["date_p"]);
	if ($sDatePrev !== $sDate) {
		$sDatePrev = $sDate;
		$sDateShow = "<div class=\"date\">".$sDate."</div>";
	}

	?>
	<div class="archiveList">
	<div class="archive"><a<?php
		if ($aArchive["original_tag"] !== "Y") { echo " class=\"copy\""; };
	?> href="<?php echo Link::get("archive", $iArchive); ?>"><?php echo $aArchive["title"]; ?></a></div>
	<?php echo $sDateShow; ?>
	</div>
	<?php
}

