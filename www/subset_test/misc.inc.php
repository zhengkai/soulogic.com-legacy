<?php


function debugShow($mShow) {
	if (!DEBUG) {
		return FALSE;
	}
	if (is_array($mShow)) {
		print_r($mShow);
	} else {
		var_dump($mShow);
	}
}
