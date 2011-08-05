<?php
class BlogMenu {

	protected static $_aCategory = array(
		7 => array(
			"name" => "代码 Coding",
		),
		11 => array(
			"name" => "铂 Platinum",
		),
		1 => array(
			"name" => "思考 Thinking",
			"hide" => TRUE,
		),
		2 => array(
			"name" => "游戏 Game",
		),
		3 => array(
			"name" => "科幻 Science Fiction",
			"hide" => TRUE,
		),
		4 => array(
			"name" => "网文 Internet",
		),
		5 => array(
			"name" => "历史 History",
		),
		6 => array(
			"name" => "琐事 Trivia",
		),
		9 => array(
			"name" => "开发记录 Blog",
			"hide" => TRUE,
		),
		12 => array(
			"name" => "记事本 Notebook",
		),
		13 => array(
			"name" => "说 Tweets",
		),
	);

	protected static $_sTitle;

	protected static $_iSelectCategory = 0;

	public static function getCategory($iCategory = NULL) {
		if ($iCategory === NULL) {
			return self::$_aCategory;
		}
		return ($sName =& self::$_aCategory[$iCategory]) ?: FALSE;
	}

	public static function setSelectedCategory($iCategory) {
		self::$_iSelectCategory = $iCategory;
		return TRUE;
	}

	public static function getSelectedCategory() {
		return self::$_iSelectCategory;
	}

	public static function setTitle($sTitle) {
		self::$_sTitle = $sTitle;
		return TRUE;
	}

	public static function getTitle() {
		return self::$_sTitle ?: "Soulogic";
	}

}
