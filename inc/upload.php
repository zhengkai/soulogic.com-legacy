<?php

class Upload {

	protected $_iError;
	protected $_aFile;

	/*
		初始化需要提供文件 post 时的名称

		如 post 表单里为 <input type="file" name="a"，则 new Upload("a")
		如 post 表单里为 <input type="file" name="b[3]"，则 new Upload("b", 3)

	 */

	public function __construct($sName, $iOrder = NULL) {

		if (!isset($_FILES[$sName])) {
			$this->_iError = 1;
			return FALSE;
		}
		$aFile = $_FILES[$sName];

		if (isset($iOrder)) {
			if (!isset($aFile["error"][$iOrder])) {
				$this->_iError = 2;
				return FALSE;
			}
			$aFile = array_map(function($aRow) use ($iOrder) {
				return $aRow[$iOrder];
			}, $aFile);
		}

		if (!isset($aFile["error"])) {
			$this->_iError = 3;
			return FALSE;
		}

		$this->_aFile = $aFile;
	}

	public function isSuccess() {
		return ($this->_iError === NULL) && ($this->_aFile["error"] === UPLOAD_ERR_OK);
	}

	public function get() {
		if (!$this->isSuccess()) {
			return FALSE;
		}

		$oDB = DBz::getInstance("Blog");
		$sQuery = "INSERT INTO files "
			."SET name = \"".$this->_aFile["name"]."\", "
			."filesize = ".$this->_aFile["size"].", "
			."header = \"".$this->_aFile["type"]."\", "
			."content = \"".addslashes(file_get_contents($this->_aFile["tmp_name"]))."\", "
			."date_c = NOW()";
		$iFile = $oDB->getInsertID($sQuery);

		if (empty($iFile)) {
			return FALSE;
		}
		return $iFile;
	}
}
