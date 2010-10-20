var dialogs = {

	dialogMessage 				: $('<div/>'),

	infoDialogMessageTimeOut	: 2000, //Time to show Info Dialog before closing it.

	currentDialogMessage 		: null,

	__timeOut 					: null,

	show 						: function(message, title, passedOptions) {

		var options = {
			position: 	['right','top'],
			width: 		400,
			height: 	120,
			hide: 		'blind',
			zIndex: 	3999
		};
		$.extend(options, passedOptions);

		/** Creates a tmp copy form the default dialog */
		dialogs.currentDialogMessage = dialogs.dialogMessage;
		dialogs.currentDialogMessage.attr('title', title);
		dialogs.currentDialogMessage.html(message);
		dialogs.currentDialogMessage.dialog(options);
	},

	showError 					: function(message) {
		var options = {
			modal	: true,
			open	: function () {
				/** Removes the info class and adds the error class */
				$(this).parents('.ui-dialog:first').find('.ui-dialog-titlebar').removeClass('ui-state-highlight').addClass('ui-state-error');
			},
			close	: function () {
				/* Return focus to first errored visible input element (<input>, <select>, <textarea>) */
				if (modals.currentDialogModalForm != null) {
					$('.error-message:first', modals.currentDialogModalForm).parent().find(':input:visible:first').focus();
				}
			}
		};
		dialogs.show(message, 'Error', options);
	},

	showSuccess					: function(message) {
		var options = {
			open	: function () {
				/** Removes the error class and adds the info class */
				$(this).parents('.ui-dialog:first').find('.ui-dialog-titlebar').removeClass('ui-state-error').addClass('ui-state-highlight');
			},
			close	: function () {
				clearTimeout(dialogs.timeOut);
			}
		};
		dialogs.show(message, 'Information', options);

		dialogs.__timeOut = setTimeout(
			function() {
				dialogs.currentDialogMessage.dialog('close');
			}, dialogs.infoDialogMessageTimeOut);
	},

};