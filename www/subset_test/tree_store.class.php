<?php

/*
	author: Zheng Kai <zhengkai@gmail.com>
 */

class TreeStore {
	public $aList = array();
	public $iCount = 0;

	public function pick($aMatch) {
		$this->iCount++;
		$sExpr = trim($aMatch[1]);
		if (preg_match("/^_(\\d+)$/", $sExpr, $aSubMatch)) {
			$iSub = $aSubMatch["1"];
			$sExpr = $this->aList[$iSub];
			unset($this->aList[$iSub]);
		}
		$this->aList[$this->iCount] = $sExpr;
		$iCount = $this->iCount;
		return "_".$iCount;
	}

	public function __construct($sQuery) {
		$sQuery = str_replace(array("(", ")", "=", ">", "<"), array(" ( ", " ) ", " = ", " > ", " < "), $sQuery);
		$sQuery = preg_replace("/\\s+/", " ", $sQuery);
		$sQuery = str_ireplace(array(" AND ", " OR "), array(" AND ", " OR "), $sQuery);
		$sQuery = "( ".trim($sQuery)." )";
		while (TRUE) {
			$iCount = 0;

			// 从最里层的括号开始，逐级放大，从树的最末端开始生成结构，存到一维数组里

			$sQuery = preg_replace_callback("/\\(([^\\(\\)]+)\\)/", array($this, "pick"), $sQuery, -1, $iCount);
			if ($iCount == 0) {
				break;
			}
		}
		$this->iCount = 0;
	}

	public function get() {
		$sLine = end($this->aList);
		debugShow($this->aList);
		$aBox = $this->parseStr($sLine);
		debugShow($aBox);
		if (is_string($aBox)) {
			$aBox = array("AND" => array($aBox));
		}
		$aBox = $this->combine($aBox);
		debugShow($aBox);
		if (key($aBox) == "AND") {
			$aBox = array($aBox);
		} else {
			$aBox = current($aBox);
		}

		$aBox = array_map(array($this, "trans"), $aBox);
		debugShow($aBox);
		return $aBox;
	}

	public function trans($mData) {
		$aQuery = is_string($mData) ? array($mData) : current($mData);

		$aWhere = array();
		foreach ($aQuery as $sExpression) {
			$aExpression = explode(" ", $sExpression);
			if (count($aExpression) !== 3) {
				echo "\n\nError Expression: ", $sExpression."\n\n\n";
				exit;
			}
			if (!isset($aWhere[$aExpression[0]])) {
				$aWhere[$aExpression[0]] = array();
			}
			if (!isset($aWhere[$aExpression[0]][$aExpression[1]])) {
				$aWhere[$aExpression[0]][$aExpression[1]] = array();
			}
			$aWhere[$aExpression[0]][$aExpression[1]][$aExpression[2]] = TRUE;
		}

		if (!$aWhere = $this->verifyExpr($aWhere)) {
			echo "\n\nError Expression: ", $sExpression."\n\n\n";
			exit;
		}

		return $aWhere;
	}

	function verifyExpr($aExpr) {

		foreach ($aExpr as $sKey => $aSub) {
			if (isset($aSub[">"])) {
				$aSub[">"] = min(array_keys($aSub[">"]));
			}
			if (isset($aSub["<"])) {
				$aSub["<"] = max(array_keys($aSub["<"]));
			}
			if (isset($aSub["<"]) && isset($aSub[">"])) {
				if ($aSub["<"] <= $aSub[">"]) {
					return FALSE; // a > 5 and a < 5 这类无解
				}
			}
			if (isset($aSub["="])) {
				if (count($aSub["="]) > 1) {
					return FALSE; // a = 1 and a = 2 这类无解
				}
				$iOnly = key($aSub["="]);
				if ((isset($aSub[">"]) && $iOnly <= $aSub[">"])
					|| (isset($aSub["<"]) && $iOnly >= $aSub["<"])) {
					return FALSE; // a = 1 and a > 2 这类无解
				}
				unset($aSub["<"]);
				unset($aSub[">"]);
			}
			$aExpr[$sKey] = $aSub;
		}

		return $aExpr;
	}

	protected function _hasOR($aNode) {
		foreach ($aNode as $aSub) {
			if (is_array($aSub) && key($aSub) == "OR") {
				return TRUE;
			}
		}
		return FALSE;
	}

	function convertLevel($mInput) {

		// 判断是具体条件 (如 a > 15) 还是条件集合 (AND => array(xxx))
		// 都转为一维数组，用于合并

		if (is_string($mInput)) {
			return array($mInput);
		} else {
			return current($mInput);
		}
	}

	public function combine($aNode) {

		// 最核心的功能，负责“and”“or”的合并、或将结果集改写以 or 连接的 and 结点

		$sType = key($aNode);
		$aChild = current($aNode);
		$aReturn = array();

		if ($sType == "AND" && $this->_hasOR($aChild)) {
			$bHasOR = TRUE;
			usort($aChild, function($aRow) {
				if (is_string($aRow)) {
					return 0;
				}
				$sType = key($aRow);
				return $sType == "OR";

			});
			$aRebuild = array();
		} else {
			$bHasOR = FALSE;
		}

		foreach ($aChild as $aRow) {
			if (is_string($aRow)) {
				$aReturn[] = $aRow;
				continue;
			}
			$aRow = $this->combine($aRow);
			$sRowType = key($aRow);
			if ($sType === $sRowType) {
				$aReturn = array_merge($aReturn, current($aRow));
			} else if ($sType == "OR") {
				$aReturn = array_merge($aReturn, array($aRow));
			} else if ($sType == "AND") {
				$aRow = current($aRow);
				if (empty($aRebuild)) {
					if (empty($aReturn)) {
						$aRebuild = $aRow;
						continue;
					} else {
						$aRebuild = array(array("AND" => $aReturn));
					}
				}

				// 将 (a | b) & (c | d) 改为 (a & c) | (a & d) | (b & c) | (b & d)

				$aBox = array();
				foreach ($aRebuild as $aSub) {
					$aSub = $this->convertLevel($aSub);
					foreach ($aRow as $aRowSub) {
						$aRowSub = $this->convertLevel($aRowSub);

						$aBox[] = array("AND" => array_merge($aSub, $aRowSub));
					}
				}
				$aRebuild = $aBox;
			}
		}
		if ($bHasOR) {
			return array("OR" => $aRebuild);
		}
		return array($sType => $aReturn);
	}


	public function parseStr($sLine) {
		foreach (array("AND", "OR") as $sType) { // 处理类似 a and b or c 这类字符串
			if (strpos($sLine, " ".$sType." ")) {
				$aLine = explode(" ".$sType." ", $sLine);
				$aLine = array_map(array($this, __FUNCTION__), $aLine);
				return array($sType => $aLine);
			}
		}
		if (substr($sLine, 0, 1) == "_") { // 类似 _15 这类别名，还原 __construct 时用 pack 存到一维数组里的别名
			$iKey = substr($sLine, 1);
			$aReturn = $this->parseStr($this->aList[$iKey]);
			unset($this->aList[$iKey]);
			return $aReturn;
		}
		return $sLine;
	}
}
