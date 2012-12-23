<?php
require_once(__DIR__."/pageheader.php");

define("TANGO_RES", "http://".PageHeader::getResDomain()."/");

register_shutdown_function(function() {
	Page::send();
});

class Page {

	protected static $_bAjax; // ajax 页面
	protected static $_bTemplate = TRUE; // 是否可以加载模板
	protected static $_bSend = TRUE; // 用于 shutdown
	protected static $_fBeginTime; // 用于 shutdown
	protected static $_aTitle = ""; // html title
	protected static $_sBodyClass = ""; // html title
	protected static $_bEscapeHTML = TRUE; // www 的变量传到 template 时是否转意
	protected static $_sCanonical = FALSE; // SEO 原始链接
	protected static $_bFollowOnly = FALSE; // SEO head 里的标签 <meta name="robots" content="noindex,follow" />
	protected static $_bAdmin = FALSE; // 只有开发模式可见

	public static $aData = array();

	public static function inAdmin() {
		return self::$_bAdmin;
	}

	public static function noTemplate() {
		self::$_bTemplate = FALSE;
	}

	public static function noSend() {
		self::$_bSend = FALSE;
	}

	public static function noEscapeHTML() {
		self::$_bEscapeHTML = FALSE;
	}

	public static function isSent() {
		return self::$_bSent;
	}

	public static function setBeginTime() {
		if (empty(self::$_fBeginTime)) {
			self::$_fBeginTime = microtime(TRUE);
		}
	}

	public static function setBodyClass($sClass = FALSE) {
		self::$_sBodyClass = $sClass;
	}

	public static function getBodyClass() {
		return self::$_sBodyClass ?: "";
	}

	public static function followOnly() {
		self::$_bFollowOnly = TRUE;
	}

	public static function isFollowOnly() {
		return self::$_bFollowOnly;
	}

	public static function setCanonical($sURL) {
		self::$_sCanonical = $sURL;
	}

	public static function getCanonical() {
		return self::$_sCanonical;
	}

	public static function setTitle() {
		self::$_aTitle = func_get_args();
	}

	public static function setAjax($bDo = TRUE) {
		self::$_bAjax = $bDo;
	}

	public static function getTitle() {
		$aTitle = self::$_aTitle;
		$aTitle[] = "Soulogic";
		$sTitle = htmlFilter(implode(" - ", $aTitle));
		return $sTitle;
	}

	public static function jump($sURI = "/", $bPermanent = TRUE) {
		self::noSend();

		if (headers_sent($sScript, $iLine)) {
			exit;
		}

		header("Location: ".$sURI, $bPermanent ? 301 : 302);
		exit;
	}

	public static function error($sMessage) {
		self::$aData["error"] = $sMessage;
		exit;
	}

	public static function getTimeCost() {
		return microtime(TRUE) - self::$_fBeginTime;
	}

	public static function getDirRoot() {
		return dirname(__DIR__);
	}

	public static function getScriptPathInfo() {
		$sFile = realpath($_SERVER["SCRIPT_FILENAME"]);
		$aPath = pathinfo($sFile);
		$sPathFromBase = substr($sFile, strlen(self::getDirRoot()."/www"));
		return array($aPath, $sPathFromBase);
	}

	public static function checkScriptFile() {

		list($aPath, $sPathFromBase) = self::getScriptPathInfo();

		// 路径中不得包含大写字母
		if ($sPathFromBase !== strtolower($sPathFromBase)) {
			echo "Fatal Error: filename must be lowercase";
			trigger_error($_SERVER["SCRIPT_FILENAME"], E_USER_ERROR);
			self::noSend();
			exit;
		}

		// 后缀必须为 .php 文件
		if ($aPath["extension"] !== "php") {
			echo "Fatal Error: not .php file ".htmlFilter($_SERVER["SCRIPT_FILENAME"]);
			trigger_error("not .php file ".$_SERVER["SCRIPT_FILENAME"], E_USER_ERROR);
			self::noSend();
			exit;
		}

		// 针对次级扩展名的额外动作，如 .inc.php / .ajax.php ，还可不断扩展
		$iDotPost = strrpos($aPath["filename"], ".");
		if ($iDotPost !== FALSE) {
			$sSubExt = substr($aPath["filename"], $iDotPost + 1);
			switch ($sSubExt) {
				case "ajax" :
					self::setAjax();
					break;
				case "inc" :
					echo "can't run a .inc.php file";
					self::noSend();
					exit;
				default :
					break;
			}
		}
		return TRUE;
	}

