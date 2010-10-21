/** Dom ready "binding" common to all layouts */
$(document).ready(function($) {


	$('#actions ul li a').live('click',
		function() {
			$(this).parent().parent().hide();
			return true;
		}
	);

	/** Modal forms */
        if (typeof(modals) != 'undefined') {
            modals.bind();
        }


	/** Bind clear reset button */
	$('.clear').live('click', function() {
		var clearInput = $('<input/>').attr('type', 'hidden').attr('value', 'clear').attr('name', 'data[Filter][action]');
		$('.clear').parent().append(clearInput);
		$('form').submit();
		return true;
	});


	/** Change Language */
	$('#language').change(
		function() {
			alert('Changed!');
		}
	);


	/** Datepicker */
	$('.datepicker').datepicker({
		dateFormat: 'yy-mm-dd',
		showOn: 'button',
		buttonImage: 'images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true

	});


	/** Menu actions */
	$('#actions').live('click',
		function() {
			$('ul', $(this)).toggle();
		}
	);


});



/** Useful function to avoid using Router::url everywere */
jQuery.path = function(relativePath) {
    if (relativePath == undefined) {
        relativePath = '';
    }

	if (relativePath[0] == '/') {
		return relativePath;
	} else {
		var pasedInfo = $.parseJSON(info);
		return pasedInfo.base_url + relativePath;
	}
}

/** Returns true if data is in JSON format */
/** TODO: Can be improved a lot to gain a better result */
jQuery.isJSON = function(data) {
	if (typeof data == 'string'
		&& data.length > 0
		&& data[0] == '{'
		&& data[data.length-1] == '}') {

		return true;
	} else {
		return false;
	}
}


/** Converts jquery html object to plain html */
jQuery.getHtml = function(object) {
	return $('<div>').append(object.clone()).remove().html();
}


/**
 * If a string is too long, shorten it in the middle
 * @return string
 */
jQuery.shorten = function (target, limit) {

    var html = $('span', target);
    var numSpans = html.length;

    var px = 0;
    //Iterate the spans tags and add their sizes to result
    for (var i = 0; i < numSpans; i++) {
       px += $(html[i]).width();
    }

    if (numSpans % 2 == 1) {
        objMax = numSpans / 2 - 0.5;
        objMin = numSpans / 2 - 1.5;
    } else {
        objMax = numSpans / 2 - 1;
        objMin = numSpans / 2 - 2;
    }


    for (i = 0; px > limit && (objMax < numSpans - 1 || objMin > 0) && i < 20; i++) {
        if (i % 2 == 0) {
            $(html[objMax++]).text('...');
        } else {
            $(html[objMax--]).text('...');
        }
    }
}