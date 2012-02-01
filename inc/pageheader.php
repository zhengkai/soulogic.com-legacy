<?php
settype($_SERVER["HTTP_HOST"], "string");
settype($_SERVER["REQUEST_URI"], "string");

class PageHeader {

	protected static $_aAlias = array(
		"wiki"     => "knowlege",
		"passport" => "platform",
		"forum"    => "group",
	);

	protected static $_aControl = array(
	/*
		"wiki" => array(
			"css" => array("abc.css", "def.css")
		),
	 */
	);

	protected static $_sInsert = "\n";

	protected static $_sDir;

	protected static $_aCSS = array();
	protected static $_aJS = array();

	public static function addCSS($sCSS) {
		self::$_aCSS[] = $sCSS;
	}

	public static function addJS($sJS) {
		self::$_aJS[] = $sJS;
	}

	public static function addExt($sContent) {
		self::$_sInsert .= $sContent;
	}

	public static function getExt($sContent) {
		return self::$_sInsert;
	}

	public static function getResDomain() {
		return TANGO_DEV ? "soulogic.res" : "www.soulogic.com";
	}

	public static function get() {

		$sResDir = dirname(dirname(__DIR__))."/res/";

		$sDir = self::getDir();

		if (isset(self::$_aAlias[$sDir])) {
			$sDir = self::$_aAlias[$sDir];
		}

		if (preg_match("/^[a-z\\_]+$/", $sDir)) {
			if (file_exists($sResDir.$sDir.".css")) {
				self::addCSS($sDir.".css");
			}
			if (file_exists($sResDir.$sDir.".js")) {
				self::addJS($sDir.".js");
			}
			$sControl =& self::$_aControl[$sDir];
			if (is_array($sControl)) {
				if (isset($sControl["css"])) {
					foreach ($sControl["css"] as $sCSS) {
						self::addCSS($sCSS.".css");
					}
				}
				if (isset($sControl["js"])) {
					foreach ($sControl["js"] as $sJS) {
						self::addCSS($sJS.".js");
					}
				}
			}
		}

		$aCSS = array(
			"init.css",
			"common.css",
		);
		$aCSS = array_unique(array_merge($aCSS, self::$_aCSS));

		if (!empty(self::$_aJS)) {
			$aJS = array(
				"jquery-1.4.2.min.js",
			);
			$aJS = array_unique(array_merge($aJS, self::$_aJS));
		}

		$sReturn = "\n";

		if (TANGO_DEV) {
			$aCSS = scandir(dirname(__DIR__)."/res/");
			$aFirst = array(
				"init.css",
				"common.css",
			);
			$aCSS = array_filter($aCSS, function($sFile) use ($aFirst) {
				if (in_array($sFile, $aFirst)) {
					return FALSE;
				}
				if (pathinfo($sFile, PATHINFO_EXTENSION) !== "css") {
					return FALSE;
				}
				return TRUE;
			});
			$aCSS = array_merge($aFirst, $aCSS);

			$fTime = microtime(TRUE);

			foreach ($aCSS as $sCSS) {
				$sReturn .= "<link rel=\"stylesheet\" href=\"//".self::getResDomain()."/".$sCSS."?tmp=".$fTime."\" type=\"text/css\" />\n";
			}

			if (!empty($aJS)) {
				foreach ($aJS as $sJS) {
					$sReturn .= "<script type=\"text/javascript\" src=\"//".self::getResDomain()."/".$sJS."?tmp=".$fTime."\"></script>\n";
				}
			}
		} else {
			$aCSS = array("default");
			$sReturn .= "<link rel=\"stylesheet\" href=\"".self::_getResLink($aCSS, "css")."\" type=\"text/css\" />\n";
			if (!empty($aJS)) {
				$sReturn .= "<script type=\"text/javascript\" src=\"".self::_getResLink($aJS,  "js")."\"></script>\n";
			}
		}

		return $sReturn;
	}

	protected static function _getResLink($aRes, $sType = "js") {
		$sFilterPattern = "/^(\\/)?(.*?)(\\.js|\\.css)?(\\.)?$/";
		$aRes = preg_replace($sFilterPattern, "\\2", $aRes);
		$sRes = implode(",", $aRes);

		$iVersion = getResVersion();

		$sHash = hash("crc32", $sRes."O0WPmINmROtIBL2AO3Fu".$iVersion);

		return $sLink = "//".self::getResDomain()."/combo/".$sRes."_v".$iVersion."_".$sHash.".".$sType;
	}

	public static function setDir($sDir) {
		self::$_sDir = $sDir;
	}

	public static function getDir() {
		if (self::$_sDir === NULL) {
			$sDir = strstr(substr($_SERVER["REQUEST_URI"], 1), '/', true);
			if (isset(self::$_aAlias[$sDir])) {
				$sDir = self::$_aAlias[$sDir];
			}
			self::setDir($sDir);
		}
		return self::$_sDir;
	}
}
