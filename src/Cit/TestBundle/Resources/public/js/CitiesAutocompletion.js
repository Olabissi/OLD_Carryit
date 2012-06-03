	$(function() {

		$(".city").autocomplete({
			source: "http://localhost:8080/app_dev.php/searchCity",
			minLength: 2,
			select: function( event, ui ) {
				$(this).val(ui.item);
			}
		});
	});
