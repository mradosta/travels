var modals = {

	currentDialogModalForm 			: 	null,

	currentDialogPopUp 				: 	null,

	returnTo						: 	null,

	refreshDiv						:	null,

	refreshUrl						:	null,

	bindDocumentReady				: 	function() {

		$(document).ready(function() {

			$(this).find('form :input:visible:first').focus();


			/** Bind any datepicker class to the calendar */
			$('.datepicker').datepicker({
				dateFormat: 'yy-mm-dd',
				showOn: 'button',
				buttonImage: $.path(base_url + 'img/date_select.jpg'),
				buttonImageOnly: true
			});


			/** Bind any file input */
			uploaders.bind();


			/** Callback optionaly defined in views to execute js code when the dom is ready
			* Passes the caller element
			*/
			if (typeof afterRender == 'function') {
				afterRender();
			}

		});

	},

	bind							: 	function() {
		modals.bindOpen();
		modals.bindClose();
		modals.bindSubmit();
	},

	bindOpen 						: 	function() {

		$('.open_modal').live('click', function() {

			/** Logic to bind a or img elements */
			var a;
			if (this.tagName == 'IMG') {
				a = $(this).parent();
				a.attr('title', $(this).attr('title'));
				a.attr('class', $(this).attr('class'));
				a.attr('return_to', $(this).attr('return_to'));
			} else {
				a = $(this);
			}

			if (a.attr('return_to') != undefined) {
				$('body').attr('return_to', a.attr('return_to'));
			}

			// Figure out modal width
			var modalWidth = 580;
			if (a.hasClass('wide')) {
				var modalWidth = 900;
			}


			// Do not close previous modals when class multiple_modal is present
			if (modals.currentDialogModalForm != null
				&& modals.currentDialogPopUp == null
				&& !a.hasClass('multiple_modal')) {

				// Avoid blinking when closing and open very fast
				var prevDialogModalForm = modals.currentDialogModalForm;
				modals.currentDialogModalForm = null;
				setTimeout(
					function() {
						prevDialogModalForm.dialog('close');
					}
				, 800);
			}

			if (modals.currentDialogModalForm == null) {

				modals.currentDialogModalForm = 	$('<div/>').dialog({
					autoOpen: 		false,
					closeOnEscape:	false,
					title: 			a.attr('title'),
					width: 			modalWidth,
					height: 		630,
				});

			} else if (modals.currentDialogPopUp == null) {

				modals.currentDialogPopUp = 	$('<div/>').dialog({
					autoOpen: 		false,
					closeOnEscape:	false,
					title: 			a.attr('title'),
					width: 			modalWidth,
					height: 		630,
				});

			}
			if (a.hasClass('ajax_report')) {

				$.ajax({
					url			: a.attr('href'),
					data		: $($('.ajax_form')).serialize(),
					type		: 'post',
					cache		: false,

					success		: function (data) {

						// Manually try to detect if this is a json or html
						if ($.isJSON(data)) {

							//parse json
							parsedData = $.parseJSON(data);
							if (parsedData.state == 'success') {
								dialogs.showSuccess(parsedData.message);
							} else {
								dialogs.showError(parsedData.message);
							}

						} else {

							if (modals.currentDialogPopUp != null) {
								modals.currentDialogPopUp.html(data).dialog('open');
							} else {
								modals.currentDialogModalForm.html(data).dialog('open');
							}

							modals.bindDocumentReady();
						}
					}
				});
				
			} else {
			
				$.ajax({
					url			: a.attr('href'),
					type		: 'get',
					cache		: false,

					error		: function (parsedData) {

					},

					success		: function (data) {

						// Manually try to detect if this is a json or html
						if ($.isJSON(data)) {

							//parse json
							parsedData = $.parseJSON(data);
							if (parsedData.state == 'success') {
								dialogs.showSuccess(parsedData.message);
							} else {
								dialogs.showError(parsedData.message);
							}

						} else {

							if (modals.currentDialogPopUp != null) {
								modals.currentDialogPopUp.html(data).dialog('open');
							} else {
								modals.currentDialogModalForm.html(data).dialog('open');
							}

							modals.bindDocumentReady();
						}
					}
				});
			}
			return false;
		});
	},

	bindClose 						: 	function() {

		$('.close_modal').live('click', function() {

			// When data is set, means that should return to somewhere.
			if (modals.currentDialogPopUp != null) {

				if ($(this).attr('data') != undefined) {
					var data = $(this).attr('data').split('|');
					$('#' + $('body').attr('return_to')).val(data[0]);
					$('#' + $('body').attr('return_to') + '__').val(data[1]);
					$('body').removeAttr('return_to');

					modals.currentDialogPopUp.dialog('close');
					modals.currentDialogPopUp = null;
				}

			} else {

				modals.currentDialogModalForm.dialog('close');
				modals.currentDialogModalForm = null;

			}

			return false;
		});
	},

	bindSubmit 						: 	function() {

		$('form.ajax_form').live('submit', function(event) {

			event.preventDefault();

			if (!uploaders.empty()) {
				uploaders.upload();
				modals.waitBeforeSubmit(this);
			} else {
				modals.doSubmit(this);
			}

			return false;
		});
	},

	waitBeforeSubmit				: 	function (form) {

		if (uploaders.isUploading()) {
			setTimeout(
				function() {
					modals.waitBeforeSubmit(form);
				},
				500
			);
		} else {
			if (uploaders.fillHiddens()) {
				modals.doSubmit(form);
			} else {
				return false;
			}
		}
	},

	doSubmit						: 	function(form) {

		$.ajax({
			url			: $(form).attr('action'),
			data		: $(form).serialize(),
			type		: 'post',
			cache		: false,
			beforeSend	: function() {

				/** Removes previous error */
				$('div.error').removeClass('error');
				$('div.error-message').remove();

			},

			error		: function (parsedData) {

			},

			success		: function (data) {

				// Manually try to detect if response is json or html
				if ($.isJSON(data)) {

					//parse json
					parsedData = $.parseJSON(data);

					if (parsedData.state == 'error') {

						var currentParent = null;
						$.each(parsedData.fields, function(k, v) {
							currentParent = $('#' + k, $('.ui-dialog')).parent();

							if (currentParent.hasClass('error')) {
								$('.error-message', currentParent).html(v);
							} else {
								currentParent
									.addClass('error')
									.append($('<div/>').addClass('error-message').html(v));
							}
						});
						dialogs.showError(parsedData.message);

					} else if (parsedData.state == 'success') {

						dialogs.showSuccess(parsedData.message);
						modals.currentDialogModalForm.dialog('close');
						modals.currentDialogModalForm = null;

						if (parsedData.redirect != undefined) {
							setTimeout(
								function() {
									window.location = parsedData.redirect;
								},
							1000);
						} else {

							/** Refresh */
							if (modals.refreshDiv != null && modals.refreshUrl != null) {

								$(modals.refreshDiv).load(modals.refreshUrl);
								modals.refreshDiv = null;
								modals.refreshUrl = null;

							} else {

								//$('#content_for_layout').load(location.href);
								location.reload(true);
							}
						}

					}

				// html data
				} else {

					if (modals.currentDialogPopUp != null) {
						modals.currentDialogPopUp.html(data).dialog('open').dialog('moveToTop');
					} else {
						modals.currentDialogModalForm.html(data).dialog('open').dialog('moveToTop');
					}

					modals.bindDocumentReady();

				}

			},
		});

	}

}