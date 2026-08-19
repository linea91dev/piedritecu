$(document).ready(function() {
	$('body').find('.image-picker').each(function() {
		var select = $(this).wrap('<div class="dropdown d-inline-block"></div>');
		select.addClass('d-none');

		var list = $('<ul style="max-height: 170px; overflow-y: auto;" class="dropdown-menu p-0"></ul>');
		var selected_item = select.find(':selected');
		var button = $('<button type="button" class="btn border text-left px-4 custom-select" data-display="static" data-toggle="dropdown"><span class="align-text-bottom mr-1"><img class="shadow-sm" src="' + selected_item.attr('data-img') + '" height="15px"></img></span>' + selected_item.text() + '</button>');

		select.children('option').each(function() {
			var option = $(this);
			list.append(
				$('<li class="dropdown-item" value="' + option.attr('value') + '"><span class="align-text-bottom mr-1"><img class="shadow-sm" src="' + option.attr('data-img') + '" height="15px"></img></span>' + option.text() + '</li>')
					.click(function() {
						select.val($(this).attr('value'));
						button.html($(this).html());
					})
			);
		});

		select.after(list).after(button);
		setTimeout(function() {
			button.css('width', list.innerWidth());
		}, 100);
	});	
});