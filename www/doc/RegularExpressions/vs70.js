var surl = "http://soulogic.com/doc/RegularExpressions/";

if (top.location != surl) {
	top.location.href = surl;
}

var L_FeedbackLink_TEXT = "将反馈发送到 Visual Studio";
var L_MessageLink_TEXT = "Microsoft 知识库链接";
var L_MailToLink_TEXT = "将反馈发送到 Visual Studio ";

var L_SeeAlso_TEXT = "请参阅";
var L_Requirements_TEXT = "要求";
var L_QuickInfo_TEXT = "快速信息";
var L_FilterTip_TEXT = "语言筛选器";
var L_Language_TEXT = "语言";
var L_ShowAll_TEXT = "显示所有语言";

var L_Animation_Text = "单击以动画显示";

var L_PopUpBoxStyle_Style = "WIDTH:200PX; PADDING: 5px 7px 7px 7px; BACKGROUND-COLOR:#FFFFCC; BORDER:SOLID 1 #999999; VISIBILITY:HIDDEN; POSITION:ABSOLUTE; TOP:0PX; LEFT:0PX; Z-INDEX:2;";

var US_See_Also = "See Also";
var US_Requirements = "Requirements";
var US_QuickInfo = "QuickInfo"
var bRefTips = "True"
var popOpen, theImg, theDiv, e;
if (bRefTips=="True"){
	var mColl = document.all.tags("MeTa");
	for (i=0; i<mColl.length; i++){
		if (mColl(i).name.toUpperCase()=="SCRIPTSETTINGS"){
			if (mColl(i).RefTips.toUpperCase()=="FALSE") bRefTips = "False";
		}
	}
}
var ie4 = false;
var advanced = false;
var curLang = null;
var showAll = true;
var cook = null;
var baseUrl = document.scripts[document.scripts.length - 1].src.replace(/[^\/]+.js/, "");
var popupDIV = "<DIV ID='popUpWindow' STYLE='"+L_PopUpBoxStyle_Style+"'>" + "</DIV>";

if (navigator.appName == "Microsoft Internet Explorer") {
	var ver = navigator.appVersion;
	var v = new Number(ver.substring(0,ver.indexOf(".", 0)));
	if (v >= 4) {
		advanced = true;
		ie4 = true;

		var toks = ver.split(/[^0-9.]/);
		if (toks) {
			for (var i = 0; i < toks.length; i++) {
				var tok = toks[i];
				if (tok.indexOf(".", 0) > 0) {
					if (tok >= 5)
						ie4 = false;
				}
			}
		}
	}
}
if (advanced)
	window.onload = bodyOnLoad;
	window.onbeforeprint = set_to_print;
	window.onafterprint = reset_form;


function finishOnLoad(){
	document.onkeypress = ieKey;
	window.onresize = closeIE4;
	document.body.onclick = bodyOnClick;
	if (advanced){
		document.body.insertAdjacentHTML('beforeEnd', popupDIV);
	}
return;}


function bodyOnClick(){
	if (advanced) {
		var elem = window.event.srcElement;
		for (; elem; elem = elem.parentElement) {
			if (elem.id == "reftip")
				return;
		}
		resetButtons();
		hideTip();
		closeMenu();
		hideSeeAlso();
		resizeBan();
	}
}


function ieKey(){
	if (window.event.keyCode == 27){
		hideTip();
		closeMenu();
		hideSeeAlso();
		resizeBan();
		resetButtons();
		closeIE4();
	}
return;}


function closeIE4(){
	document.all.popUpWindow.style.visibility = "hidden";
	popOpen = false;
	resizeBan();
return;}


function bodyOnLoad(){
	if (advanced) {
		var address = location.href;
		var bookmarkStart = address.indexOf("#")
		if (bookmarkStart>-1){
			var bookmark = address.substring(bookmarkStart+1);
			curLang = findLanguage(bookmark);
		}
		else {
			var cLang;
			try{
				for (i=1; i< window.external.ContextAttributes.Count; i++){
					if(window.external.ContextAttributes(i).Name.toUpperCase()=="DEVLANG"){
						var b = window.external.ContextAttributes(i).Values.toArray();
						cLang = b[0].toUpperCase();
					}
				}
			}
			catch(e){}
			if (cLang != null){
				if (cLang.indexOf("VB")!=-1) curLang = "Visual Basic";
				if (cLang.indexOf("VC")!=-1) curLang = "C++";
				if (cLang.indexOf("C#")!=-1) curLang = "C#";
				if (cLang.indexOf("JSCRIPT")!=-1) curLang = "JScript";
				if (cLang.indexOf("VBSCRIPT")!=-1) curLang = "VBScript";
			}

			if (curLang == null){
				var l = ""
				try {l = window.external.Help.FilterQuery.toUpperCase();}
				catch(e){}
				if (l.indexOf("VB")!=-1) curLang = "Visual Basic";
				if (l.indexOf("VC")!=-1) curLang = "C++";
				if (l.indexOf("C#")!=-1) curLang = "C#";
				if (l.indexOf("JSCRIPT")!=-1) curLang = "JScript";
				if (l.indexOf("VBSCRIPT")!=-1) curLang = "VBScript";
			}
		}
		if (curLang != null)
			showAll = false;
		initLangs();
		resizeBan();
		if (bRefTips=="True") initReftips();
		initSeeAlso();
		resetButtons();

	}
finishOnLoad();
}


