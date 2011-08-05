<?php
// $Id: pagenumber.php 61 2010-04-26 10:15:32Z zhengkai $

class PageNumber {

	protected $_iNumberPerPageDefault = 30;

	protected $_iCount;
	protected $_iNumberPerPage;
	protected $_iPageNow;
	protected $_iPageMax;
	protected $_aPageSlice;
	protected $_bReverse;

	public function __construct($iCount, $iNumberPerPage = NULL, $iPageNow = NULL) {
		if ($iPageNow == NULL) {
			if (array_key_exists("page", $_GET)) {
				$iPageNow = $_GET["page"];
			} else {
				$iPageNow = 0;
			}
		}
		if ($iNumberPerPage === NULL) {
			$iNumberPerPage = $this->_iNumberPerPageDefault;
		} else if ($iNumberPerPage < 1) {
			$iNumberPerPage = 1;
		}
		$iPageMax = ceil($iCount < 1 ? 1 : $iCount / $iNumberPerPage) - 1;
		if ($iPageNow > $iPageMax) {
			$iPageNow = $iPageMax;
		} else if ($iPageNow < 0) {
			$iPageNow = 0;
		}

		$this->_iCount         = $iCount;
		$this->_iNumberPerPage = $iNumberPerPage;
		$this->_iPageMax       = $iPageMax;

		$this->setPageNow($iPageNow);
	}

	public function setPageNow($iPageNow) {
		$this->_iPageNow       = $iPageNow;
	}

	public function getPageNow() {
		return $this->_iPageNow;
	}

	public function slice($iOffset = 2, $iBorder = 2) {

		$iKeep = $iOffset + $iBorder;
		$iPageMax = $this->_iPageMax;

		$aBegin = array();
		if ($this->_iPageNow < $iKeep) {
			$aBegin = range(0, $iKeep + $iOffset);
		} else if ($iBorder > 0) {
			$aBegin = range(0, $iBorder - 1);
		}
		$aEnd = array();
		if ($this->_iPageNow > ($iPageMax - $iKeep)) {
			$aEnd = range($iPageMax - $iKeep - $iOffset, $iPageMax);
		} else if ($iBorder > 0) {
			$aEnd = range($iPageMax - $iBorder + 1, $iPageMax);
		}

		$aBody = range($this->_iPageNow - $iOffset, $this->_iPageNow + $iOffset);
		$aSlice = array_merge($aBegin, $aEnd, $aBody);
		$aSlice = array_unique($aSlice);
		$aSlice = array_filter($aSlice, function($iPage) use ($iPageMax) {
			if ($iPage < 0) {
				return FALSE;
			}
			if ($iPage > $iPageMax) {
				return FALSE;
			}
			return TRUE;
		});

		sort($aSlice);
		$this->_aPageSlice = $aSlice;

		return $aSlice;
	}

	public function getLimit($bOptimize = FALSE, $bOrderASC = FALSE) {

		$sLimit = " ";

		$sOrder = $bOrderASC ? "ASC" : "DESC";
		$sDeOrder = $bOrderASC ? "DESC" : "ASC";

		if ($this->_iCount < $this->_iNumberPerPage) { // 如果总数不到一页，直接短路处理
			$sLimit .= $sOrder." LIMIT ".$this->_iNumberPerPage;
			return $sLimit;
		}

		$iOffset = $this->_iPageNow * $this->_iNumberPerPage;
		$iRowCount = $this->_iNumberPerPage;

		if (!$bOptimize || $this->_iCount < 500 || ($this->_iPageNow <= $this->_iPageMax / 2)) {
			$sLimit .= $sOrder;
			$bReverse = FALSE;
		} else {
			$iOffset = $this->_iCount - $iOffset - $this->_iNumberPerPage;
			if ($iOffset < 0) {
				$iRowCount = $this->_iNumberPerPage + $iOffset;
				$iOffset = 0;
			}
			$sLimit .= $sDeOrder;
			$this->_bReverse = TRUE;
		}
		$sLimit .= " LIMIT ".$iOffset.", ".$iRowCount;

		return $sLimit;
	}

	public function transList($aList) {
		if ($this->_bReverse) {
			$aList = array_reverse($aList, TRUE);
		}
		return $aList;
	}

	public function getHTML($aTpl = array()) {
		if (is_string($aTpl)) {
			$aTpl = array("href" => $aTpl);
		}

		$aTplDefault = array(
			"outer"   => "<p class=\"pageNumber\">%s</p>",
			"outerWhenNull" => "",
			"href"    => "?page=%d",
			"link"    => "<a href=\"%s\">%s</a>",
			"current" => "<span class=\"current\">%s</span>",
			"dot"     => "<span class=\"dot\">...</span>",
			"number"  => "%02d",
		);
		$aTpl += $aTplDefault;

		if (!empty($aTpl["outerWhenNull"]) && empty($this->_iPageMax)) {
			return "";
		}

		if (empty($this->_aPageSlice)) {
			$this->slice();
		}
		$aSlice = $this->_aPageSlice;

		$sHTML = "";
		$iPrevNumber = current($aSlice);
		foreach ($aSlice as $iNumber) {
			if (1 < $iNumber - $iPrevNumber) {
				$sHTML .= $aTpl["dot"];
			}
			$sNumber = sprintf($aTpl["number"], $iNumber + 1);
			if ($iNumber == $this->_iPageNow) {
				$sHTML .= sprintf($aTpl["current"], $sNumber);
			} else {
				$sHref = sprintf($aTpl["href"], $iNumber);
				$sHTML .= sprintf($aTpl["link"], $sHref, $sNumber);
			}
			$iPrevNumber = $iNumber;
		}
		$sHTML = sprintf($aTpl[empty($this->_iPageMax) ? "outerWhenNull" : "outer"], $sHTML);
		return $sHTML;
	}
}