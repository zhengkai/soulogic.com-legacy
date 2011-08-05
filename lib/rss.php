<?PHP

/*
$oRSS = new RSS;
$oRSS->setInfo("title", "测试 RSS");
$oRSS->setInfo("language", "zh-cn");

$oRSS->addItem(array("title" => "1", "link" => "2", "description" => "3"));
$oRSS->addItem(array("title" => "1", "link" => "2", "description" => "3"));
$oRSS->addItem(array("title" => "1", "link" => "2", "description" => "3", "pubDate" => "1212422312"));

$oRSS->clearAllItem();

$oRSS->addItem(array("title" => "1", "link" => "2", "description" => "3", "pubDate" => "1112422312"));

$oRSS->sendHeader();
echo $oRSS->make();
 */

class RSS {

/*
	Optional channel elements
		language, copyright, managingEditor, webMaster, pubDate, lastBuildDate,
		category, generator, docs, cloud, ttl, image, rating, textInput,
		skipHours, skipDays

	Elements of <item>
		title, link, description, author, category, comments, enclosure, guid,
		pubDate, source
 */

	protected $sVersion = "Beta 07.03.01";

	protected $iSizeLimit = 500000;
	protected $bSizeLimit = true;

	protected $aChannelKey = array(
		"title", "link", "description", "generator",
		"language", "copyright", "managingEditor", "webMaster", "pubDate",
		"lastBuildDate", "category", "docs", "cloud", "ttl", "image",
		"rating", "textInput", "skipHours", "skipDays"
	);

	protected $aItemKey = array(
		"title", "link", "description"
	//	"author", "category", "comments", "enclosure", "guid", "pubDate",
	//	"source"
	);

	protected $aChannel = array(
		"title" => "Undefined RSS Title",
		"link" => "http://example.com/",
		"description" => "Empty Description",
		"generator" => "Soulogic RSSGenerator",
		"lastBuildDate" => "1"
	);

	protected $aItem = array();

	protected $aItemIfNull = array(
		array(
			"title" => "Undefined Item Title",
			"link" => "http://example.com/",
			"description" => "Empty Item Description"
		)
	);

	protected $aRssNs = array(
		"dc" => "http://purl.org/dc/elements/1.1/"
	);

	protected function xmlNode($sName, $sValue, $bXmlOutTag = true, $bCdata = false) {

		settype($sValue, "string");
		settype($sName, "string");

		$sName = $this->xmlOut($sName);
		$sReturn = "";
		if (strlen($sValue) == 0) {
			$sReturn .= "<".$sName." />";
		} else {
			if ($bXmlOutTag) {
				$sValue = $this->xmlOut($sValue);
			}
			$sReturn .= "<".$sName.">";
			if ($bCdata or strstr($sValue, "\n")) { // 如果是多行的值则使用 CDATA
				$sReturn .= "<![CDATA[".$sValue."]]>";
			} else {
				$sReturn .= $sValue;
			}
			$sReturn .= "</".$sName.">";
		}
		return $sReturn;
	}

	protected function xmlOut($string) {
		$string = str_replace("&", "&#38;", $string);
		$string = str_replace("<", "&#60;", $string);
		$string = str_replace(">", "&#62;", $string);
		$string = str_replace("]", "&#93;", $string);
		return $string;
	}

	public function make() { // 根据当前的设置输出 RSS XML

		$sXML = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
		$sXML .= "<rss version=\"2.0\"";

		foreach ($this->aRssNs as $sKey => $sValue) {
			$sXML .= " xmlns:".$sKey."=\"".$sValue."\"";
		}
		$sXML .= ">\n<channel>\n";

		$aChannel = $this->aChannel;
		if (!empty($aChannel["image"])) {
			$aImage = $aChannel["image"];
			unset($aChannel["image"]);
		}
		$aChannel["generator"] .= " ".$this->sVersion;
		$aChannel["lastBuildDate"] = date("r");
		foreach ($aChannel as $sKey => $sValue) {
			$sXML .= $this->xmlNode($sKey, $sValue)."\n";
		}
		if (isset($aImage)) {
			$sXML .= "<image>";
			foreach ($aImage as $sKey => $sValue) {
				$sXML .= $this->xmlNode($sKey, $sValue)."";
			}
			$sXML .= "</image>\n";
		}

		$aItem = empty($this->aItem) ? $this->aItemIfNull : $this->aItem;
		foreach ($aItem as $aItemSub) {
			$sXMLItem = "<item>";
			if (isset($aItemSub["pubDate"])&&(intval($aItemSub["pubDate"]) > 0)) {
				$aItemSub["pubDate"] = date("r", $aItemSub["pubDate"]);
			}

			foreach ($aItemSub as $sKey => $sValue) {
				if ($sKey == "description") {
					$sXMLItem .= $this->xmlNode($sKey, $sValue, false, true);
				} else {
					$sXMLItem .= $this->xmlNode($sKey, $sValue);
				}
			}
			$sXMLItem .= "</item>\n";
			if (!$this->bSizeLimit||(strlen($sXMLItem) + strlen($sXML) < $this->iSizeLimit)) {
				$sXML .= $sXMLItem;
			}
		}

		$sXML .= "</channel></rss>";
		return $sXML;
	}

	public function setInfo($sKey, $mixValue) {
		if (!in_array($sKey, $this->aChannelKey)) {
			return false;
		}
		if ($sKey == "image") {
			if (is_string($mixValue)) {
				$mixValue = array(
					"title" => $this->aChannel["title"],
					"link"  => $this->aChannel["link"],
					"url"   => $mixValue
				);
			}
			foreach (array("title", "link", "url") as $sRequiredKey) {
				if (empty($mixValue[$sRequiredKey])) {
					return false;
				}
			}
		}
		$this->aChannel[$sKey] = $mixValue;
	}

	public function addItem($aItemSub) {
		foreach ($this->aItemKey as $sKey) {
			if (!isset($aItemSub[$sKey])) {
				return false;
			}
		}
		$this->aItem[] = $aItemSub;
	}

	public function clearAllItem() {
		$this->aItem = array();
	}

	public function sendHeader() {
		header("Content-Type: application/rss+xml");
	}

	public function setNs($aRssNs) {
		$this->aRssNs = $aRssNs;
	}
}
