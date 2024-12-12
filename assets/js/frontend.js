(function($) {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // Find all inline icon divs
        const inlineIcons = document.querySelectorAll('.smsi-single-icon-inline');
        
        if (inlineIcons.length > 0) {
            // Group consecutive icons together
            let currentGroup = [];
            let lastIcon = null;
            
            inlineIcons.forEach((icon, index) => {
                // Check if this icon is consecutive with the last one
                if (lastIcon && lastIcon.nextElementSibling === icon) {
                    currentGroup.push(icon);
                } else {
                    // If we have a group and this icon isn't consecutive, wrap the previous group
                    if (currentGroup.length > 0) {
                        wrapIconGroup(currentGroup);
                    }
                    // Start a new group with this icon
                    currentGroup = [icon];
                }
                
                lastIcon = icon;
                
                // Wrap the last group if we're at the end
                if (index === inlineIcons.length - 1 && currentGroup.length > 0) {
                    wrapIconGroup(currentGroup);
                }
            });
        }
        
        function wrapIconGroup(icons) {
            if (icons.length === 0) return;
            
            // Get the alignment from the first icon's wrapper
            const alignment = icons[0].querySelector('.smsi-single-icon-wrapper').style.textAlign || 'left';
            
            // Create wrapper div
            const wrapper = document.createElement('div');
            wrapper.className = 'smsi-icons-container';
            wrapper.style.textAlign = alignment;
            
            // Insert wrapper before the first icon
            icons[0].parentNode.insertBefore(wrapper, icons[0]);
            
            // Move all icons into the wrapper
            icons.forEach(icon => {
                wrapper.appendChild(icon);
            });
        }
    });

})(jQuery);