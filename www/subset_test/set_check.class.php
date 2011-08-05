<?php

/*
	author: Zheng Kai <zhengkai@gmail.com>
 */

class SetCheck {

	static $sMessage = "";

	static function isSubset($aA, $aB) {

		// echo "\n\n[ return A = ".print_r($aA, TRUE)." ]\n\n";
		// echo "\n\n[ return B = ".print_r($aB, TRUE)." ]\n\n";

		debugShow($aA);

		// 如果 B 的所有 OR 集都是 A 的某 OR 集的子集，则 B 是 A 的自己
		foreach ($aB as $aBSub) {
			$bTest = FALSE;
			foreach ($aA as $aASub) {
				if (self::checkSubset($aASub, $aBSub)) {
					$bTest = TRUE;
					break;
				}
			}
			if (!$bTest) {
				// print_r($aA);
				// print_r($aB);
				return FALSE;
			}
		}
		return TRUE;
	}

	static function checkSubset($aParent, $aSub) {

		// 突然死亡法，任何不符都直接 return FALSE

		$bSubset = TRUE;
		self::$sMessage = "";

		foreach ($aSub as $sField => $aExpr) {

			// 父没某 field 做规定，则子怎么规定都是正确的

			if (!isset($aParent[$sField])) {
				continue;
			}
			$aCheck = $aParent[$sField];
			unset($aParent[$sField]);

			// 父集 A = 3 或者 A < 5 可以覆盖子集的 A = 3
			if (isset($aExpr["="])) {
				$iSingle = key($aExpr["="]);

				$bTest = FALSE;
				do {
					if (isset($aCheck["="][$iSingle])) {
						$bTest = TRUE;
						break;
					}
					if (
						(!isset($aCheck[">"]) || $iSingle > $aCheck[">"])
						&& (!isset($aCheck["<"]) || $iSingle < $aCheck["<"])
						) {
						$bTest = TRUE;
						break;
					}
				} while (FALSE);
				if (!$bTest) {
					self::$sMessage = "field \"".$sField."\" = ".$iSingle;

					return FALSE;
				}
				continue;
			}

			// 上下边界，如果父没设，也就是无限，则子怎么来都行
			// 如果有边界，分两种情况 子无限 或者 子边界大于父边 ，则判定不是子集

			if (isset($aCheck[">"])
				&&
				( !isset($aExpr[">"])
					||
					($aCheck[">"] > $aExpr[">"])
				)
			) {
				self::$sMessage = "field \"".$sField."\" > ".(isset($aExpr[">"]) ? $aExpr[">"] : "unlimited");
				return FALSE;
			}

			if (isset($aCheck["<"])
				&&
				( !isset($aExpr["<"])
					||
					($aCheck["<"] < $aExpr["<"])
				)
			) {
				self::$sMessage = "field \"".$sField."\" < ".(isset($aExpr["<"]) ? $aExpr["<"] : "unlimited");
				return FALSE;
			}
		}
		if (!empty($aParent)) {
			self::$sMessage = "no some fields";
			return FALSE;
		}

		return TRUE;
	}

}
