<?php
$s = 'color: #004D80;';

$iRate = -30;
$iRate = 10;

$s = preg_replace('#[^0-9a-fA-F]+$#', '', $s);
$s = substr($s, -6);

$a = str_split($s, 2);

$a = array_map(function ($s) use ($iRate) {
	$i = base_convert($s, 16, 10);
	if ($iRate > 0) {
		$i = 255 - $i;
		$i = $i * (100 - $iRate) / 100;
		$i = 255 - $i;
	} else {
		$i = $i * (100 - abs($iRate)) / 100;
	}
	$i = round($i);

	$s = sprintf('%02s', base_convert($i, 10, 16));
	return $s;
}, $a);

echo '#'.implode('', $a);
