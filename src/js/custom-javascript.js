// Add your JS customizations here
jQuery(document).ready(function($) {
	filterData('.filters');

	$('form[name=search_form]').on('submit', function(e) {
		e.preventDefault();
		console.log("form submit");
		let search_field = $('input[name=s]').val();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/wp-admin/admin-ajax.php',
            context: this,
            data: {
                search_item: search_field,
                action: 'test_ajax_search',
            },
            beforeSend: function() { // Перед отправкой данных
						$('.filters-item').fadeOut(); // Скроем блок результатов
						$('.filters-item').remove(); // Очистим блок результатов
			},
            success: function (data) {
                if (data) {
                    $('.table-head').after(data.finded);
                    $('.pagination').hide();
                }
            },
            error: function (jqXHR, exception) {
                console.log('error = ' + jqXHR.responseText);
                console.log('exception = ' + exception);
            },
        });

	});

	$('body').on('click', '.pagination li a:not(.current)', function(e) {
		e.preventDefault();
		let link = $(this).attr('href');
		console.log("link = " + link);
		$.post( link, function( data ) {
	        var content = $(data).find('#content-items').html();
	        console.log(content);
	        var pagination = $(data).find('.pagination');
	        $('.pagination').html(pagination.html());
	        $('#content-items').animate(
	            {opacity: 0},
	            500,
	            function(){
	                $(this).html(content).animate(
	                    {opacity: 1},
	                    500
	                );
	            }
	        );
	        $('.filters-tab[data-filter=all]').addClass('bg-secondary');
	        $('.filters-tab[data-filter!=all]').removeClass('bg-secondary');
	        $('.filters-tab[data-filter=all]').addClass('text-light');
	        $('.filters-tab[data-filter!=all]').addClass('text-secondary');
	        $('.filters-tab[data-filter!=all]').removeClass('text-light');
	    });
	});
});

/**
 * Фильтрация однотипных элементов в табах
 */

function filterData(selector) {
  var className =
    arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
  var $tabs = jQuery(''.concat(selector, '-tab'));

  function toggle($this) {
    var $items = jQuery(''.concat(selector, '-item').concat(className));
    var $activeTab = $this ? jQuery($this) : jQuery(''.concat(selector, '-tab.bg-secondary'));
    var filter = $activeTab.data('filter');

    if (filter === 'all') {
      $items.show();
      $items.addClass('d-flex');
      return $items.length;
    } else {
      $items.filter('[data-filter="' + filter + '"]').show();
      $items.filter('[data-filter="' + filter + '"]').addClass('d-flex');
      $items.filter('[data-filter!="' + filter + '"]').hide();
      $items.filter('[data-filter!="' + filter + '"]').removeClass('d-flex');
      return $items.filter('[data-filter="' + filter + '"]').length;
    }
  }

  $tabs.on('click', function() {
    var $tab = jQuery(this); // изменение активности

    $tabs.not($tab).removeClass('bg-secondary');
    $tab.addClass('bg-secondary');
    $tabs.not($tab).removeClass('text-light');
    $tabs.not($tab).addClass('text-secondary');
    $tab.addClass('text-light');
    $tab.removeClass('text-secondary');
    var found = toggle($tab);
    var $empty = jQuery('.calendar-events__empty');

    if (found === 0) {
      $empty.show();
    } else {
      $empty.hide();
    }
  });
  toggle();
}