function findLanguage(bookmark){
	var found = false
	var aColl = document.all.tags("A");
	for (i=0; i<aColl.length; i++){
		if (aColl(i).name.toUpperCase()==bookmark.toUpperCase()){
			var elem = null
			for(t = 1; t<4; t++){
				elem = document.all(aColl(i).sourceIndex + t);
				if (elem.tagName.toUpperCase()=="SPAN")
					found = true;
					break;
			}
			break;
		}
	}
	if (found){
		var lang = elem.innerText
		return lang.substring(lang.indexOf("[") + 1, lang.indexOf("]"));
	}
}


function initLangs(){
	var hdr = document.all.hdr;
	if (!hdr)
		return;

	var langs = new Array;
	var spans = document.all.tags("SPAN");
	if (spans) {
		var iElem = spans.length;
		for (iElem = 0; iElem < spans.length; iElem++) {
			var elem = spans[iElem];
			if (elem.className == "lang") {

				var a = elem.innerText.split(",");
				for (var iTok = 0; iTok < a.length; iTok++) {
					if (a[iTok]=="[A]"){
						langs[0]="A";
						elem.parentElement.outerText="";
					}
					var m = a[iTok].match(/([A-Za-z].*[A-Za-z+#])/);
					if (m) {
						var iLang = 0;
						while (iLang < langs.length && langs[iLang] < m[1])
							iLang++;
						if (iLang == langs.length || langs[iLang] != m[1]) {
							var before = langs.slice(0,iLang);
							var after = langs.slice(iLang);
							langs = before.concat(m[1]).concat(after);
						}
					}
				}
			}
		}
	}

	if (langs.length > 0) {
		var pres = document.all.tags("PRE");
		if (pres) {
			for (var iPre = 0; iPre < pres.length; iPre++)
				initPreElem(pres[iPre]);
		}

		if (curLang == null){
			var obj = document.all.obj_cook;
			if (obj && obj.object) {
				cook = obj;
				if (obj.getValue("lang.all") != "1") {
					var lang = obj.getValue("lang");
					var c = langs.length;
					for (var i = 0; i != c; ++i) {
						if (langs[i] == lang) {
							curLang = langs[i];
							showAll = false;
						}
					}
				}
			}

		}

		var iLim = document.body.all.length;
		var head = null;
		for (var i = 0; i < iLim; i++) {
			var elem = document.body.all[i];
			if (elem.tagName.match(/^(P)|(PRE)|([DOU]L)$/))
				break;
			if (elem.tagName.match(/^H[1-6]$/)) {
				head = elem;
				head.insertAdjacentHTML('BeforeEnd', '<SPAN CLASS=ilang></SPAN>');
			}
		}

		iLang = 0;
		foundA = false;
		while (iLang != langs.length){
			if (langs[iLang]=="A")
				foundA = true;
			iLang++;
		}
		if (!foundA)
			// 不启用语言按钮。
			var td = hdr.insertCell(0);
		if (td) {
			// 将语言按钮添加到按钮栏。
			td.className = "button1";
			td.onmouseenter = hotButton;
			td.onmouseleave = coldButton;
			td.onkeyup = ieKey;
			td.onkeypress = langMenu;
			td.onclick = langMenu;
			td.innerHTML = '<IMG id=button1 SRC="' + baseUrl + 'Filter1a.gif' + '" ALT="' +
				L_FilterTip_TEXT + '" BORDER=0 TABINDEX=0>';

			// 添加菜单。
			var div = '<DIV ID="lang_menu" CLASS=langMenu onkeypress=ieKey><B>' + L_Language_TEXT + '</B><UL>';
			for (var i = 0; i < langs.length; i++)
				div += '<LI><A HREF="" ONCLICK="chooseLang(this)">' + langs[i] + '</A><BR>';
			div += '<LI><A HREF="" ONCLICK="chooseAll()">' + L_ShowAll_TEXT + '</A></UL></DIV>';
			nsbanner.insertAdjacentHTML('AfterEnd', div);
		}

		if (!showAll)
			filterLang();
	}
}


function initPreElem(pre){
	var htm0 = pre.outerHTML;

	var reLang = /<span\b[^>]*class="?lang"?[^>]*>/i;
	var iFirst = -1;
	var iSecond = -1;

	iFirst = htm0.search(reLang);
	if (iFirst >= 0) {
		iPos = iFirst + 17;
		iMatch = htm0.substr(iPos).search(reLang);
		if (iMatch >= 0)
			iSecond = iPos + iMatch;
	}

	if (iSecond < 0) {
		var htm1 = trimPreElem(htm0);
		if (htm1 != htm0) {
			pre.insertAdjacentHTML('AfterEnd', htm1);
			pre.outerHTML = "";
		}
	}
	else {
		var rePairs = /<(\w+)\b[^>]*><\/\1>/gi;

		var substr1 = htm0.substring(0,iSecond);
		var tags1 = substr1.replace(/>[^<>]+(<|$)/g, ">$1");
		var open1 = tags1.replace(rePairs, "");
		open1 = open1.replace(rePairs, "");

		var substr2 = htm0.substring(iSecond);
		var tags2 = substr2.replace(/>[^<>]+</g, "><");
		var open2 = tags2.replace(rePairs, "");
		open2 = open2.replace(rePairs, "");

		pre.insertAdjacentHTML('AfterEnd', open1 + substr2);
		pre.insertAdjacentHTML('AfterEnd', trimPreElem(substr1 + open2));
		pre.outerHTML = "";
	}
}


function trimPreElem(htm){
	return htm.replace(/[ \r\n]*((<\/[BI]>)*)[ \r\n]*<\/PRE>/g, "$1</PRE>").replace(
		/\w*<\/SPAN>\w*((<[BI]>)*)\r\n/g, "\r\n</SPAN>$1"
		);
}


function getBlock(elem){
	while (elem && elem.tagName.match(/^([BIUA]|(SPAN)|(CODE)|(TD))$/))
		elem = elem.parentElement;
	return elem;
}


function langMenu(){
	bodyOnClick();
	var btn = window.event.srcElement
	if (btn.id=="button1"){
	btn.src = btn.src.replace("a.gif", "c.gif");}

	window.event.returnValue = false;
	window.event.cancelBubble = true;

	var div = document.all.lang_menu;
	var lnk = window.event.srcElement;
	if (div && lnk) {
		var x = lnk.offsetLeft + lnk.offsetWidth - div.offsetWidth;
		div.style.pixelLeft = (x < 0) ? 0 : x;
		div.style.pixelTop = lnk.offsetTop + lnk.offsetHeight;
		div.style.visibility = "visible";
	}
}


function chooseLang(item){
	window.event.returnValue = false;
	window.event.cancelBubble = true;

	if (item) {
		closeMenu();
		curLang = item.innerText;
		showAll = false;
	}

	if (cook) {
		cook.putValue('lang', curLang);
		cook.putValue('lang.all', '');
	}

	filterLang();
}


function chooseAll(){
	window.event.returnValue = false;
	window.event.cancelBubble = true;

	closeMenu();

	showAll = true;
	if (cook)
		cook.putValue('lang.all', '1');

	unfilterLang();
}


function closeMenu(){
	var div = document.all.lang_menu;
	if (div && div.style.visibility != "hidden") {
		var lnk = document.activeElement;
		if (lnk && lnk.tagName == "A")
			lnk.blur();

		div.style.visibility = "hidden";
	}
}


function getNext(elem){
	for (var i = elem.sourceIndex + 1; i < document.all.length; i++) {
		var next = document.all[i];
		if (!elem.contains(next))
			return next;
	}
	return null;
}


function filterMatch(text, name){
	var a = text.split(",");
	for (var iTok = 0; iTok < a.length; iTok++) {
		var m = a[iTok].match(/([A-Za-z].*[A-Za-z+#])/);
		if (m && m[1] == name)
			return true;
	}
	return false;
}


function topicHeading(head){
	try{var iLim = nstext.children.length;
	Section = nstext;}
	catch(e){try{var iLim = scrtext.children.length;
		Section = scrtext;}
		catch(e){var iLim = document.body.children.length;
		Section = document.body;
		}
	}
	var idxLim = head.sourceIndex;
	for (var i = 0; i < iLim; i++) {
		var elem = Section.children[i];
		if (elem.sourceIndex < idxLim) {
			if (elem.tagName.match(/^(P)|(PRE)|([DOU]L)$/))
				return false;
		}
		else
			break;
	}
	return true;
}


function filterLang(){
	var spans = document.all.tags("SPAN");
	for (var i = 0; i < spans.length; i++) {
		var elem = spans[i];
		if (elem.className == "lang") {
			var newVal = filterMatch(elem.innerText, curLang) ? "block" : "none";
			var block = getBlock(elem);
			block.style.display = newVal;
			elem.style.display = "none";

			if (block.tagName == "DT") {
				var next = getNext(block);
				if (next && next.tagName == "DD")
					next.style.display = newVal;
			}
			else if (block.tagName == "DIV") {
				block.className = "filtered2";
			}
			else if (block.tagName.match(/^H[1-6]$/)) {
				if (topicHeading(block)) {
					if (newVal != "none") {
						var tag = null;
						if (block.children && block.children.length) {
							tag = block.children[block.children.length - 1];
							if (tag.className == "ilang") {
								tag.innerHTML = (newVal == "block") ?
									'&nbsp; [' + curLang + ']' : "";
							}
						}
					}
				}
				else {
					var next = getNext(block);
					while (next && !next.tagName.match(/^(H[1-6])$/)) {
						if (next.tagName =="DIV"){
							if (next.className.toUpperCase() != "TABLEDIV") break;
						}
						next.style.display = newVal;
						next = getNext(next);
					}
				}
			}
		}
		else if (elem.className == "ilang") {
			var block = getBlock(elem);
			if (block.tagName == "H1")
				elem.innerHTML = '&nbsp; [' + curLang + ']';
		}
	}

	if (ie4) {
		document.body.style.display = "none";
		document.body.style.display = "block";
	}
	resizeBan();
	resetButtons();
}


function unfilterLang(name){
	var spans = document.all.tags("SPAN");
	for (var i = 0; i < spans.length; i++) {
		var elem = spans[i];
		if (elem.className == "lang") {
			var block = getBlock(elem);
			block.style.display = "block";
			elem.style.display = "inline";

			if (block.tagName == "DT") {
				var next = getNext(block);
				if (next && next.tagName == "DD")
					next.style.display = "block";
			}
			else if (block.tagName == "DIV") {
				block.className = "filtered";
			}
			else if (block.tagName.match(/^H[1-6]$/)) {
				if (topicHeading(block)) {
					var tag = null;
					if (block.children && block.children.length) {
						tag = block.children[block.children.length - 1];
						if (tag && tag.className == "ilang")
							tag.innerHTML = "";
					}
				}
				else {
					var next = getNext(block);
					while (next && !next.tagName.match(/^(H[1-6])$/)) {
						if (next.tagName =="DIV"){
							if (next.className.toUpperCase() != "TABLEDIV") break;
						}
						next.style.display = "block";
						next = getNext(next);
					}
				}
			}
		}
		else if (elem.className == "ilang") {
			elem.innerHTML = "";
		}
	}
	resizeBan();
	resetButtons();
}

function initReftips(){
	var DLs = document.all.tags("DL");
	var PREs = document.all.tags("PRE");
	if (DLs && PREs) {
		var iDL = 0;
		var iPRE = 0;
		var iSyntax = -1;
		for (var iPRE = 0; iPRE < PREs.length; iPRE++) {
			if (PREs[iPRE].className == "syntax") {
				while (iDL < DLs.length && DLs[iDL].sourceIndex < PREs[iPRE].sourceIndex)
					iDL++;
				if (iDL < DLs.length) {
					initSyntax(PREs[iPRE], DLs[iDL]);
					iSyntax = iPRE;
				}
				else
					break;
			}
		}

		if (iSyntax >= 0) {
			var last = PREs[iSyntax];
			last.insertAdjacentHTML(
				'AfterEnd',
				'<DIV ID=reftip CLASS=reftip STYLE="position:absolute;visibility:hidden;overflow:visible;"></DIV>'
				);
		}
	}
}


function initSyntax(pre, dl){
	var strSyn = pre.outerHTML;
	var ichStart = strSyn.indexOf('>', 0) + 1;
	var terms = dl.children.tags("DT");
	if (terms) {
		for (var iTerm = 0; iTerm < terms.length; iTerm++) {
			var words = terms[iTerm].innerText.replace(/\[.+\]/g, " ").replace(/,/g, " ").split(" ");
			var htm = terms[iTerm].innerHTML;
			for (var iWord = 0; iWord < words.length; iWord++) {
				var word = words[iWord];

				if (word.length > 0 && htm.indexOf(word, 0) < 0)
					word = word.replace(/:.+/, "");

				if (word.length > 0) {
					var ichMatch = findTerm(strSyn, ichStart, word);
					while (ichMatch > 0) {
						var strTag = '<A HREF="" ONCLICK="showTip(this)" CLASS="synParam">' + word + '</A>';

						strSyn =
							strSyn.slice(0, ichMatch) +
							strTag +
							strSyn.slice(ichMatch + word.length);

						ichMatch = findTerm(strSyn, ichMatch + strTag.length, word);
					}
				}
			}
		}
	}

	pre.outerHTML = strSyn;
}


function findTerm(strSyn, ichPos, strTerm)
{
	var ichMatch = strSyn.indexOf(strTerm, ichPos);
	while (ichMatch >= 0) {
		var prev = (ichMatch == 0) ? '\0' : strSyn.charAt(ichMatch - 1);
		var next = strSyn.charAt(ichMatch + strTerm.length);
		if (!isalnum(prev) && !isalnum(next) && !isInTag(strSyn, ichMatch)) {
			var ichComment = strSyn.indexOf("/*", ichPos);
			while (ichComment >= 0) {
				if (ichComment > ichMatch) {
					ichComment = -1;
					break;
				}
				var ichEnd = strSyn.indexOf("*/", ichComment);
				if (ichEnd < 0 || ichEnd > ichMatch)
					break;
				ichComment = strSyn.indexOf("/*", ichEnd);
			}
			if (ichComment < 0) {
				ichComment = strSyn.indexOf("//", ichPos);
				while (ichComment >= 0) {
					if (ichComment > ichMatch) {
						ichComment = -1;
						break;
					}
					var ichEnd = strSyn.indexOf("\n", ichComment);
					if (ichEnd < 0 || ichEnd > ichMatch)
						break;
					ichComment = strSyn.indexOf("//", ichEnd);
				}
			}
			if (ichComment < 0)
				break;
		}
		ichMatch = strSyn.indexOf(strTerm, ichMatch + strTerm.length);
	}
	return ichMatch;
}


function isInTag(strHtml, ichPos)
{
	return strHtml.lastIndexOf('<', ichPos) >
		strHtml.lastIndexOf('>', ichPos);
}


function isalnum(ch){
	return ((ch >= 'a' && ch <= 'z') || (ch >= 'A' && ch <= 'Z') || (ch >= '0' && ch <= '9') || (ch == '_') || (ch == '-'));
}


function showTip(link){
	bodyOnClick();

	var tip = document.all.reftip;
	if (!tip || !link)
		return;

	window.event.returnValue = false;
	window.event.cancelBubble = true;

	tip.style.visibility = "hidden";
	tip.style.pixelWidth = 260;
	tip.style.pixelHeight = 24;

	var term = null;
	var def = null;
	var DLs = document.all.tags("DL");
	for (var iDL = 0; iDL < DLs.length; iDL++) {
		if (DLs[iDL].sourceIndex > link.sourceIndex) {
			var dl = DLs[iDL];
			var iMax = dl.children.length - 1;
			for (var iElem = 0; iElem < iMax; iElem++) {
				var dt = dl.children[iElem];
				if (dt.tagName == "DT" && dt.style.display != "none") {
					if (findTerm(dt.innerText, 0, link.innerText) >= 0) {
						var dd = dl.children[iElem + 1];
						if (dd.tagName == "DD") {
							term = dt;
							def = dd;
						}
						break;
					}
				}
			}
			break;
		}
	}

	if (def) {
		window.linkElement = link;
		window.linkTarget = term;
		tip.innerHTML = '<DL><DT>' + term.innerHTML + '</DT><DD>' + def.innerHTML + '</DD></DL>';
		window.setTimeout("moveTip()", 0);
	}
}


function jumpParam(){
	hideTip();

	window.linkTarget.scrollIntoView();
	document.body.scrollLeft = 0;

	flash(3);
}


function flash(c){
	window.linkTarget.style.background = (c & 1) ? "#CCCCCC" : "";
	if (c)
		window.setTimeout("flash(" + (c-1) + ")", 200);
}


function moveTip(){
	var tip = document.all.reftip;
	var link = window.linkElement;
	if (!tip || !link)
		return;

	var w = tip.offsetWidth;
	var h = tip.offsetHeight;

	if (w > tip.style.pixelWidth) {
		tip.style.pixelWidth = w;
		window.setTimeout("moveTip()", 0);
		return;
	}

	var maxw = document.body.clientWidth;
	var maxh = document.body.clientHeight;

	if (h > maxh) {
		if (w < maxw) {
			w = w * 3 / 2;
			tip.style.pixelWidth = (w < maxw) ? w : maxw;
			window.setTimeout("moveTip()", 0);
			return;
		}
	}

	var x,y;

	var linkLeft = link.offsetLeft - document.body.scrollLeft;
	var linkRight = linkLeft + link.offsetWidth;

	var linkTop = link.offsetTop - document.body.scrollTop;
	var linkBottom = linkTop + link.offsetHeight;

	var cxMin = link.offsetWidth - 24;
	if (cxMin < 16)
		cxMin = 16;

	if (linkLeft + cxMin + w <= maxw) {
		x = maxw - w;
		if (x > linkRight + 8)
			x = linkRight + 8;
		y = maxh - h;
		if (y > linkTop)
			y = linkTop;
	}
	else if (linkBottom + h <= maxh) {
		x = maxw - w;
		if (x < 0)
			x = 0;
		y = linkBottom;
	}
	else if (w <= linkRight - cxMin) {
		x = linkLeft - w - 8;
		if (x < 0)
			x = 0;
		y = maxh - h;
		if (y > linkTop)
			y = linkTop;
	}
	else if (h <= linkTop) {
		x = maxw - w;
		if (x < 0)
			x = 0;
		y = linkTop - h;
	}
	else if (w >= maxw) {
		x = 0;
		y = linkBottom;
	}
	else {
		w = w * 3 / 2;
		tip.style.pixelWidth = (w < maxw) ? w : maxw;
		window.setTimeout("moveTip()", 0);
		return;
	}

	link.style.background = "#CCCCCC";
	tip.style.pixelLeft = x + document.body.scrollLeft;
	tip.style.pixelTop = y + document.body.scrollTop;
	tip.style.visibility = "visible";
}


function hideTip(){
	if (window.linkElement) {
		window.linkElement.style.background = "";
		window.linkElement = null;
	}

	var tip = document.all.reftip;
	if (tip) {
		tip.style.visibility = "hidden";
		tip.innerHTML = "";
	}
}


function beginsWith(s1, s2){

	return s1.substring(0, s2.length) == s2;
}


function initSeeAlso(){
	var hdr = document.all.hdr;
	if (!hdr)
		return;

	var divS = new String;
	var divR = new String;
	var heads = document.all.tags("H4");
	if (heads) {
		for (var i = 0; i < heads.length; i++) {
			var head = heads[i];
			var txt = head.innerText;
			if (beginsWith(txt, L_SeeAlso_TEXT) || beginsWith(txt, US_See_Also)) {
				divS += head.outerHTML;
				var next = getNext(head);
				while (next && !next.tagName.match(/^(H[1-4])|(DIV)$/)) {
					divS += next.outerHTML;
					next = getNext(next);
				}
			}
			else if (beginsWith(txt, L_Requirements_TEXT) || beginsWith(txt, US_Requirements) || beginsWith(txt, L_QuickInfo_TEXT) || beginsWith(txt, US_QuickInfo)) {
				divR += head.outerHTML;
				var next = getNext(head);
				var isValid = true;
				while (isValid){
					if (next && !next.tagName.match(/^(H[1-4])$/)){
						if (next.tagName == "DIV" && next.outerHTML.indexOf("tablediv")==-1)
								isValid = false;
						if (isValid){
							divR += next.outerHTML;
							next = getNext(next);
						}
					}
					else
						isValid = false;
				}
			}
		}
	}

	var pos = getNext(hdr.parentElement);
	if (pos) {
		if (divR != "") {
			divR = '<DIV ID=rpop CLASS=sapop onkeypress=ieKey>' + divR + '</DIV>';
			var td = hdr.insertCell(0);
			if (td) {
				td.className = "button1";
				td.onclick = showRequirements;
				td.onmouseenter = hotButton;
				td.onmouseleave = coldButton;
				td.onkeyup = ieKey;
				td.onkeypress = showRequirements;
				td.innerHTML = '<IMG id=button1 SRC="' + baseUrl + 'Requirements1a.gif' + '" ALT="' + L_Requirements_TEXT + '" BORDER=0 TABINDEX=0>';
				if (advanced)
					nsbanner.insertAdjacentHTML('AfterEnd', divR);
				else
					document.body.insertAdjacentHTML('BeforeEnd', divR);
			}
		}
		if (divS != "") {
			divS = '<DIV ID=sapop CLASS=sapop onkeypress=ieKey>' + divS + '</DIV>';
			var td = hdr.insertCell(0);
			if (td) {
				td.className = "button1";
				td.onclick = showSeeAlso;
				td.onmouseenter = hotButton;
				td.onmouseleave = coldButton;
				td.onkeyup = ieKey;
				td.onkeypress = showSeeAlso;
				td.innerHTML = '<IMG id=button1 SRC="' + baseUrl + 'SeeAlso1a.gif' + '" ALT="' + L_SeeAlso_TEXT + '" BORDER=0 TABINDEX=0>';
				if (advanced)
					nsbanner.insertAdjacentHTML('AfterEnd', divS);
				else
					document.body.insertAdjacentHTML('BeforeEnd', divS);
			}
		}
	}
}


function hotButton(){
	if (window.event.srcElement.innerHTML!=""){
		window.event.srcElement.innerHTML = window.event.srcElement.innerHTML.replace("a.gif", "b.gif");
	}
}

function coldButton(){
	if (window.event.srcElement.innerHTML!=""){
		window.event.srcElement.innerHTML = window.event.srcElement.innerHTML.replace("b.gif", "a.gif");
	}
}

function resetButtons(){
	var btns = document.all.button1;
	if (btns) {
		for (var i = 0; i < btns.length; i++){
			btns[i].src = btns[i].src.replace("c.gif", "a.gif");
			btns[i].src = btns[i].src.replace("b.gif", "a.gif");
		}
	}
}

function showSeeAlso(){
	bodyOnClick();
	var btn = window.event.srcElement
	if (btn.id=="button1"){
	btn.src = btn.src.replace("a.gif", "c.gif");}

	window.event.returnValue = false;
	window.event.cancelBubble = true;

	var div = document.all.sapop;
	var lnk = window.event.srcElement;

	if (div && lnk) {
		div.style.pixelTop = lnk.offsetTop + lnk.offsetHeight;
		div.style.visibility = "visible";
	}
}


function showRequirements(){
	bodyOnClick();
	var btn = window.event.srcElement
	if (btn.id=="button1"){
	btn.src = btn.src.replace("a.gif", "c.gif");}

	window.event.returnValue = false;
	window.event.cancelBubble = true;

	var div = document.all.rpop;
	var lnk = window.event.srcElement;

	if (div && lnk) {
		div.style.pixelTop = lnk.offsetTop + lnk.offsetHeight;
		div.style.visibility = "visible";
	}
}


function hideSeeAlso(){
	var div = document.all.sapop;
	if (div)
		div.style.visibility = "hidden";

	var div = document.all.rpop;
	if (div)
		div.style.visibility = "hidden";
}


function GrabtheExpandDiv(e){
	var theExpandDiv;
	for (var a = 0; a < 5; a++){
    	var theTag = e.sourceIndex + e.children.length + a;
    	theExpandDiv= document.all(theTag);
     	if ((((theExpandDiv.tagName == 'DIV') || (theExpandDiv.tagName == 'SPAN')) && ((theExpandDiv.className.toLowerCase() == 'expand') || (theExpandDiv.className.toLowerCase() == 'expand2'))) || theTag == document.all.length){break;}}
return theExpandDiv;}


function getImage(){
	for (var a = 0; a < 5; a++){
      	if ((e.tagName != 'A') && (e.parentElement != null)){e = e.parentElement;}
		var elemImg = e;
		if(elemImg.tagName == 'A'){elemImg = e.all.tags('IMG')(0); break;}}
return elemImg;}


function callExpand(){
	e = window.event.srcElement;
	event.returnValue = false;

	var theDiv = GrabtheExpandDiv(e);

	if (theDiv.style.display == 'block'){
		var theImg = getImage(e);
		theImg.src = "coe.gif";
		theDiv.style.display = "none";}
	else {
		var theImg = getImage(e);
		theImg.src = "coc.gif";
		theDiv.style.display = "block";}
return;}


function callExpandAll(){
	e = window.event.srcElement;
	event.returnValue = false;

	if (e.innerHTML.indexOf("Expand All") != -1){eOrC="block"}else{eOrC="none"}
	if (eOrC=="block"){
		e.innerHTML="<IMG CLASS='ExPand' SRC='coc.gif' HEIGHT='9' WIDTH='12' ALT='Expand/Collapse' BORDER='0'>Collapse All";}
	else{
		e.innerHTML="<IMG CLASS='ExPand' SRC='coe.gif' HEIGHT='9' WIDTH='12' ALT='Expand/Collapse' BORDER='0'>Expand All";}
  	for (var a = 0; a < document.all.length; a++){
		e=document.all[a];

 		if (e.id.indexOf('ExPand') != -1){
			if (e.id.indexOf('ExPandAll') == -1){

			var theDiv = GrabtheExpandDiv(e);
			if (eOrC == 'none'){
				theImg = getImage(e);
				theImg.src = "coe.gif";
				theDiv.style.display = eOrC;}
			else {
				theImg = getImage(e);
				theImg.src = "coc.gif";
				theDiv.style.display = eOrC;}
			}
		}
	}
return;}


function insertAnimation(name) {
	document.write("<input type=\"image\" src=\"AnimButton1.gif\" onClick=\"changeToAnimate('" + name +".gif', '" + name + "');\" onMouseDown=\"src='AnimButton2.gif';\" onMouseUp=\"src='AnimButton1.gif';\">&nbsp;" + L_Animation_Text + "<br><img name=\"" + name + "\" src=\"" + name + "1.gif\">");
	}


function changeToAnimate(imgSRC, imgName) {
	animate = new Image();
	animate.src = imgSRC;
	document[imgName].src = animate.src;
	}


function resizeBan(){
	if (document.body.clientWidth==0) return;
	var oBanner= document.all.item("nsbanner");
	var oText= document.all.item("nstext");
	if (oText == null) return;
	var oBannerrow1 = document.all.item("bannerrow1");
	var oTitleRow = document.all.item("titlerow");
	if (oBannerrow1 != null){
		var iScrollWidth = bodyID.scrollWidth;
		oBannerrow1.style.marginRight = 0 - iScrollWidth;
	}
	if (oTitleRow != null){
		oTitleRow.style.padding = "0px 10px 0px 22px; ";
	}
	if (oBanner != null){
		if (document.all.tags('iframe') !=null){
		document.body.scroll = "yes"
		return;
	}
		document.body.scroll = "no"
		oText.style.overflow= "auto";
 		oBanner.style.width= document.body.offsetWidth-2;
		oText.style.paddingRight = "20px"; // 宽度问题代码
		oText.style.width= document.body.offsetWidth-4;
		oText.style.top=0;
		if (document.body.offsetHeight > oBanner.offsetHeight)
    			oText.style.height= document.body.offsetHeight - (oBanner.offsetHeight+4)
		else oText.style.height=0
	}
}


function set_to_print(){

	var i;

	if (window.text)document.all.text.style.height = "auto";

	for (i=0; i < document.all.length; i++){
		if (document.all[i].tagName == "BODY") {
			document.all[i].scroll = "yes";
			}
		if (document.all[i].id == "nsbanner") {
			document.all[i].style.margin = "0px 0px 0px 0px";
			}
		if (document.all[i].id == "nstext") {
			document.all[i].style.overflow = "visible";
			document.all[i].style.top = "5px";
			document.all[i].style.width = "100%";
			document.all[i].style.padding = "0px 10px 0px 30px";
			}
		if (document.all[i].tagName == "A") {
			document.all[i].outerHTML = "<A HREF=''>" + document.all[i].innerHTML + "</a>";
			}
		}
}


function reset_form(){

	 document.location.reload();

}


function writefeedbacklink(){
	msdnid = arguments[0];
	contextid = arguments[1];
	topictitle = arguments[2];
	href = "http://beta.visualstudio.net/feedback.asp?feedback=doc&msdnid="+msdnid+"&contextid="+contextid+"&topictitle="+topictitle;
	document.writeln("<a href="+href+">"+L_FeedbackLink_TEXT+"</a>");
}


function writemessagelink(){
	msdnid = arguments[0];
	href = "http://www.microsoft.com/contentredirect.asp?prd=vs&pver=7.0&id="+msdnid;
	document.writeln("<a href="+href+">"+L_MessageLink_TEXT+"</a>");
}


function writemailtolink(){
	emailalias = arguments[0];
	contextid = arguments[1];
	topictitle = arguments[2];

	href = "mailto:"+emailalias+"?subject=Feedback%20on%20topic%20-%20"+topictitle+",%20URL%20-%20"+contextid;
	document.writeln("<a href="+href+">"+L_MailToLink_TEXT+"</a>");
}

function navfailpageparam(){
	var address = location.href;
	var params = address.indexOf("#");
		if (params==-1)
			params = address.indexOf("?");
		if (params>-1)
			document.write(address.substring(params+1));
}