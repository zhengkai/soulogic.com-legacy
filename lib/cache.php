<?php
require_once(__DIR__."/mcz.php");

class Cache {

	protected static $_aConfig = array();
	protected static $_aConfigDefault = array(
		"pdo_method" => "getAll",
	);
	public static function getarray() {
		return array("a" => "b", "c" => "d");
	}

	public static function set($sType, $aOption) {
		$aSet =& self::$_aConfig[$sType];
		if (!empty($aSet)) {
			trigger_error(__METHOD__.": duplicate type ".$sType, E_USER_ERROR);
			return FALSE;
		}
		$aSet = $aOption + self::$_aConfigDefault;
	}

	public static function get() {

		list($sType, $aConfig, $sQuery, $sKey) = call_user_func_array(
			array("self", "_argFormat"),
			func_get_args());

		// trigger_error("memcache key ".$sKey);

		$aCache = MCz::get($sKey);
		if ($aCache !== FALSE) {
			// trigger_error("memcache ".$sType." : [  OK  ]");
			return $aCache;
		}

		// trigger_error("memcache ".$sType." : [ Fail ]");

		$oDB = DBz::getInstance($aConfig["db"]);

		$aData = $oDB->{$aConfig["pdo_method"]}($sQuery);

		MCz::set($sKey, $aData);

		return $aData;
	}

	public static function clean() {

		MCz::flush();
		return TRUE;

		list( , , , $sKey) = call_user_func_array(
			array("self", "_argFormat"),
			func_get_args());

		MCz::del($sKey);
	}

	public static function _argFormat() {

		$aArg = func_get_args();
		$sType = array_shift($aArg);

		if (empty(self::$_aConfig[$sType])) {
			trigger_error(__METHOD__.": no such type ".$sType, E_USER_ERROR);
			return FALSE;
		}
		$aConfig = self::$_aConfig[$sType];

		$sQuery = self::_sprintf($aConfig["query"], $aArg);
		$sKey   = self::_sprintf($aConfig["key"], $aArg);

		return array(
			$sType,
			$aConfig,
			$sQuery,
			$sKey,
		);
	}

	public static function _sprintf($sFormat, $aArg) {
		$aArg = array_merge(array($sFormat), $aArg);
		return call_user_func_array("sprintf", $aArg);
	}
}
