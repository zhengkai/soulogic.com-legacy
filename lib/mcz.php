<?php
class MCz {

	protected static $_o;   // server 连接信息

	public static function setServer($aServer = array()) {
		$aServer = $aServer ?: array(
			array("127.0.0.1", 11211, 1),
		);
		$o = new Memcached();
		$o->addServers($aServer);
		self::$_o = $o;
	}

	public static function add($sKey, $mValue, $iExpire = NULL) {
		$o = self::$_o;
		return $o->add($sKey, $mValue, $iExpire);
	}

	public static function get($sKey) {
		$o = self::$_o;
		return $o->get($sKey);
	}

	public static function set($sKey, $mValue, $iExpire = NULL) {
		$o = self::$_o;
		return $o->set($sKey, $mValue, $iExpire);
	}

	public static function replace($sKey, $mValue, $iExpire = NULL) {
		$o = self::$_o;
		return $o->replace($sKey, $mValue, $iExpire);
	}

	public static function del($sKey) {
		$o = self::$_o;
		return $o->delete($sKey);
	}

	public static function delete($sKey) { // alias of del
		return self::del($sKey);
	}

	public static function getStats() {
		$o = self::$_o;
		return $o->getStats();
	}

	public static function flush($iDelay = NULL) {
		$o = self::$_o;
		return $o->flush($iDelay);
	}
}

