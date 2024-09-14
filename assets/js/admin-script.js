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

        function updateIconStyleOptions() {
            var $widget = $(this).closest('.widget-content');
            var iconType = $widget.find('.icon-type-select').val();
            var $iconStyle = $widget.find('.icon-style-select');
            
            $iconStyle.find('option').prop('disabled', false);
    
            if (iconType === 'SVG') {
                $iconStyle.find('option[value="Icon only full color"]').prop('disabled', true);
                $iconStyle.find('option[value="Full logo horizontal"]').prop('disabled', true);
                $iconStyle.find('option[value="Full logo square"]').prop('disabled', true);
                if ($iconStyle.val() === 'Icon only full color' || $iconStyle.val() === 'Full logo horizontal' || $iconStyle.val() === 'Full logo square') {
                    $iconStyle.val('Icon only black');
                }
            } else {
                $iconStyle.find('option[value="Icon only custom color"]').prop('disabled', true);
                if ($iconStyle.val() === 'Icon only custom color') {
                    $iconStyle.val('Icon only full color');
                }
            }
        }
    
        $('.icon-type-select').on('change', updateIconStyleOptions);
        $('.icon-type-select').each(updateIconStyleOptions); // Run on page load
        
        $(document).on('click', '[id$=link_margins]', function() {
            var $button = $(this);
            var $form = $button.closest('form');
            var $margins = $form.find('input[id$=margin_top], input[id$=margin_right], input[id$=margin_bottom], input[id$=margin_left]');
            
            if ($button.hasClass('linked')) {
                $button.removeClass('linked').text('Link Margins');
                $margins.off('input.linked');
            } else {
                $button.addClass('linked').text('Unlink Margins');
                var maxMargin = Math.max(...$margins.map(function() { return parseInt($(this).val()) || 0; }));
                $margins.val(maxMargin);
                
                $margins.on('input.linked', function() {
                    var newValue = $(this).val();
                    $margins.val(newValue);
                });
            }
        });

        $('.widget-content').each(function() {
            var $widget = $(this);
            var widgetId = $widget.find('.widget-id').val();
            
            var $iconTypeSelect = $widget.find('#widget-' + widgetId + '-icon_type');
            var $iconStyleSelect = $widget.find('#widget-' + widgetId + '-icon_style');
            var $customColorInput = $widget.find('#widget-' + widgetId + '-custom_color');
    
            function updateCustomColorVisibility() {
                var iconType = $iconTypeSelect.val();
                var iconStyle = $iconStyleSelect.val();
                
                if (iconType === 'PNG') {
                    $iconStyleSelect.find('option[value="Icon only custom color"]').prop('disabled', true);
                    if (iconStyle === 'Icon only custom color') {
                        $iconStyleSelect.val('Icon only full color');
                    }
                } else {
                    $iconStyleSelect.find('option[value="Icon only custom color"]').prop('disabled', false);
                }
                
                $customColorInput.prop('disabled', iconType === 'PNG' || iconStyle !== 'Icon only custom color');
            }
            
            $iconTypeSelect.change(updateCustomColorVisibility);
            $iconStyleSelect.change(updateCustomColorVisibility);
            
            updateCustomColorVisibility();
    
            $widget.find('#widget-' + widgetId + '-link_margins').click(function() {
                var $topMargin = $widget.find('#widget-' + widgetId + '-margin_top');
                var $rightMargin = $widget.find('#widget-' + widgetId + '-margin_right');
                var $bottomMargin = $widget.find('#widget-' + widgetId + '-margin_bottom');
                var $leftMargin = $widget.find('#widget-' + widgetId + '-margin_left');
    
                $rightMargin.val($topMargin.val());
                $bottomMargin.val($topMargin.val());
                $leftMargin.val($topMargin.val());
            });
        });
    });
})(jQuery);