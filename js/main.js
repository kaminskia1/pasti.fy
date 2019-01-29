/*jshint multistr: true */
/*jshint esversion: 6 */

$(".material-checkbox input").click(()=>{
	"use strict";
	let state = $(".material-checkbox input").prop("checked");
	if (state === true) {
		$(".encrypt-container").css({
			width: '279px'
		});
	} else {
		$(".encrypt-container").css({
			width: '94px'
		});
	}
});

$(".form-content input:button").click(()=>{
	"use strict";
	if (($(".form-content textarea").val()).length < 1) {
		alert("Pastebox is empty!");
	} else {
		let encryptState = $(".material-checkbox input:checkbox").prop("checked");
		let title = $("form .form-header input").val();
		let encryptPassword = null;
		if (encryptState === true) {
			encryptPassword = $(".encrypt-overflow input:text").val();
		}
		let data = $(".form-content textarea").val();
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {
				function: "newPost",
				title: title,
				data: data,
				key: encryptPassword
			},
			success: (tag) => {
				location.href = "viewPost.php?tag=" + tag;
			}
		});
	}
});

$(".form-header .form-title").keydown(()=>{
	"use strict";
	let string = $(".form-header .form-title").val();
	if ($(".form-header .form-width-span").length) {
		$(".form-header .form-width-span").html(string);
	} else {
		$(".form-header").prepend("<span class='form-width-span' style='display:none;font-family:Raleway;font-size:24px;'>" + string + "</span>");
	}
	let width = $(".form-header .form-width-span").width();
	if ((string).length < 1) {
		width = 119.141;
	}
	$(".form-header .form-title").width(width + 10);
});

$("input.view-unset-input.tag").keyup(()=>{
	"use strict";
	let tag = $("input.view-unset-input").val();
	if (tag.length > 29) {
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {function: "checkTag", tag: tag},
			success: (content)=>{
				if (content === "true") {
					$("input.view-unset-input").removeClass("invalid").addClass("valid");
					setTimeout(()=>{location.href = "viewPost.php?tag=" + tag;},1000);
                } else if (content === "false") {
					$("input.view-unset-input").removeClass("valid").addClass("invalid");
                }
            }
        });
    } else {
    	$("input.view-unset-input").removeClass("valid").addClass("invalid");
	}
});

$("button.view-password-submit").click(()=>{
	"use strict";
	var pw = $("input.view-unset-input.password").val();
	location.href = location.href + "&key=" + pw;
});