<?php
function old_convert($string, $p_tag = true) {

	$url_style = 2;

	$string = str_replace("\r\n", "\n", $string);
	$string = str_replace("\r", "\n", $string);
	// $string = str_replace("\t", "    ", $string);

//    $string = preg_replace("/&(^#[0-9]{1,3};)/siU", "&#60;\\1", $string);

	$html_search = array(
//		"&",
		"<",
		">",
		"\"",
		'\''
		);
	$html_replace = array(
//		"&#38;",
		"&#60;",
		"&#62;",
		"&#34;",
		"&#39;"
		);
	$string = str_replace($html_search, $html_replace, $string);

	// 转义标签
	$ubb_code = array("[hr]", "[url]", "[url", "[/url]", "[quote]", "[/quote]",
		"[img", "[/img]", "[file=", "[archives=", "[phpcode]", "[/phpcode]",
		"[code]", "[/code]", "[color=", "[/color]", "[size=", "[/size]",
		"[del]", "[/del]", "[face=", "[/face]", "[b]", "[/b]", "[u]", "[/u]",
		"[center]", "[/center]");

	foreach ($ubb_code as $value) {
		$value2 = str_replace("[", "&#91;", $value);
		$string = str_replace("\\".$value, $value2, $string);
	}

	// 段落起始/结束标志
	if ($p_tag) {
		$paragraph_begin = "<p>";
		$paragraph_end = "</p>";
	} else {
		$paragraph_begin = "";
		$paragraph_end = "";
	}

	// 替换标签
	$searcharray = array(
		"/\[h\](.*)\[\/h\]/iU", // 行内等宽
		"/\[incode\](.*)\[\/incode\]/iU", // 行内等宽
		"/\[file=([1-9][0-9]*)\]/", // 站内附件
		"/\[archive=([1-9][0-9]*)\]/", // 站内文档
		"/\[center\](.*)\[\/center\]/siU", // 居中
		"/\[sub\](.*)\[\/sub\]/iU", // 粗体
		"/\[sup\](.*)\[\/sup\]/iU", // 粗体
		"/\[b\](.*)\[\/b\]/iU", // 粗体
		"/\[u\](.*)\[\/u\]/iU", // 下划线
		"/\[del\](.*)\[\/del\]/iU", // 删除线
		"/\[img (left|right|center)\](.*)\[\/img\]/iU", // 图片
		"/\[img\](.*)\[\/img\]/iU", // 图片
		"/\[size=([1-9][0-9]?)\](.*)\[\/size\]/siU", // 字体大小
		"/\[color=(.*)\](.*)\[\/color\]/siU", // 颜色
		"/\[face=(.*)\](.*)\[\/face\]/siU", // 字体
		"/\[hr\]/i",
		"/\[url=(http:\\/\\/)?(.*)\](.*)\[\/url\]/iU",
		"/\[url\](http:\\/\\/)?(.*)\[\/url\]/iU",
		"/\[quote\]\n(.*)\n\[\/quote\]/siU",
		"/\[code\]\n(.*)\n\[\/code\]/siU"
		);

	$replacearray = array(
		$paragraph_end."<h2>\\1</h2>".$paragraph_begin,
		"<span class=\"monospace\">\\1</span>",
		TANGO_DEV ? "https://soulogic.blog/upload/\\1" : "https://soulogic.com/upload/\\1",
		TANGO_DEV ? "https://soulogic.blog/archives/\\1" : "https://soulogic.com/archives/\\1",
		$paragraph_end.str_replace(">", " style=\"text-align: center\">", $paragraph_begin)."\\1".$paragraph_end.$paragraph_begin,
		"<sub>\\1</sub>",
		"<sup>\\1</sup>",
		"<span style=\"font-weight: bold; font-family: '微软雅黑'\">\\1</span>",
		"<span style=\"text-decoration: underline;\">\\1</span>",
		"<span style=\"text-decoration: line-through;\">\\1</span>",
		"<img src=\"\\2\" style=\"float: \\1\" />",
		"<img src=\"\\1\" />",
		"<span style=\"font-size: \\1;\">\\2</span>",
		"<span style=\"color: \\1;\">\\2</span>",
		"<span style=\"font-family: '\\1';\">\\2</span>",
		$paragraph_end."<hr />".$paragraph_begin,
		"<a href=\"\\2\">\\3</a>",
		"<a href=\"\\2\">\\2</a>",
		$paragraph_end."<blockquote>".$paragraph_begin."\\1".$paragraph_end."</blockquote>".$paragraph_begin,
		$paragraph_end."<blockquote class=\"code\">".$paragraph_begin."\\1".$paragraph_end."</blockquote>".$paragraph_begin
		);
    $string = preg_replace($searcharray, $replacearray, $string);

	// 替换标签 [phpcode]
	$search = "/\[phpcode](.*)\[\/phpcode\]/siU";
	$string = preg_replace_callback($search, function($matches) use ($ubb_code, $html_replace, $html_search, $paragraph_end, $paragraph_begin) {

		$value = $matches[1];

		// 还原转义标签
		foreach ($ubb_code as $ubb_code_value) {
			$ubb_code_value2 = str_replace("[", "&#91;", $ubb_code_value);
			$value = str_replace($ubb_code_value2, $ubb_code_value, $value);
		}

		$value = str_replace($html_replace, $html_search, $value); // 还原 HTML

		$value = trim($value);

		if (strpos($value, "<?php") === FALSE) {
			$value = "<?php\n".$value."\n?>";
		}

		$t = token_get_all($value);

		$bLess = FALSE;
		if (substr_count($value, "\n") < 6) {
			$bLess = TRUE;
			foreach ($t as $s) {
				if (is_array($s) && $s[0] === T_INLINE_HTML) {
					$bLess = FALSE;
					break;
				}
			}
		}
		if ($bLess) {
			$t = array_filter($t, function($s) {
				if (!is_array($s)) {
					return TRUE;
				}
				if (in_array($s[0], array(T_OPEN_TAG, T_CLOSE_TAG))) {
					return FALSE;
				}
				return TRUE;
			});
			$end = end($t);
			if (is_array($end) && !strlen(trim($end[1]))) {
				array_pop($t);
			}
		}

		$out = "";
		foreach ($t as $s) {
			if (is_array($s)) {
				$token = strtolower(substr(token_name($s[0]), 2));
				if ($token === "whitespace") {
					$token = "";
				}
				$string = $s[1];
			} else {
				$token = "";
				$string = $s;
			}

			$open = $close = "";
			if ($token) {
				$open  = "<span class=\"".$token."\">";
				$close = "</span>";
			}

			$string = str_replace(array("\r\n", "\r"), "\n", $string);
			$string = htmlspecialchars($string);

			$string = preg_replace(
				"/(\\t+)/",
				"<span class=\"tab\">\\1</span>",
				$string
			);

			$pizza = explode("\n", $string);

			foreach ($pizza as $i => $piece) {
				if ($i > 0) {
					$out .= "<br /></span></li><li><span>";
				}
				if (!empty($piece)) {
					$out .= $open;
				}
				$out .= $piece;
				if (!empty($piece)) {
					$out .= $close;
				}
			}
		}

		return $paragraph_end."<ol class=\"php".($bLess ? " phpless" : "")."\"><li><span>".$out."</span></li></ol>".$paragraph_begin;

	}, $string);

	// 加段落、去掉空段落
	if ($p_tag) {
		$string = str_replace("\n\n", $paragraph_end.$paragraph_begin, $string);
		$string = str_replace($paragraph_begin.$paragraph_end, "", $paragraph_begin.$string.$paragraph_end);
	}
	$string = str_replace("  ", " &#160;", $string);

	// 还原转义标签
	foreach ($ubb_code as $value) {
		$value2 = str_replace("[", "&#91;", $value);
		$string = str_replace($value2, $value, $string);
	}

	// 加换行
	$string = str_replace("\n", "<br />", $string);

	// -_- 不换行
	$string = str_replace("-_-", "<span style=\"white-space: nowrap;\">-_-</span>", $string);

	return $string;
}
