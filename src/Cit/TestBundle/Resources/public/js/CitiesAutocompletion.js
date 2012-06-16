	$(function() {

		$(".city").autocomplete({
			source: "http://searchCity",
			minLength: 2,
			select: function( event, ui ) {
				$(this).val(ui.item);
			}
		});
	});
