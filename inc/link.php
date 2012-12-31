<?php

// echo Link::get("archive", 13);

class Link {

	protected static $_aFormat = array(
		"archive"  => "/archives/%d",
		"upload"   => "/upload/%d",
		"tweets"   => "/tweets/%d",
		"category" => "/category_%d",
	);

	public static function get() {

		$sURLBase = "soulogic.com";

		$aArg = func_get_args();
		$sType = array_shift($aArg);
		$sFormat =& self::$_aFormat[$sType];
		if (empty($sFormat)) {
			trigger_error("Undefined Type", E_USER_ERROR);
		}
		array_unshift($aArg, $sFormat);
		$sLink = call_user_func_array("sprintf", $aArg);
		return "https://".$sURLBase.$sLink;
	}
}