	public static function callTemplate() {

		if (self::_errorCheck() || PHP_SAPI === "cli") {
			return;
		}

		// 如果 www 目录的文件已有输出，则 template 页不起作用
		if (headers_sent($sScript, $iLine)) {
			// trigger_error("Headers already sent in ".$sScript." on line ".$iLine);
			return;
		}

		$aTango =& self::$aData;

		if (self::$_bEscapeHTML) {
			$aTango = self::escapeHTML($aTango);
		}

		ob_start();

		if (self::$_bAjax) {
			echo json_encode($aTango);
			return ob_get_clean();
		}

		list($aPath, $sPathFromBase) = self::getScriptPathInfo();

		if (strpos($sPathFromBase, "/admin") === 0) {
			self::$_bAdmin = TRUE;
			if (!LOCAL_ACCESS) {
				header("Not Found", TRUE, 404);
				trigger_error($_SERVER["REMOTE_ADDR"]);
				return FALSE;
			}
		}

		$sTemplateDir = self::getDirRoot()."/tpl";

		$sTemplate      = $sTemplateDir.$sPathFromBase;
		$sTemplateError = $sTemplateDir.dirname($sPathFromBase)."/".$aPath["filename"].".error.php";

		chdir(self::getDirRoot()."/tpl");

//		require_once("common.php");

		if (!empty($aTango["error"])) {

			if (is_file($sTemplateError)) {
				require_once($sTemplateError);
			} else {
				require_once($sTemplateDir."/error.php");
			}

		} else if (is_file($sTemplate)) {

			require_once($sTemplate);

		} else {

			echo "no template";
			if (defined("TANGO_DEBUG") && TANGO_DEBUG) {
				dump($aTango);
			}
		}

		$sBody = ob_get_clean();

		if (!self::$_bTemplate) {
			return $sBody;
		}

		if (
			strpos($sBody, 'class="monospace"')
			|| strpos($sBody, 'class="code"')
			|| strpos($sBody, 'class="php"')
		) {
			PageHeader::setWebfont();
		}

		ob_start();
		require_once("header.php");
		$sHeader = ob_get_clean();

		ob_start();
		require_once("footer.php");
		$sFooter = ob_get_clean();

		$sBody = $sHeader.$sBody.$sFooter;

		if (!TANGO_DEV && !self::$_bAdmin) {
			$sBody = preg_replace("/\n\t+/", "", $sBody);
			$sBody = str_replace("\n", "", $sBody);
		}

		return $sBody;
	}

	public static function send() {

		if (!self::$_bSend) {
			return;
		}
		self::noSend();

		if (self::_errorCheck()) {
			return;
		}

		if (!$sContent = self::callTemplate()) {
			return;
		}

		if (PHP_SAPI == "cli") {
			echo $sContent;
			return;
		}

		if (self::$_bAjax) {
			header("Cache-Control: no-cache, must-revalidate");
		} else {
			$sETag = "\"".md5($sContent)."\"";
			header("ETag: ".$sETag);

			settype($_SERVER["HTTP_IF_NONE_MATCH"], "string");
			if ($_SERVER["HTTP_IF_NONE_MATCH"] == $sETag) {
			    header("HTTP/1.1 304 Not Modified");
			    exit;
			}
		}

		$iContentLength = strlen($sContent);

		if ($iContentLength > 3500 && isset($_SERVER["HTTP_ACCEPT_ENCODING"])) {
			$aAcceptEncoding = explode(",", $_SERVER["HTTP_ACCEPT_ENCODING"]);
			$aAcceptEncoding = array_map("trim", $aAcceptEncoding);

			if (in_array("deflate", $aAcceptEncoding)) {

				header("Content-Encoding: deflate");
				$sContent = gzdeflate($sContent);

			} else if (in_array("gzip", $aAcceptEncoding)) {

				header("Content-Encoding: gzip");
				$sContent = gzencode($sContent);
			}
		}

		header("Content-Length: ".strlen($sContent));

		DBz::disable();
		echo $sContent;
	}

	public static function escapeHTML($aArg) {
		foreach ($aArg as $mKey => $mValue) {
			if (is_array($mValue)) {
				$aArg[$mKey] = self::escapeHTML($mValue);
			} else if (is_string($mValue)) {
				$aArg[$mKey] = htmlFilter($mValue);
			}
		}
		return $aArg;
	}

	public static function escapeHTMLIfNeed($aArg) {
		if (Page::isSent()) {
			$aArg = self::escapeHTML($aArg);
		}
		return $aArg;
	}

	protected static function _errorCheck() {
		$aError = error_get_last();
		if (empty($aError) || $aError["type"] == E_NOTICE || $aError["type"] == E_USER_NOTICE) {
			return FALSE;
		}
		return TRUE;
	}
}
