<?php
// $Id: filter.class.php 61 2010-04-26 10:15:32Z zhengkai $

/**
 *  过滤输入参数，保障安全
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
 *
 */

require_once(__DIR__."/utility.func.php");

class Filter {

	/**
	 *  工作方法
	 *
     *  @parm $sMethod 字符串 POST 或 GET，如果是 POST 则顺便检查 referer，要求 referer 和目标地址都在同一个域名下，否则报错中断
     *  @parm $aRule 数组，包含要获取的参数的 key 和实际类型，并进行转换
     *      字符串会被
     *      需要注意默认的 integer 不允许有负值，否则请使用 signedInteger
	 */
	public static function run($sMethod = "GET", $aRule = array()) {
		$sMethod = strtoupper($sMethod);
		if ($sMethod == "POST") {
			$aParm =& $_POST;
			$sRefHost = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
			list($sThisHost) = explode(":", $_SERVER["HTTP_HOST"], 2);
			if ($sRefHost !== $sThisHost) {
				static::_ErrorReferer($sRefHost);
			}
		} else {
			$aParm =& $_GET;
		}

		foreach ($aRule as $sKey => $sType) {
			$mNow =& $aParm[$sKey];
			switch ($sType) {
				case "str":
				case "string":
					settype($mNow, "string");
					if (strlen($mNow) > 4096) {
						$mNow = "";
						break;
					}
				case "longStr":
				case "longString":
					settype($mNow, "string");
					$mNow = str_replace(array("\r\n", "\r"), "\n", $mNow);
					$mNow = iconv("UTF-8", "UTF-8//IGNORE", trim($mNow));
					break;
				case "bin":
				case "binary":
					settype($mNow, "string");
					break;
				case "int":
				case "integer":
					settype($mNow, $sType);
					if ($mNow <= 0) {
						$mNow = 0;
					}
					break;
				case "signedInt":
				case "signedInteger":
					settype($mNow, "integer");
					break;
				case "bool":
				case "boolean":
					if (is_string($mNow)) {
						$mNow = trim($mNow);
					}
					$mNow = !empty($mNow);
					break;
				case "ip":
					$sIPPattern = "/^[\d\.]{7,15}$/"; // IP 内容是否合法
					if (preg_match($sIPPattern, $mNow) < 1) {
						$mNow = "0";
					}
					break;
				case "hex":
					settype($mNow, "string");
					if (!preg_match("/^[0-9a-z]{0,100}$/", $mNow)) {
						$mNow = "";
					}
					break;
				default:
					settype($mNow, $sType);
					break;
			}
		}
	}

	protected static function _ErrorReferer($sRefHost) {
		// 这里可以多做点处理，汇报攻击者 ip / referer 什么的
		trigger_error("Class Filter: POST referer error [ ".$sRefHost." ]");
		exit;
	}
}
