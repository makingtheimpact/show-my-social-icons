(function($) {
    'use strict';
    
    $(document).ready(function() {
        console.log('admin-script.js: Document ready.');

        var $container = $('#smsi-platform-container');

        // Sortable platforms
        $container.sortable({
            handle: '.smsi-drag-handle',
            update: function(event, ui) {
                $container.find('.smsi-platform-fields').each(function(index) {
                    $(this).find('.smsi-order-field').val(index + 1);
                });
            }
        });

        // Search for platforms
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
        
        // Generalized function to update icon style options
        function updateIconStyleOptions(context) {
            var $context = $(context);
            var iconType = $context.find('.icon-type-select').val();
            var $iconStyle = $context.find('.icon-style-select');
            var currentValue = $iconStyle.val();

            $iconStyle.find('option').prop('disabled', false);

            if (iconType === 'SVG') {
                $iconStyle.find('option[value="Icon only full color"]').prop('disabled', true);
                $iconStyle.find('option[value="Full logo horizontal"]').prop('disabled', true);
                $iconStyle.find('option[value="Full logo square"]').prop('disabled', true);
                if (currentValue === 'Icon only full color' || currentValue === 'Full logo horizontal' || currentValue === 'Full logo square') {
                    $iconStyle.val('Icon only black');
                }
            } else {
                $iconStyle.find('option[value="Icon only custom color"]').prop('disabled', true);
                if (currentValue === 'Icon only custom color') {
                    $iconStyle.val('Icon only full color');
                }
            }
        }

        // Apply to settings page
        $('#icon_type').on('change', function() {
            updateIconStyleOptions($(this).closest('form'));
        });
        updateIconStyleOptions($('#icon_type').closest('form')); // Run on page load

        // Apply to widget and block settings
        $('.icon-type-select').on('change', function() {
            updateIconStyleOptions($(this).closest('.widget-content, .block-content'));
        });
        $('.icon-type-select').each(function() {
            updateIconStyleOptions($(this).closest('.widget-content, .block-content'));
        }); // Run on page load
        
        // Widget Block Settings - Link the margins
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

        // Widget Block Settings - Update the icon style options
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

        // Copy to clipboard button
        $('.copy-button').on('click', function() {
            copyToClipboard(this);
        });
        function copyToClipboard(button) {
            var container = $(button).closest('.copy-container');
            if (!container.length) {
                console.error('Show My Social Icon Error: Copy container not found');
                return;
            }
            var copyText = container.find('.copy-text');
            if (!copyText.length) {
                console.error('Show My Social Icon Error: Copy text element not found');
                return;
            }
            var textToCopy = copyText.val();

            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    alert('Copied to clipboard: ' + textToCopy);
                }).catch(function(err) {
                    console.error('Show My Social Icon Error: Failed to copy text: ', err);
                });
            } else {
                // Fallback for older browsers
                copyText.select();
                document.execCommand('copy');
                alert('Copied to clipboard: ' + textToCopy);
            }
        }

        // Preview shortcode button
        $('.preview-button').on('click', function() {
            previewShortcode(this);
        });
        function previewShortcode(button) {
            var container = $(button).closest('.copy-container');
            var textarea = container.find('.copy-text');
            var previewDiv = container.next('.shortcode-preview');

            if (textarea.length === 0 || previewDiv.length === 0) {
                console.error('Show My Social Icon Error: Textarea or preview div not found');
                return;
            }

            var shortcode = textarea.val();

            // Make an AJAX request to the server to process the shortcode
            $.ajax({
                url: smsiData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'preview_shortcode',
                    shortcode: shortcode,
                    nonce: smsiData.nonce
                },
                success: function(response) {
                    if (response.trim() === '') {
                        previewDiv.html('<p>No icons to display. Please check the shortcode and platform URLs.</p>');
                    } else {
                        previewDiv.html(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Show My Social Icon Error: ajax call failed: ', error);
                }
            });
        }
    });

})(jQuery);