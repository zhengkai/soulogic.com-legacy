<?php
// $Id: filter.ext.php 39 2010-04-19 08:48:49Z zhengkai $

require_once(__DIR__."/../lib/filter.class.php");

class FilterExt extends Filter {

	protected static function _ErrorReferer($sRefHost) {
		$sError = "";
		$sError .= "\n\n".date("Y-m-d H:i:s")." :\n\n";
		$sError .= var_export($_SERVER, TRUE);
		debugLog($sError, "/tmp/filter_post_attack.log");
		echo "filter not passed";
		Page::noSend();
		exit;
	}
}
