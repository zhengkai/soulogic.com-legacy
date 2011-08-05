<ol><li><span><?php
/*
	a Tokenizer demo by zhengkai@gmail.com

	http://soulogic.com/archive/408
 */

$t = token_get_all(trim(file_get_contents(__FILE__)));

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
			echo "<br /></span></li>\n<li><span>";
		}
		if (!empty($piece)) {
			echo $open;
		}
		echo $piece;
		if (!empty($piece)) {
			echo $close;
		}
	}
}
?></span></li></ol>

<style>
body {
	background-color: black;
}

span.tab {
	font-size: 8px;
}

span {
	white-space: pre;
	cursor: default;
}

ol {
	width: 800px;
}

li {
	color: #ccc;
	font-size: 12px;
	font-family: Verdana, monospace;
	padding: 3px 0;
	padding-left: 6px;
}

li:hover {
	background-color: #0E303F;
}

li > span {
	font-size: 16px;
	color: #ff00ff;
	font-family: monospace;
}

li > span > span {
	color: #ccc;
}

span.inline_html {
	color: #336699;
}

span.open_tag,
span.close_tag
{
	color: red;
}

span.variable {
	color: #00ffff;
}

span.string {
	color: #00AA00;
}

span.constant_encapsed_string,
span.lnumber
{
	color: #00ff00;
}

span.comment {
	color: #808080;
}

span.file,
span.dir,
span.class_c
{
	color: #8080ff;
}

span.is_equal,
span.is_greater_or_equal,
span.is_identical,
span.is_not_equal,
span.is_not_identical,
span.is_smaller_or_equal,
span.sl,
span.sl_equal,
span.sr,
span.sr_equal,
span.start_heredoc,
span.boolean_and,
span.boolean_or,
span.double_colon,
span.double_arrow
{
	color: #ff00ff;
}

span.endfor,
span.endforeach,
span.endif,
span.endswitch,
span.endwhile,
span.break,
span.continue,
span.declare,
span.enddeclare,
span.do,
span.else,
span.elseif,
span.for,
span.as,
span.foreach,
span.goto,
span.if,
span.case,
span.default,
span.switch,
span.while,
span.function,
span.class,
span.extends,
span.new,
span.var,
span.catch,
span.throw,
span.try,
span.namespace,
span.use,
span.abstract,
span.clone,
span.const,
span.final,
span.implements,
span.interface,
span.private,
span.protected,
span.public,
span.and,
span.or,
span.xor,
span.instanceof,
span.global,
span.static,
span.array,
span.die,
span.echo,
span.empty,
span.exit,
span.eval,
span.include,
span.include_once,
span.isset,
span.list,
span.require,
span.require_once,
span.return,
span.print,
span.unset
{
	color: #ffff00;
}
</style>
