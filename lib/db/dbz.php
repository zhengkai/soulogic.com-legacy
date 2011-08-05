<?php
// $Id: dbz.php 61 2010-04-26 10:15:32Z zhengkai $

require_once(__DIR__."/dbz.single.php");
// require_once(__DIR__."/dbz.multi.class.php");

/**
 *  数据库统一调用接口
 *
 *  使用时，先通过 setServer 定义连接信息，需要连数据库时候通过 getInstance 获得相应 server 的 client PDO 类
 *
 *  这是一个使用范例：
 *
 *  FilterExt::run("POST", array(
 *  	"group_id" => "int",
 *  	"topic_id" => "int",
 *  	"post_id" => "int",
 *  	"title"   => "string",
 *  	"content" => "longString",
 *  ));
 */

class DBz {

	protected static $_aConfig = array();   // server 连接信息
	protected static $_aInstance = array(); // mysql 链接资源
	protected static $_bDebug  = FALSE;
	protected static $_iDebug  = -1;
	protected static $_aDebugLog  = array();
	protected static $_aConfigFormatCheck = array("user", "password", "host");

	/**
	 *
	 *
	 */
	public static function debug($bDebug = NULL) {
		if ($bDebug !== NULL) {
			self::$_bDebug = (boolean)$bDebug;
		}
		return self::$_bDebug;
	}

	public static function setServer($sName, $aConfig) {
		if (!is_array(self::$_aInstance)) {
			self::_errorMessage(__METHOD__, "use it before disabled");
			return FALSE;
		}
		foreach (self::$_aConfigFormatCheck as $sKey) {
			if (empty($aConfig[$sKey])) {
				if ($sKey === "host" && !empty($aConfig["unix_socket"])) {
					continue;
				}
				self::_errorMessage(__METHOD__, "server format error, no ".$sKey." in ".$sName);
			}
		}
		self::$_aConfig[$sName] = $aConfig;
	}

	public static function getInstance($sName) {
		if (!is_array(self::$_aInstance)) {
			self::_errorMessage(__METHOD__, "use it before disabled");
			return FALSE;
		}
		if (!array_key_exists($sName, self::$_aConfig)) {
			self::_errorMessage(__METHOD__, "undefined server ".$sName);
		}
		$aServer = self::$_aConfig[$sName];
		if (!array_key_exists($sName, self::$_aInstance)) {
			$aServer["name"] = $sName;
			$aServer["debug"] = self::$_bDebug;
			self::$_aInstance[$sName] = new DBzSingle($aServer);
			/*
			if (array_key_exists("single", $aServer)) {
				self::$_aInstance[$sName] = new DBzSingle($aServer);
			} else {
				self::$_aInstance[$sName] = new DBzMulti($aServer);
			}
			 */
		}
		return self::$_aInstance[$sName];
	}

	public static function disable() {
		self::$_aInstance = NULL;
	}

	public static function addDebugLog($aInfo) {
		self::$_aDebugLog[] = $aInfo;
	}

	public static function getDebugLog() {
		return self::$_aDebugLog;
	}

	protected static function _errorMessage($sMethod, $sMessage) {
		trigger_error("Class ".$sMethod." : ".$sMessage." at ".$_SERVER["SCRIPT_NAME"], E_USER_ERROR);
	}
}
