$.ajaxSetup({
	type: "POST",
	dataType: "json"
});

var SoulogicBlog = new Object();

$(function() {
	SoulogicBlog.archiveID = $("#commentForm input[name=archive_id]").val();
});
SoulogicBlog.archiveID = false;

$(function() {
	if (!(SoulogicBlog.archiveID)) {
		return;
	}
	SoulogicBlog.loadComment();
});
SoulogicBlog.loadComment = function() {
	$.ajax({
		url: "/comment.ajax.php",
		data: {archive_id: this.archiveID},
		success: function(data) {
			if (!data.comment) {
				return false;
			}

			var sOut = "";
			var iFloor = 0;

			for (var i in data.comment) {
				iFloor++;
				aRow = data.comment[i];
				sAvatar = "<img src=\"https://secure.gravatar.com/avatar/" + aRow.email + "?s=48\" />";
				sName = aRow.guest;
				sAddClassName = "";
				if (!sName) {
					sName = "匿名访客";
					sAddClassName = " anonym";
				}
				if (aRow.url) {
					sAvatar = "<a href=\"http://" + aRow.url + "\">" + sAvatar + "</a>";
					sName   = "<a href=\"http://" + aRow.url + "\">" + sName + "</a>";
				}

				aRow.content = (aRow.content)
					.replace(/\r\n/g, "\n")
					.replace(/\r/g, "\n")
					.replace(/\n{2,}/g, "</p><p>")
					.replace(/\n/g, "<br />");

				sOut += sAvatar
					+ "<div>"
					+ "<span class=\"floor\">#" + iFloor + "</span>"
					+ "<p class=\"name" + sAddClassName + "\">" + sName + "<br /><span class=\"date\">Commented on " + aRow.date + "</span></p>"
					+ "<div class=\"comment\"><p>" + aRow.content + "</p></div>"
					+ "</div>";
			}

			oComment = $("#comment");

			if (!iFloor) {
				oComment.hide().prev().hide();
				return false;
			}

			// sOut += "<br style=\"clear: both;\" />";
			oComment.show().html(sOut).prev().show();
		}
	});
};

$(function() {
	if (!(SoulogicBlog.archiveID)) {
		return;
	}
	$("#commentForm").submit(function() {
		// get token
		$.ajax({
			url: "/comment.token.ajax.php",
			data: {archive_id: SoulogicBlog.archiveID},
			success: function(data) {
				if (!data.token || !data.time) {
					alert(data.error);
					return false;
				}
				SoulogicBlog.postComment(data);
			}
		});

		return false;
	});
});

SoulogicBlog.postComment = function(token) {
	data = $("#commentForm").serializeArray();
	for (var key in token) {
		$.merge(data, [{name: key, value: token[key]}]);
	}
	// alert($.param(data));

	$.ajax({
		url: "/comment.post.ajax.php",
		dataType: "json",
		data: data,
		success: function(data) {
			if (!data.success) {
				alert(data.error);
				return;
			}
			SoulogicBlog.loadComment();
		}
	});
}
