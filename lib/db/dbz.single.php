<?php
class DBzSingle extends PDO {

	public $sName;
	protected $_aColumnNeedConvert;

	protected static $_aTypeNeedConvert = array(
		"DATE"      => "strtotime",
		"DATETIME"  => "strtotime",
		"TIMESTAMP" => "strtotime",
		"LONG"      => "intval",
		"LONGLONG"  => "intval",
	);

	public function __construct($aServer) {

		$this->sName = $aServer["name"];

		if (!empty($aServer["host"])) {
			$aHost = explode(":", $aServer["host"], 2);
			if (!isset($aHost[1])) {
				$aHost[1] = 3306;
			}
			$sServer = "host=".$aHost[0].";port=".$aHost[1];
		} else {
			$sServer = "unix_socket=".$aServer["unix_socket"];
		}

		parent::__construct(
			"mysql:".$sServer.";dbname=".$aServer["database"],
			$aServer["user"],
			$aServer["password"],
			array(
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
			)
		);
	}

	protected function _ColumnConvertScan($oResult) {

		// 转换变量类型 确定要转换的字段
		$iColumnCount = $oResult->columnCount();
		$aColumnNeedConvert = array();
		foreach (range(0, $iColumnCount - 1) as $iColumn) {
			$aColumnMeta = $oResult->getColumnMeta($iColumn);
			$sType = $sTmp =& $aColumnMeta["native_type"] ?: "UNKNOWN";
			if (array_key_exists($sType, self::$_aTypeNeedConvert)) {
				$aColumnNeedConvert[$aColumnMeta["name"]] = $sType;
			}
		}
		$this->_aColumnNeedConvert = $aColumnNeedConvert;
	}

	protected function _ColumnConvertDo($aRow) {

		// 转换变量类型 执行转换
		foreach ($this->_aColumnNeedConvert as $sColumnKey => $sType) {
			$sFunction = self::$_aTypeNeedConvert[$sType];
			$aRow[$sColumnKey] = $sFunction($aRow[$sColumnKey]);
		}
		// $this->_aColumnNeedConvert = NULL;
		return $aRow;
	}

	public function query() {
		$aArg = func_get_args();
		if (DBz::debug()) {
			$fTime = microtime(TRUE);
		}
		$oResult = call_user_func_array(array("parent", "query"), $aArg);
		if (DBz::debug()) {
			$fTime = microtime(TRUE) - $fTime;
			$this->_errorLog($aArg[0], $fTime);
		}
		return $oResult;
	}

	public function exec($sQuery) {
		$aArg = func_get_args();
		if (DBz::debug()) {
			$fTime = microtime(TRUE);
		}
		$iAffected = parent::exec($sQuery);
		if (DBz::debug()) {
			$fTime = microtime(TRUE) - $fTime;
			$this->_errorLog($aArg[0], $fTime);
		}
		return $iAffected;
	}

	protected function _send() {
	}

	protected function _errorLog($sQuery, $fTime) {
		// trigger_error("sql: ".$sQuery);

		$aInfo = array(
			"server" => $this->sName,
			"query" => strlen($sQuery) > 1000 ? mb_substr($sQuery, 0, 1000) : $sQuery,
			"time" => $fTime,
		);
		if ($this->errorCode() > 0) {
			$aError = $this->errorInfo();
			$aInfo["error"] = array(
				"code" => $aError[1],
				"message" => $aError[2],
			);
		}
		DBz::addDebugLog($aInfo);
	}

	public function getInsertID($sQuery) {
		if (!$this->exec($sQuery)) {
			return;
		}
		return $this->lastInsertId();
	}

	public function getAll($sQuery, $bByKey = TRUE) {

		if (!$oResult = $this->query($sQuery)) {
			return FALSE;
		}

		$aData = $oResult->fetchAll(PDO::FETCH_ASSOC);
		if (empty($aData)) {
			return $aData;
		}

		self::_ColumnConvertScan($oResult);

		$iColumnCount = $oResult->columnCount();
		if ($bByKey && $iColumnCount == 1) {
			$bByKey = FALSE;
		}
		if ($bByKey) {
			$aKey = array();
		}
		$bPeelArray = ($iColumnCount == 1 + $bByKey);
		foreach ($aData as $iRowKey => $aRow) {

			$aRow = self::_ColumnConvertDo($aRow);

			// 取第一个字段为 key，替代原来的顺序数字
			if ($bByKey) {
				$aKey[$iRowKey] = array_shift($aRow);
			}

			// 如果只有一列，则不再需要原来的 array 套了
			if ($bPeelArray) {
				$aRow = current($aRow);
			}
			$aData[$iRowKey] = $aRow;
		}

		if ($bByKey) {
			$aData = array_combine($aKey, $aData);
		}

		return $aData;
	}

	// 只取第一行
	public function getRow($sQuery) {

		if (!$oResult = $this->query($sQuery)) {
			return FALSE;
		}

		$aRow = $oResult->fetch(PDO::FETCH_ASSOC);
		if (!empty($aRow)) {
			self::_ColumnConvertScan($oResult);
			$aRow = self::_ColumnConvertDo($aRow);
		}

		return $aRow;
	}

	// 只取第一行的第一个字段
	public function getSingle($sQuery) {

		if (!$aRow = $this->getRow($sQuery)) {
			return FALSE;
		}

		$aRow = current($aRow);

		return $aRow;
	}
}
