(function($) {
    'use strict';
    
    $(document).ready(function() {
        var $container = $('#smsi-platform-container');
        
        $container.sortable({
            handle: '.smsi-drag-handle',
            update: function(event, ui) {
                $container.find('.smsi-platform-fields').each(function(index) {
                    $(this).find('.smsi-order-field').val(index + 1);
                });
            }
        });
        
        $('#smsi-platform-search').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('.smsi-platform-fields').each(function() {
                var platformName = $(this).data('platform').toLowerCase();
                if (platformName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
})(jQuery);