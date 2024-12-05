"use strict";
// cuModal Start
let cuModal = $("#cuModal");
let form = cuModal.find("form");
const action = form[0] ? form[0].action : null;

$(document).on("click", ".cuModalBtn", function () {
	let data = $(this).data();
	let resource = data.resource ?? null;

	if (!resource) {
		$(form).trigger("reset");
		form[0].action = `${action}`;
		cuModal.find(".status").empty();
	}

	
	cuModal.find(".modal-title").text(`${data.modal_title}`);
	if (resource) {
		form[0].action = `${action}/${resource.id}`;

		// If form has image
		if (resource.image_with_path) {
			cuModal.find(".profilePicPreview").css("background-image", `url(${resource.image_with_path})`);
		}

		let fields = cuModal.find("input, select, textarea");
		let fieldName;

		fields.each(function (index, element) {
			fieldName = element.name;
			
			// If input name is an array
			if (fieldName.substring(fieldName.length - 2) == "[]") {
				fieldName = fieldName.substring(0, fieldName.length - 2);
			}
			
			if (fieldName != "_token" && (resource[fieldName] || resource[fieldName] == 0)) {
				if (element.tagName == "TEXTAREA") {
					if ($(element).hasClass("nicEdit")) {
						$(".nicEdit-main").html(resource[fieldName]);
					} else {
						$(`[name='${fieldName}']`).text(resource[fieldName]);
					}
				} else if ($(element).data("toggle") == "toggle") {
					if (resource[fieldName]) {
						$(element).bootstrapToggle("on");
					} else {
						$(element).bootstrapToggle("off");
					}
				} else if (element.type == "file") {
				} else {
					$(`[name='${element.name}']`).val(resource[fieldName]);
				}
			}
		});
	}

	cuModal.modal("show");
});
// cuModal end
